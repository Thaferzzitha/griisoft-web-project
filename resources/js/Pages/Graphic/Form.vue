<script lang="ts">
import Rossler from "./Partials/Rossler.vue";
import InputLabel from '@/Components/InputLabel.vue';
export default {
  components: { Rossler, InputLabel },
  data() {
    return {
      atractor: "",
    };
  },
};
</script>

<template>
  <div class="mb-10">
    <select
      v-model="atractor"
      name="atractor"
      id="atractor"
      class="block w-11/12 mx-auto my-10 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
    >
      <option value="" disabled selected hidden>Seleccione el atractor...</option>
      <option value="rossler">Atractor de Rossler</option>
      <option value="lorenz">Atractor de Lorenz</option>
      <option value="chen">Atractor de Chen</option>
      <option value="sprott">Atractor de Sprott</option>
    </select>

    <div
      class="p-5 card-height w-11/12 mx-auto bg-white shadow-md rounded-lg overflow-hidden"
    >
      <Rossler v-if="atractor == 'rossler'"></Rossler>
      <!-- <Lorenz v-if="atractor == 'lorenz'">
        </Lorenz>
        <Chen v-if="atractor == 'chen'">
        </Chen>
        <Sprott v-if="atractor == 'sprott'">
        </Sprott> -->   
        
    </div>
  </div>
</template>

<style>
.card-height {
  min-height: 75vh;
}
</style>
