<template>
  <h1>Estadísticas de Impuesto Predial Urbano</h1>
  <div class="containter">
      <div class="row">
    <div class="col-md-6">
  <Bar
    id="my-chart-id"
    :options="chartOptions"
    :data="chartData"
    aria-label="Sales figures for the years 2022 to 2024. Sales in 2022: 987, Sales in 2023: 1209, Sales in 2024: 825."
  />
    </div>
       <div class="col-md-6">
  <Bar
    id="my-chart-id"
    :options="chartOptions"
    :data="chartData"
    aria-label="Sales figures for the years 2022 to 2024. Sales in 2022: 987, Sales in 2023: 1209, Sales in 2024: 825."
  />
    </div>
  </div>
  </div>


</template>
	
<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import 'bootstrap'; import 'bootstrap/dist/css/bootstrap.min.css';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
  name: 'BarChart',
  components: { Bar },
  data() {
    return {
      chartData: {
        labels: [ 'January', 'February', 'March' ],
        datasets: [ { data: [40, 20, 12] } ]
      },
      chartOptions: {
        responsive: true,
        plugins: {
        legend: {
          position: 'right',
        },
        title: {
          display: true,
          text: 'Chart.js Horizontal Bar Chart'
        }
      }
      }
    }
  }
}
</script>


<style scoped>

</style>