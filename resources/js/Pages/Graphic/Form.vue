<script lang="ts">
import Rossler from "./Partials/Rossler.vue";
export default {
  components: { Rossler },
  data() {
    return {
      atractor: "",
    };
  },
};
</script>

<template>
  <div>
    <select v-model="atractor" name="atractor" id="atractor">
      <option value="rossler">Rossler</option>
      <option value="lorenz">Lorenz</option>
      <option value="chen">Chen</option>
      <option value="sprott">Sprott</option>
    </select>
    <div v-if="atractor =='rossler'">
        <Rossler/>
    </div>
    <div v-if="atractor =='lorenz'">
        Lorenz
    </div>
    <div v-if="atractor =='chen'">
        Chen
    </div>
    <div v-if="atractor =='sprott'">
        Sprott
    </div>
  </div>
</template>

<style></style>
