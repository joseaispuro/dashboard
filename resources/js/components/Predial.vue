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
                :data="chartData2"
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
                    <Doughnut :data="comparacion" style="max-height:410px;"/>
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
                id="workhr-chart2"
                :data="chartData2"
                :options="opciones"
              >
              </Bar>

               {{info}}
          </div>
          
        </div>



    </div>
  </div>
</template>
	

<script setup lang="ts">
import {ref, computed} from 'vue'
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
} from "chart.js"

const desde = ref('2025-01-02');
const hasta = ref('2025-01-02');

let info = ref({})

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement,PointElement)
//const props = defineProps<{ userHours: {} }>()

const chartData = ref({
    labels: Object.keys([1,23,54,3]),
    datasets: [
        {
            label: "Worker Hours per Employee",
            data: [...Object.values([1,2,3,4])] as number[],
            maxBarThickness: 32,
            backgroundColor: "#ffb53c",
        },
    ],
})

const chartData2 = ref({
    labels: ['Ingresos Año Anterior vs Actual'],
    datasets: [
        {
            label: ["2024"],
            data: [12000] as number[],
            backgroundColor: "#ff7878",
        },
        {
            label: ["2025"],
            data: [15000] as number[],
            backgroundColor: '#62a2f3',
        },
    ],
})

const comparacion = ref({
    labels: ['Bancos','Autoservicio', 'Página Web', 'SPEI', 'CODI'],
    datasets: [
        {
            label: ["Distribución de Importes por Método de Pago"],
            data: [...Object.values([173300,200152, 200152, 200152 , 200152])] as number[],
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

const data = {
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
}

const options = {
  responsive: true,
  maintainAspectRatio: false
}


const opciones = {
    indexAxis: 'y',
}


function actualizar(){
  console.log(desde)
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