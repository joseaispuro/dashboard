import './bootstrap';
import { createApp } from 'vue';
import Predial from './components/Predial.vue';
import MenuBar from './components/Menu.vue';


let app = createApp({})
  .component('Predial', Predial)
  .component('MenuBar', MenuBar)
  .mount('#app')