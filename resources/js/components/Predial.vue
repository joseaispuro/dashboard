<template>
  <h1>Estadísticas de Impuesto Predial Urbano</h1>
  <div class="containter-fluid">
      <div class="row">

        <div class="col-md-9">

          <div class="row">

            <div class="col-md-6 sombra">
              <h5>Período</h5>
              <input type="date" class="form-control"  v-model="desde">
              <input type="date" class="form-control" v-model="hasta">
              <div class="d-grid gap-2">
                <button class="btn btn-primary" @click="actualizar" type="button">Actualizar</button>
              </div>

            </div>

            <div class="col-md-6 sombra">
                <h5>Ingreso Digital</h5>
                <p class="numero">${{info.actual?.IMPORTE_LEYENDA}}</p>
                <p class="leyenda">{{info.actual?.TOTAL}} pagos</p>
            </div>

            <div class="col-md-6 sombra">
              <h5>Ingreso vs Año Anterior</h5>
              <Bar
                id="workhr-chart2"
                :data="chartComparativo"
                key="{{Math.random()}}"
              >
              </Bar>
            </div>

            <div class="col-md-6 sombra" style="max-height:450px;">
                <h5>Últimos 7 días</h5>
                <Line :data="data" :options="options" style="max-height:370px;" />

            </div>

            <div class="col-md-12 sombra" style="max-height:450px;">
                <h5>Distribución de Importes</h5>

                <div class="row">
                  <div class="col-md-8">
                    <Doughnut :data="comparacion" style="max-height:410px;"/>
                  </div>

                  <div class="col-md-4 leyendas mt-5">
                      <p class="bancos">Bancos <strong>$20M</strong></p>
                      <p class="autoservicio">Autoservicio <strong>$10M</strong></p>
                      <p class="web">Página Web <strong>$20M</strong></p>
                      <p class="spei">SPEI <strong>$5M</strong></p>
                      <p class="codi">CODI <strong>$4M</strong></p>
                  </div>
                </div>
                
            </div>

            <div class="col-md-12 sombra" style="max-height:450px;">
                <h5>Número de Pagos</h5>

                <div class="row">
                  <div class="col-md-8">
                    <Doughnut :data="comparacionPagos" style="max-height:410px;"/>
                  </div>

                  <div class="col-md-4 leyendas mt-5">
                        <p class="bancos">Bancos <strong>500</strong></p>
                        <p class="autoservicio">Autoservicio <strong>600</strong></p>
                        <p class="web">Página Web <strong>500</strong></p>
                        <p class="spei">SPEI <strong>200</strong></p>
                        <p class="codi">CODI <strong>124</strong></p>
                  </div>   
                </div>
                            
            </div>
           
          </div>


        </div>

        <div class="col-md-3 sombra">

          <div class="row">
             <h5>Distribución Bancos</h5>
           
             <Bar
                id="workhr-chart3"
                :data="chartData3"
                :options="opciones"
              >
              </Bar>

               {{info}}
          </div>
          
        </div>



    </div>
  </div>
</template>
	

<script setup>
import {ref, reactive, computed, watch} from 'vue'
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

const desde = ref('2025-01-07');
const hasta = ref('2025-01-07');

let info = ref({})

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement,PointElement, Colors)

const chartComparativo = ref({
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

const chartData3 = ref({
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
            data: [173300,200152, 200152, 200152 , 200152],
            backgroundColor: ["#c95569",'#6cb9d1','#1cbbb5','orange','orangered'],
        },
    ],
})

const comparacionPagos = ref({
    labels: ['Bancos','Autoservicio', 'Página Web', 'SPEI', 'CODI'],
    datasets: [
        {
            label: ["Distribución de Números de Pagos por Método"],
            data: [173300,200152, 200152, 200152 , 200152],
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
      data: [20, 39, 10, 40, 39, 20, 90]
    },
    {
      label: '2025',
      backgroundColor: '#62a2f3',
      data: [40, 49, 11, 31, 9, 80, 40]
    },
  ]
})

const options = {
  responsive: true,
  maintainAspectRatio: false,
}


watch(info, async (newQuestion, oldQuestion) => {

  console.log(info.value.actual)

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

  const pagina = ["PAGOS EN LINEA"];
  const bancos = ['BANCOMER', 'BANORTE', 'HSBC', 'SANTANDER','BAJIO','BANAMEX','BANCO AZTECA','SCOTIABANK'];
  const spei = ['HSBC Spei'];
  const autoservicio = ['KIOSKO', 'OXXO'];

  let paginaWebImporte = 0, bancosImporte = 0, speiImporte = 0, autoservicioImporte = 0;
  let paginaWebTotal = 0, bancosTotal = 0, speiTotal = 0, autoservicioTotal = 0;
  for (let item of info.value.actual.detalle){
    console.log(item.origen)
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

  console.log(paginaWebImporte)


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

     





  }
)


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


</script>


<style scoped>
h3{
  font-weight: bolder;
}
h1{
  margin-bottom: 40px;
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