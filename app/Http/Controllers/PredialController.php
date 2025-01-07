<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime, DateInterval, DatePeriod;

class PredialController extends Controller
{
    public function estadisticas($fecha_inicio, $fecha_fin){

    $db = DB::connection('predial_sadmun');

    //Fecha Inicio
    $fecha_inicio = date($fecha_inicio);

    //Fecha Fin
    $fecha_fin = date($fecha_fin);

    //Ultima Semana
    $fecha_hoy = date('Y-m-d');
    $fecha_previa = date("Y-m-d",strtotime($fecha_hoy."- 6 days"));

    //Ultima Semama Anterior
    $fechaInicioAnterior = date("Y-m-d",strtotime($fecha_previa."- 1 year"));
    $fechaFinAnterior = date("Y-m-d",strtotime($fecha_hoy."- 1 year"));

    //Año Pasado
    $fecha_inicioAnterior = date("Y-m-d",strtotime($fecha_inicio."- 1 year"));
    $fecha_finAnterior = date("Y-m-d",strtotime($fecha_fin."- 1 year"));

    //Año Actual
    $query = "SELECT origen, count(*) AS TOTAL, sum(importe) AS IMPORTE FROM Recibos_Predial_Urbano WHERE id_devolucion = 0 and Status = 1 and origen is not null and fecha >= '".$fecha_inicio." 00:00:00' and fecha <= '".$fecha_fin." 23:59:59'  group by origen";
    $result = $db->select($query);
    $data = $this->utf8_encode_deep($result);


    $totalPagos=0; $totalImporte=0;
    foreach($data as $row) {
        $datos['detalle'][] = $row;
        $totalPagos = $totalPagos + $row['TOTAL'];
        $totalImporte = $totalImporte + $row['IMPORTE'];
    }

    $datos['ANIO'] = date('Y');
    $datos['TOTAL'] = number_format($totalPagos);
    $datos['IMPORTE'] = $totalImporte;
    $datos['IMPORTE_LEYENDA'] = ($totalImporte >= 1000000) ? number_format(round($totalImporte, 2),2) . ' M' : ( ($totalImporte >= 10000 ) ? number_format(round($totalImporte, 2),2) . ' K' :number_format(round($totalImporte, 2),2) . ' MXN' );


    //Año Anterior
    $queryAnterior = "SELECT origen, count(*) AS TOTAL, sum(importe) AS IMPORTE FROM Recibos_Predial_Urbano WHERE id_devolucion = 0 and Status = 1 and origen is not null and fecha >= '".$fecha_inicioAnterior." 00:00:00' and fecha <= '".$fecha_finAnterior." 23:59:59'  group by origen";
    $result = $db->select($queryAnterior);
    $anterior = $this->utf8_encode_deep($result);

    $totalPagosAnterior=0; $totalImporteAnterior=0;
    foreach($anterior as $row) {
        $anteriores['detalle'][] = $row;
        $totalPagosAnterior = $totalPagosAnterior + $row['TOTAL'];
        $totalImporteAnterior = $totalImporteAnterior + $row['IMPORTE'];
    }

    $anteriores['ANIO'] = date('Y') - 1 ;
    $anteriores['TOTAL'] = $totalPagosAnterior;
    $anteriores['IMPORTE'] = $totalImporteAnterior;
    $anteriores['IMPORTE_LEYENDA'] = ($totalImporteAnterior >= 1000000) ? number_format(round($totalImporteAnterior, 2),2) . ' M' : ( ($totalImporteAnterior >= 10000 ) ? number_format(round($totalImporteAnterior, 2),2) . ' K' :number_format(round($totalImporteAnterior, 2),2) . ' MXN' );



    //Semana Actual
    $meses = ['Ene', 'Feb', 'Mar', 'Abril', 'May', 'Jun', 'Jul', 'Ago','Sep', 'Oct', 'Nov', 'Dic'];
    $querySemanal = "SELECT fecha, count(*) AS TOTAL, sum(importe) AS IMPORTE FROM Recibos_Predial_Urbano WHERE id_devolucion = 0 and Status = 1 and origen is not null and fecha >= '".$fecha_previa." 00:00:00' and fecha <= '".$fecha_hoy." 23:59:59'  group by fecha"; 
    $result = $db->select($querySemanal);
    $datosSemanal = $this->utf8_encode_deep($result);


    /** SACA LA SEMANA ACTUAL */
    $fechaInicio = new DateTime($fecha_previa);
    $fechaFin = new DateTime('now');
    $intervalo = new DateInterval('P1D');
    $periodo = new DatePeriod($fechaInicio, $intervalo, $fechaFin);

    $fechas = [];
    foreach($periodo as $fecha){
        $fechas[] = $fecha->format('Y-m-d 00:00:00');
    }


    /* SACA LA SEMANA ANTERIOR */
    $fecha_previaAnterior = new DateTime(date("Y-m-d",strtotime($fecha_previa."- 1 year"))); 
    $fecha_finAnterior = new DateTime(date("Y-m-d",strtotime($fecha_hoy."- 1 year + 1 days"))); 
    $intervalo2 = new DateInterval('P1D');
    $periodo2 = new DatePeriod($fecha_previaAnterior, $intervalo2, $fecha_finAnterior);

    $fechas2 = [];
    foreach($periodo2 as $fecha){
        $fechas2[] = $fecha->format('Y-m-d 00:00:00');
    }

    //return $datosSemanal;

    $ultimados = [];
    foreach ( $fechas as $element ) {

        foreach( $datosSemanal as $encontrado ){
            $key = array_search($element, array_column((array)  $datosSemanal, 'fecha'));    
        }

        if(!$key && $key == ''){
                $agregar['fecha'] = $element;
                $agregar['TOTAL'] = 0;
                $agregar['IMPORTE'] = 0;
                $ultimados[] = $agregar;
        }else{
                $agregar['fecha'] = $element;
                $agregar['TOTAL'] = $datosSemanal->$key['TOTAL'];
                $agregar['IMPORTE'] = $datosSemanal->$key['IMPORTE'];
                $ultimados[] = $agregar;
        }
    }

    foreach($ultimados as $row) {
        $row['fecha'] = date("d",strtotime($row['fecha'])) .'-'. $meses[date("m",strtotime($row['fecha'])) - 1];
        $ultimos[] = $row;
    }

    //Semana Anterior
    $querySemanalAnterior = "SELECT fecha, count(*) AS TOTAL, sum(importe) AS IMPORTE FROM Recibos_Predial_Urbano WHERE id_devolucion = 0 and Status = 1 and origen is not null and fecha >= '".$fechaInicioAnterior." 00:00:00' and fecha <= '".$fechaFinAnterior." 23:59:59'  group by fecha"; 
    $result = $db->select($querySemanalAnterior);
    $datosSemanalAnterior = $this->utf8_encode_deep($result);


    $ultimados = [];
    foreach ( $fechas2 as $element ) {

        foreach( $datosSemanalAnterior as $encontrado ){
            $key = array_search($element, array_column((array)  $datosSemanalAnterior, 'fecha'));    
        }

        if(!$key && $key == ''){
                $agregar['fecha'] = $element;
                $agregar['TOTAL'] = 0;
                $agregar['IMPORTE'] = 0;
                $ultimados[] = $agregar;
        }else{
                $agregar['fecha'] = $element;
                $agregar['TOTAL'] = $datosSemanalAnterior->$key['TOTAL'];
                $agregar['IMPORTE'] = $datosSemanalAnterior->$key['IMPORTE'];
                $ultimados[] = $agregar;
        }
    }

    foreach($ultimados as $row) {
        $row['fecha'] = date("d",strtotime($row['fecha'])) .'-'. $meses[date("m",strtotime($row['fecha'])) - 1];
        $ultimosAnterior[] = $row;
    }
    return response()->json([ 'actual' => $datos, 'anterior' => $anteriores, 
                              'ultimos_dias' => $ultimos, 'ultimos_dias_anterior' => $ultimosAnterior]);

        
    }

    //Recorre el resultado de una query MSSQL (Object) y los codifica a utf8
    public function utf8_encode_deep($input) {
        $data = [];
        $result = [];

        foreach($input as $row) {
            foreach($row as $key => $value) {
                $data[utf8_encode($key)] = utf8_encode(trim($value));
            }
            $result[] = $data;
        }

        return (Object) $result;
    }

    public function prueba(){
        $db = DB::connection('predial');
        $query = "SELECT COUNT(*) FROM impresos WHERE created_at >= '2024-01-01 00:00:00' AND created_at <= '2024-01-05 23:59:59'";

        $result = $db->select($query);
        $data = $this->utf8_encode_deep($result);

        return $data;
    }
}
