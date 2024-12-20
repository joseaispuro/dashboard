import './bootstrap';
import { createApp } from 'vue';
import HelloWorld from './components/HelloWorld.vue';
import MenuBar from './components/Menu.vue';
 
createApp({})
  .component('HelloWorld', HelloWorld)
  .component('MenuBar', MenuBar)
  .mount('#app')