<template>
  <div>
    <h2 v-bind:style="{ fontWeight: 'bold' }"> Laravel 9 con Vue 3</h2>
    <hr />
    
    <h3>{{ title }}</h3>
  </div>
</template>

<script setup>
const title = " This is a project Laravel with Vue 3 by: Thaly";
</script>

<style scoped>
div {
  text-align: center;
}
</style>
