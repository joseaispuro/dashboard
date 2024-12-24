<template>
  <h1>Estadísticas de Impuesto Predial Urbano</h1>
  <div class="containter">
      <div class="row">
        <div class="col-md-3">
          <h3>Período</h3>
          <input type="date" class="form-control"  v-model="desde">
          <input type="date" class="form-control" v-model="hasta">
          <button @click="actualizar" class="btn btn-primary">
            Actualizar
          </button>
        </div>
        <div class="col-md-6">

            <Bar
              id="workhr-chart"
              :options="chartOptions"
              :data="chartData"
              :style="chartStyles"
            >
            </Bar>
        </div>
        <div class="col-md-6">
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
</style>