<template>
  <h1>Estadísticas de Impuesto Predial Urbano</h1>
  <div class="containter">
      <div class="row">

        <div class="col-md-9">

          <div class="row">

            <div class="col-md-6 sombra">
              <h5>Período</h5>
              <!--
              <input type="date" class="form-control"  v-model="desde">
              <input type="date" class="form-control" v-model="hasta">
              <div class="d-grid gap-2">
                <button class="btn btn-primary" @click="actualizar" type="button">Actualizar</button>
              </div>-->
            </div>

            <div class="col-md-6 sombra">
                <h5>Ingreso Digital</h5>
                <p class="numero">$59.01 M</p>
                <p class="leyenda">1,824 pagos</p>
            </div>
           
          </div>
        </div>

        <div class="col-md-3">

          <div class="row">
             <h5>Distribución Bancos</h5>
           
             <Bar
                id="workhr-chart"
                :options="chartOptions"
                :data="chartData"
                :style="chartStyles"
              >
              </Bar>
          </div>
           {{info}}
        </div>

        

        

    </div>
  </div>
</template>
	

<script setup lang="ts">
import {ref, computed} from 'vue'
import { Bar } from "vue-chartjs"
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js"

const desde = ref('2024-01-01');
const hasta = ref('2024-01-31');

let info = ref({})

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps<{ userHours: {} }>()

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
</style>