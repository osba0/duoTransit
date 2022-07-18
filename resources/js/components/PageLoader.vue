<template>
  <div class="page-loader" v-if="isLoading">
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
  </div> 
</template>

<script>

  export default {
    props: {
    isLoading: Boolean 
    },
    data: () => {
      return {}
    },
    methods: {},
    mounted() {
      document.onreadystatechange = () => { 
        if (document.readyState == "complete") { 
         
        } 
      }
    },
  }
</script>

<style lang="scss" scoped>
   $colors: #3A75A7, #69BEEB, #F5AA39, #CA0A05;
  // -----------------------------------------------------
  .page-loader {
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100% /*100vw*/;
    height: 100vh;
    background-color: #f4f6f9; /*#333*/
    z-index: 105;
  }
  // -----------------------------------------------------
  .cube{
    width: 40px;
    height: 40px;
    margin-right: 10px;
    @for $i from 1 through length($colors) {
      &:nth-child(#{$i}) {
        background-color: nth($colors, $i);
      }
    }
    &:first-child {
      animation: left 1s infinite;
    }
    &:last-child {
      animation: right 1s infinite .5s;
    }
  }
  // -----------------------------------------------------
  @keyframes left {
    40% {
      transform: translateX(-60px);
    }
    50% {
      transform: translateX(0);      
    }
  }
  @keyframes right {
    40% {
      transform: translateX(60px);
    }
    50% {
      transform: translateX(0);
    }
  }
</style>