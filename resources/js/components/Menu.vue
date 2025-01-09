<template>
<div class="wrapper">

  <div class="material-design-hamburger">
    <button class="material-design-hamburger__icon">
      <span class="material-design-hamburger__layer"></span>
    </button>
  </div>

  <div class="menu menu--off">
    <div>Dashboard </div>
    <div>Predial Urbano</div>
  </div>
  
</div>
</template>
<script setup>
import 'bootstrap'; import 'bootstrap/dist/css/bootstrap.min.css';

document.addEventListener('DOMContentLoaded', () => {
     document.querySelector('.material-design-hamburger__icon').addEventListener(
    'click',
    function() {      
      var child;
      
      document.body.classList.toggle('background--blur');
      this.parentNode.nextElementSibling.classList.toggle('menu--on');

      child = this.childNodes[1].classList;

      if (child.contains('material-design-hamburger__icon--to-arrow')) {
        child.remove('material-design-hamburger__icon--to-arrow');
        child.add('material-design-hamburger__icon--from-arrow');
      } else {
        child.remove('material-design-hamburger__icon--from-arrow');
        child.add('material-design-hamburger__icon--to-arrow');
      }

    });
})


</script>

<style scoped>

body {
  background: rgba(57,117,178,1);
}

.wrapper {
  color:black;
  height: 100%;
  transition: all 300ms ease-in-out;  
  margin: 1em 0;
  padding: 0;
}

.background--blur {
  background: rgb(34, 54, 73);
}

.menu {
  font-size:1em;
  font-family: 'Roboto', sans-serif;
  color: #333;
}

.menu div {
  margin: 1em;
  padding-bottom: 1em;
  border-bottom: 1px solid #ccc;
}

.menu div:last-child {
  border: 0;
}

.menu--off {
  position: absolute;
  left: -250px;
  width: 250px;
  display: block;
  background: #eee;
  /*height: 200px;*/
  margin-top: 1em;
  transition: all 300ms;
}

.menu--on {
  left: 0;
  box-shadow: 8px 8px 20px 1200px rgba(0, 0, 0, 0.3);
  transition: all 300ms;
}

.material-design-hamburger button {
  display: block;
  border: none;
  background: none;
  outline: 0;
}

.material-design-hamburger__icon {
  padding: 2rem 1rem;
  cursor: pointer;
}

.material-design-hamburger__layer {
  display: block;
  width: 40px;
  height: 8px;
  background: #eee;
  position: relative;
  animation-duration: 300ms;
  animation-timing-function: ease-in-out;
}

.material-design-hamburger__layer:before, .material-design-hamburger__layer:after {
  display: block;
  width: inherit;
  height: 8px;
  width:100px;
  position: absolute;
  background: inherit;
  left: ;
  content: '';
  animation-duration: 300ms;
  animation-timing-function: ease-in-out;
}

.material-design-hamburger__layer:before {
  bottom: 20px;
  width:40px;
}

.material-design-hamburger__layer:after {
  top: 20px;
  width:40px;
}

.material-design-hamburger__icon--to-arrow {
  animation-name: material-design-hamburger__icon--slide;
  animation-fill-mode: forwards;
}

.material-design-hamburger__icon--to-arrow:before {
  animation-name: material-design-hamburger__icon--slide-before;
  animation-fill-mode: forwards;
}

.material-design-hamburger__icon--to-arrow:after {
  animation-name: material-design-hamburger__icon--slide-after;
  animation-fill-mode: forwards;
}

.material-design-hamburger__icon--from-arrow {
  animation-name: material-design-hamburger__icon--slide-from;
}

.material-design-hamburger__icon--from-arrow:before {
  animation-name: material-design-hamburger__icon--slide-before-from;
}

.material-design-hamburger__icon--from-arrow:after {
  animation-name: material-design-hamburger__icon--slide-after-from;
}

@keyframes material-design-hamburger__icon--slide {
  0% {
  }
  100% {
    transform: rotate(180deg);
  }
}

@keyframes material-design-hamburger__icon--slide-before {
  0% {
  }
  100% {
    transform: rotate(45deg);
    margin: 5% 40% -25% ;
    width: 60%;
  }
}

@keyframes material-design-hamburger__icon--slide-after {
  0% {
  }
  100% {
    transform: rotate(-45deg);
    margin: -25% 40% 60% ;
    width: 60%;
  }
}

@keyframes material-design-hamburger__icon--slide-from {
  0% {
    transform: rotate(-180deg);
  }
  100% {
  }
}

@keyframes material-design-hamburger__icon--slide-before-from {
  0% {
    transform: rotate(45deg);
    margin: 5% 40% -25% ;
    width: 60%;
  }
  100% {
  }
}

@keyframes material-design-hamburger__icon--slide-after-from {
  0% {
    transform: rotate(-45deg);
    margin: 5% 40% -25% ;
    width: 60%;
  }
  100% {
  }
}


</style>


