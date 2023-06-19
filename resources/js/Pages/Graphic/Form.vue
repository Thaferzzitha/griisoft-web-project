<script lang="ts">
import Rossler from "./Partials/Rossler.vue";
import  Lorenz from "./Partials/Lorenz.vue";
import InputLabel from '@/Components/InputLabel.vue';
export default {
  components: { Rossler, Lorenz, InputLabel },
  props: {
    atractor: {type: Object, default: null}
  },
  data() {
    return {
      selectedAtractor: "",
    };
  },
  mounted() {
      if (this.atractor) {
        this.selectedAtractor = this.atractor.type;
      }
  },
};
</script>

<template>
  <div class="mb-10">
    <select
      v-model="selectedAtractor"
      name="atractor"
      id="atractor"
      class="block w-11/12 mx-auto my-10 text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
      :disabled="atractor"
    >
      <option value="" disabled selected hidden>Seleccione el atractor...</option>
      <option value="rossler">Atractor de Rossler</option>
      <option value="lorenz">Atractor de Lorenz</option>
      <option value="chen">Atractor de Chen</option>
      <option value="sprott">Atractor de Sprott</option>
    </select>

    <div
      class="p-5 card-height w-11/12 mx-auto bg-white dark:bg-gray-500 shadow-md rounded-lg overflow-hidden"
    >
      <Rossler v-if="selectedAtractor == 'rossler'" :atractor="atractor"></Rossler>
      <Lorenz v-if="selectedAtractor == 'lorenz'" :atractor="atractor"></Lorenz>
       <!-- <Chen v-if="atractor == 'chen'">
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
