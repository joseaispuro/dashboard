<template>
  <h1>Estadísticas de Impuesto Predial Urbano</h1>
  <div class="containter">
      <div class="row">

        <div class="col-md-9">

          <div class="row">

            <div class="col-md-6 sombra mb-2" style="padding-bottom:10px;">
              <h5>Período</h5>

              <div class="row">
                <div class="col-md-9">
                  <input type="date" class="form-control"  v-model="desde">
                  <input type="date" class="form-control" v-model="hasta">
                </div>
                <div class="col-md-3">
                  <div class="d-grid gap-2" style="height:100%;">
                    <button class="btn btn-dark" @click="actualizar" type="button">Actualizar</button>
                  </div>
                </div>
              </div>
              
            </div>

            <div class="col-md-6 sombra mb-2">
                <h5>Ingreso Digital</h5>
                <p class="numero">${{info.actual?.IMPORTE_LEYENDA}}</p>
                <p class="leyenda">{{info.actual?.TOTAL}} pagos</p>
            </div>

            <div class="col-md-6 sombra mb-2">
              <h5>Ingreso vs Año Anterior</h5>
              <Bar
                id="workhr-chart2"
                :data="chartComparativo"
                key="{{Math.random()}}"
              >
              </Bar>
            </div>

            <div class="col-md-6 sombra mb-2" style="max-height:450px; padding-bottom:10px;">
                <h5>Últimos 7 días</h5>
                <Line :data="data" :options="options" style="max-height:370px;" />

            </div>

            <div class="col-md-12 sombra mb-2" style="padding-bottom:10px;">
                <h5>Distribución de Importes</h5>

                <div class="row">
                  <div class="col-md-8 col-sm-7">
                    <Doughnut :data="comparacion" style="max-height:400px;"/>
                  </div>

                  <div class="col-md-4 leyendas col-sm-5 ">
                      <p class="bancos">Bancos <strong>${{bancosImporte}}</strong></p>
                      <p class="autoservicio">Autoservicio <strong>${{AutoServicioImporte}}</strong></p>
                      <p class="web">Página Web <strong>${{webImporte}}</strong></p>
                      <p class="spei">SPEI <strong>${{speiImporte}}</strong></p>
                      <p class="codi">CODI <strong>${{CodiImporte}}</strong></p>
                  </div>
                </div>
                
            </div>

            <div class="col-md-12 sombra mb-2" style="padding-bottom:10px;">
                <h5>Número de Pagos</h5>

                <div class="row">
                  

                  <div class="col-md-8 col-sm-7">
                    <Doughnut :data="comparacionPagos" style="max-height:400px;"/>
                  </div>

                  <div class="col-md-4 leyendas col-sm-5">
                        <p class="bancos">Bancos <strong>{{bancosTotalPagos.toLocaleString('es-MX')}}</strong></p>
                        <p class="autoservicio">Autoservicio <strong>{{autoservicioTotalPagos.toLocaleString('es-MX')}}</strong></p>
                        <p class="web">Página Web <strong>{{paginaWebTotalPagos.toLocaleString('es-MX')}}</strong></p>
                        <p class="spei">SPEI <strong>{{speiTotalPagos.toLocaleString('es-MX')}}</strong></p>
                        <p class="codi">CODI <strong>{{codiTotalPagos.toLocaleString('es-MX')}}</strong></p>
                  </div>   

                </div>
                            
            </div>
           
          </div>

        </div>

        <div class="col-md-3">

          <div class="row">
              
            <div class="col-md-12 sombra mb-2">
                <h5>Distribución Bancos</h5>
                <Bar
                  id="workhr-chart3"
                  :data="distribucionBancos"
                  :options="opciones"
                >
                </Bar>
              </div>

            <div class="col-md-12 sombra mb-2">
                <h5>Distribución Autoservicio</h5>
                <Bar
                  id="workhr-chart3"
                  :data="distribucionAutoservicio"
                  :options="opciones"
                >
                </Bar>
            </div>

             <div class="col-md-12 sombra mb-2">
                <h5>Distribución Página Web</h5>
                <Bar
                  id="workhr-chart3"
                  :data="distribucionWeb"
                  :options="opciones"
                >
                </Bar>
              </div>
             
          </div>
          
        </div>



    </div>
  </div>
</template>
	

<script setup>
import {ref, reactive, computed, watch, onMounted} from 'vue'
import { Bar, Doughnut, Line } from "vue-chartjs"
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
    LineElement,
    PointElement,
    Colors
} from "chart.js"


const pagina = ["PAGOS EN LINEA"];
const bancos = ['BANCOMER', 'BANORTE', 'HSBC', 'SANTANDER','BAJIO','BANAMEX','BANCO AZTECA','SCOTIABANK'];
const spei = ['HSBC Spei'];
const autoservicio = ['KIOSKO', 'OXXO'];


const desde = ref('2025-01-09');
const hasta = ref('2025-01-09');


//Importes por banco
let importeBANCOMER= ref(0), importeBANORTE = ref(0), importeHSBC = ref(0), importeSANTANDER = ref(0), importeBAJIO = ref(0),
importeBANAMEX = ref(0), importeAZTECA = ref(0), importeSCOTIABANK = ref(0), importeKIOSKO = ref(0), importeOXXO = ref(0),
importeSPEI = ref(0), importeWEB = ref(0);

//Total pagos por metodo
let bancosTotalPagos = ref(0) ,autoservicioTotalPagos = ref(0), paginaWebTotalPagos = ref(0), speiTotalPagos = ref(0), codiTotalPagos = ref(0);

let info = ref({})

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement,PointElement, Colors)

const chartComparativo = ref({
    labels: ['Ingresos Año Anterior vs Actual'],
    datasets: [
        {
            label: ["2024"],
            data: [0],
            backgroundColor: "#ff7878",
        },
        {
            label: ["2025"],
            data: [0],
            backgroundColor: '#62a2f3',
        },
    ],
})

  
const labels = bancos
const distribucionBancos = ref({
  labels: labels,
  datasets: [{
    axis: 'y',
    label: 'Importe por Bancos',
    data: [0, 0, 0, 0, 0, 0, 0],
    fill: false,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
       'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
})


const distribucionAutoservicio = ref({
  labels: autoservicio,
  datasets: [{
    axis: 'y',
    label: 'Importe por Autoservicio',
    data: [0, 0],
    fill: false,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
       'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
})

const distribucionWeb = ref({
  labels: ['BBVA Multipagos', 'HSBC Vicomm'],
  datasets: [{
    axis: 'y',
    label: 'Importe por Motor de Pago',
    data: [0, 0],
    fill: false,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
    ],
    borderWidth: 1
  }]
})


const distribucion = ref({
    labels: ['Ingresos Año Anterior vs Actual'],
    datasets: [
        {
            label: ["2024"],
            data: [12000],
            backgroundColor: "#ff7878",
        },
        {
            label: ["2025"],
            data: [14000],
            backgroundColor: '#62a2f3',
        },
    ],
})

const comparacion = ref({
    labels: ['Bancos','Autoservicio', 'Página Web', 'SPEI', 'CODI'],
    datasets: [
        {
            label: ["Distribución de Importes por Método de Pago"],
            data: [0, 0, 0, 0, 0],
            backgroundColor: ["#c95569",'#6cb9d1','#1cbbb5','orange','orangered'],
        },
    ],
})

const comparacionPagos = ref({
    labels: ['Bancos','Autoservicio', 'Página Web', 'SPEI', 'CODI'],
    datasets: [
        {
            label: ["Distribución de Números de Pagos por Método"],
            data: [0, 0, 0, 0 , 0],
            backgroundColor: ["#c95569",'#6cb9d1','#1cbbb5','orange','orangered'],
        },
    ],
})

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        x: {
            grid: {
                color: "black",
            },
            ticks: {
                color: "#eee",
            },
        },
        y: {
            grid: {
                color: "black",
            },
            ticks: {
                color: "#eee",
            },
        },
    },
    legend: {
        labels: {
            fontColor: "#eee",
        },
    },
})

const chartStyles = computed(() => {
    return {
        width: "100%",
        height: "20ch",
    }
})


const data = ref({
  labels: ['Ene-1', 'Ene-2', 'Ene-3', 'Ene-4', 'Ene-5', 'Ene-6', 'Ene-7'],
  datasets: [
    {
      label: '2024',
      backgroundColor: "#ff7878",
      data: [0, 0, 0, 0, 0, 0, 0]
    },
    {
      label: '2025',
      backgroundColor: '#62a2f3',
      data: [0, 0, 0, 0, 0, 0, 0]
    },
  ]
})

const options = {
  responsive: true,
  maintainAspectRatio: false,
}


const bancosImporte = computed(() => {
  let sumado = Number(importeBANCOMER.value.IMPORTE) + Number(importeBANORTE.value.IMPORTE) + Number(importeHSBC.value.IMPORTE) +  Number(importeSANTANDER.value.IMPORTE)
   + Number(importeBAJIO.value.IMPORTE) +  Number(importeBANAMEX.value.IMPORTE) +  Number(importeAZTECA.value.IMPORTE) +  Number(importeSCOTIABANK.value.IMPORTE);

  return (sumado == 0) ? 0 : (( sumado < 1000000 ) ?    (sumado/1000).toFixed(1) + ' K' :   (sumado/1000000).toFixed(1) + ' M' )    
})

const speiImporte = computed(() => {
  return (importeSPEI.value.TOTAL_LEYENDA) ? importeSPEI.value.TOTAL_LEYENDA : 0;
})

const webImporte = computed(() => {
  return (importeWEB.value.TOTAL_LEYENDA) ? importeWEB.value.TOTAL_LEYENDA : 0;
})

const AutoServicioImporte = computed(() => {
  let sumado = Number(importeOXXO.value.IMPORTE) + Number(importeKIOSKO.value.IMPORTE);
  return (sumado == 0) ? 0 : (( sumado < 1000000 ) ?    (sumado/1000).toFixed(1) + ' K' :   (sumado/1000000).toFixed(1) + ' M' )    
})

const CodiImporte = computed(() => {
  return 0;
})




watch(info, async (newQuestion, oldQuestion) => {

  
  importeBANCOMER.value = (info.value.actual.detalle.find(({ origen }) => origen === "BANCOMER")) ? info.value.actual.detalle.find(({ origen }) => origen === "BANCOMER") : {'origen' : 'BANCOMER', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeBANORTE.value = (info.value.actual.detalle.find(({ origen }) => origen === "BANORTE")) ? info.value.actual.detalle.find(({ origen }) => origen === "BANORTE") :  {'origen' : 'BANORTE', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeHSBC.value = (info.value.actual.detalle.find(({ origen }) => origen === "HSBC")) ? info.value.actual.detalle.find(({ origen }) => origen === "HSBC") :  {'origen' : 'HSBC', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeSANTANDER.value = (info.value.actual.detalle.find(({ origen }) => origen === "SANTANDER")) ? info.value.actual.detalle.find(({ origen }) => origen === "SANTANDER") :  {'origen' : 'SANTANDER', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeBAJIO.value = (info.value.actual.detalle.find(({ origen }) => origen === "BAJIO")) ? info.value.actual.detalle.find(({ origen }) => origen === "BAJIO") :  {'origen' : 'BAJIO', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeBANAMEX.value = (info.value.actual.detalle.find(({ origen }) => origen === "BANAMEX")) ? info.value.actual.detalle.find(({ origen }) => origen === "BANAMEX") :  {'origen' : 'BANAMEX', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeAZTECA.value = (info.value.actual.detalle.find(({ origen }) => origen === "BANCO AZTECA")) ? info.value.actual.detalle.find(({ origen }) => origen === "BANCO AZTECA") : {'origen' : 'BANCO AZTECA', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeSCOTIABANK.value = (info.value.actual.detalle.find(({ origen }) => origen === "SCOTIABANK")) ? info.value.actual.detalle.find(({ origen }) => origen === "SCOTIABANK") : {'origen' : 'SCOTIABANK', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeOXXO.value = (info.value.actual.detalle.find(({ origen }) => origen === "OXXO")) ? info.value.actual.detalle.find(({ origen }) => origen === "OXXO") : {'origen' : 'OXXO', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeKIOSKO.value = (info.value.actual.detalle.find(({ origen }) => origen === "KIOSKO")) ? info.value.actual.detalle.find(({ origen }) => origen === "KIOSKO") : {'origen' : 'KIOSKO', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeWEB.value = (info.value.actual.detalle.find(({ origen }) => origen === "PAGOS EN LINEA")) ? info.value.actual.detalle.find(({ origen }) => origen === "PAGOS EN LINEA") : {'origen' : 'PAGOS EN LINEA', 'TOTAL' : 0, 'IMPORTE' : 0};
  importeSPEI.value = (info.value.actual.detalle.find(({ origen }) => origen === "HSBC Spei")) ? info.value.actual.detalle.find(({ origen }) => origen === "HSBC Spei") : {'origen' : 'HSBC Spei', 'TOTAL' : 0, 'IMPORTE' : 0};

  
  const importeBancos = [importeBANCOMER.value.IMPORTE, importeBANORTE.value.IMPORTE, importeHSBC.value.IMPORTE, importeSANTANDER.value.IMPORTE,
    importeBAJIO.value.IMPORTE, importeBANAMEX.value.IMPORTE, importeAZTECA.value.IMPORTE, importeSCOTIABANK.value.IMPORTE];
  
  const importeAutoservicio = [importeOXXO.value.IMPORTE, importeKIOSKO.value.IMPORTE];


  let anioAnterior = info.value.anterior.ANIO
  let anioActual =info.value.actual.ANIO

  let ingresoAnterior = info.value.anterior.IMPORTE
  let ingresoActual= info.value.actual.IMPORTE


  let diasAnterior = [], totalesAnterior = [], importesAnterior = [];
  for(let key of info.value.ultimos_dias_anterior) {
    diasAnterior.push(key.fecha)
    totalesAnterior.push(key.TOTAL)
    importesAnterior.push(key.TOTAL)
  }

  let dias = [], totales = [], importes = [];
  for(let key of info.value.ultimos_dias) {
    dias.push(key.fecha)
    totales.push(key.TOTAL)
    importes.push(key.TOTAL)
  }



  let paginaWebImporte = 0, bancosImporte = 0, speiImporte = 0, autoservicioImporte = 0;
  let paginaWebTotal = 0, bancosTotal = 0, speiTotal = 0, autoservicioTotal = 0;


  //Agrupacion por método de pago
  for (let item of info.value.actual.detalle){

      if (pagina.includes(item.origen)){
        paginaWebImporte = paginaWebImporte + parseFloat(item.IMPORTE);
        paginaWebTotal = paginaWebTotal + parseFloat(item.TOTAL);
      }

      if (bancos.includes(item.origen)){
        bancosImporte = bancosImporte + parseFloat(item.IMPORTE);
        bancosTotal = bancosTotal + parseFloat(item.TOTAL);
      }

      if (spei.includes(item.origen)){
        speiImporte = speiImporte + parseFloat(item.IMPORTE);
        speiTotal = speiTotal + parseFloat(item.TOTAL);
      }

      if (autoservicio.includes(item.origen)){
        autoservicioImporte = autoservicioImporte + parseFloat(item.IMPORTE);
        autoservicioTotal = autoservicioTotal + parseFloat(item.TOTAL);
      }
  }

  comparacion.value = {
      labels: ['Bancos','Autoservicio', 'Página Web', 'SPEI', 'CODI'],
      datasets: [
          {
              label: ["Distribución de Importes por Método de Pago"],
              data: [bancosImporte,autoservicioImporte, paginaWebImporte, speiImporte , 0],
              backgroundColor: ["#c95569",'#6cb9d1','#1cbbb5','orange','orangered'],
          },
      ],
  }

  bancosTotalPagos.value = bancosTotal, autoservicioTotalPagos.value = autoservicioTotal, paginaWebTotalPagos.value = paginaWebTotal, speiTotalPagos.value = speiTotal, codiTotalPagos.value = 0;

  comparacionPagos.value = {
    labels: ['Bancos','Autoservicio', 'Página Web', 'SPEI', 'CODI'],
    datasets: [
        {
            label: ["Distribución de Números de Pagos por Método"],
            data: [bancosTotal,autoservicioTotal, paginaWebTotal, speiTotal , 0],
            backgroundColor: ["#c95569",'#6cb9d1','#1cbbb5','orange','orangered'],
        },
    ],
  }


  data.value = {
    labels: dias,
    datasets: [
      {
        label: '2024',
        backgroundColor: "#ff7878",
        data: importesAnterior
      },
      {
        label: '2025',
        backgroundColor: '#62a2f3',
        data: importes
      },
    ]
  }

    //loading.value = true
    chartComparativo.value = {
        labels: ['Ingresos Año Anterior vs Actual'],
        datasets: [
            {
                label: [anioAnterior],
                data: [ingresoAnterior],
                backgroundColor: "#ff7878",
            },
            {
                label: [anioActual],
                data: [ingresoActual],
                backgroundColor: '#62a2f3',
            },
        ],
    };


  distribucionBancos.value = {
  labels: labels,
  datasets: [{
    axis: 'y',
    label: 'Importe por Bancos',
    data: importeBancos,
    fill: false,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
       'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
};

distribucionAutoservicio.value = {
  labels: autoservicio,
  datasets: [{
    axis: 'y',
    label: 'Importe por Autoservicio',
    data: importeAutoservicio,
    fill: false,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
       'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
};

})


const opciones = {
    indexAxis: 'y',
}



function actualizar(){


  fetch('http://dashboard.local/get-stats/'+desde.value+'/'+hasta.value)
  .then(data => {
  return data.json();
  })
  .then(json => {
    info.value = json;

  });
}


onMounted (()=>{
  actualizar();
})

</script>


<style scoped>

@media (width <= 750px) {
    .leyendas p{
        font-size: 1.1em!important;
    }

    .leyendas{
      font-weight: bolder;
    }

    .leyendas p{
      margin-bottom: 2px;
    }
    p.numero{
      font-size: 2.4em!important;
    }
    .leyendas p strong{
      margin-right: 8px!important;
    }
    h1{
      font-size: 1.5em;
      text-align: center;
    }
}
h3{
  font-weight: bolder;
}
h1{
    margin-bottom: 19px;
    color: #6b6969;
    background: #f1f1f1;
    padding: 12px;
}
h5{
  font-size: 1em;
  color: #797979;
  padding-top: 5px;
}
p.numero{
  font-size: 3.2em;
  text-align: center;
  font-weight: bold;
}
p.leyenda{
  text-align: center;
  font-size: 1.2em;
  font-weight: bolder;
  color: gray;
}
.sombra{
  -webkit-box-shadow: 6px 3px 14px -3px rgba(0,0,0,0.75);
  -moz-box-shadow: 6px 3px 14px -3px rgba(0,0,0,0.75);
  box-shadow: 6px 3px 14px -3px rgba(0,0,0,0.75);
}

.sombreado{
    padding: 8px;
    -webkit-box-shadow: 6px 3px 14px -3px rgba(0,0,0,0.75);
    -moz-box-shadow: 6px 3px 14px -3px rgba(0,0,0,0.75);
    box-shadow: 6px 3px 14px -3px rgba(0,0,0,0.75);

}

.leyendas p{
  font-size: 1.5em;
}
.leyendas p strong{
  float: right;
  margin-right: 40px;
}

p.bancos{
  color: #c95569;
}
p.autoservicio{
  color:#6cb9d1;
}
p.web{
  color:#1cbbb5;
}
p.spei{
  color: orange;
}
p.codi{
  color: orangered;
}
</style>