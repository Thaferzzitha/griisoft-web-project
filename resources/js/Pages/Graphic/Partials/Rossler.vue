<template>
  <div>
    <div class="container mx-auto p-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div>
          <label for="pointsNumber" class="block mb-1">Número de Puntos</label>
          <input
            v-model="pointNumber"
            type="number"
            id="pointsNumber"
            class="w-full border-gray-300 border rounded-md p-2"
            step="1"
          />
        </div>
        <div>
          <label for="stepSize" class="block mb-1">Número de Pasos</label>
          <input
            v-model="stepSize"
            type="number"
            id="stepSize"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.01"
          />
        </div>
        <div>
          <label for="a" class="block mb-1">Variable a</label>
          <input
            v-model="a"
            type="number"
            id="a"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
          />
        </div>
        <div>
          <label for="b" class="block mb-1">Variable b</label>
          <input
            v-model="b"
            type="number"
            id="b"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
          />
        </div>
        <div>
          <label for="c" class="block mb-1">Variable c</label>
          <input
            v-model="c"
            type="number"
            id="c"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
          />
        </div>
        <div>
          <label for="x" class="block mb-1">Variable X</label>
          <input
            v-model="x"
            type="number"
            id="x"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
          />
        </div>
        <div>
          <label for="y" class="block mb-1">Variable Y</label>
          <input
            v-model="y"
            type="number"
            id="y"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
          />
        </div>
        <div>
          <label for="z" class="block mb-1">Variable Z</label>
          <input
            v-model="z"
            type="number"
            id="z"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
          />
        </div>
      </div>
    </div>
    <div class="mb-10">
      <SecondaryButton @click="generatePlot"> Graficar </SecondaryButton>
      <PrimaryButton v-if="$page.props.auth.user" @click="onSavePlot" class="ml-4"> Guardar </PrimaryButton>
    </div>
    <div v-if="!loading" ref="plotContainer"></div>
    <div v-else>Cargando...</div>
  </div>
</template>
<script>
import Plotly from "plotly.js-dist-min";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from 'sweetalert2'

export default {
  components: { SecondaryButton, PrimaryButton },
  data() {
    return {
      pointNumber: 10000,
      stepSize: 0.01,
      a: 0.2,
      b: 0.2,
      c: 5.7,
      x: 1.0,
      y: 1.0,
      z: 1.0,
      loading: false,
    };
  },
  mounted() {
    this.generatePlot();
  },
  methods: {
    generatePlot() {
      this.loading = true;
      const N = this.pointNumber; //Points Number
      const dt = this.stepSize; //Step size
      const a = this.a;
      const b = this.b;
      const c = this.c;

      let x = this.x;
      let y = this.y;
      let z = this.z;

      const xs = [];
      const ys = [];
      const zs = [];

      for (let i = 0; i < N; i++) {
        const dx = -y - z;
        const dy = x + a * y;
        const dz = b + z * (x - c);

        x += dt * dx;
        y += dt * dy;
        z += dt * dz;

        xs.push(x);
        ys.push(y);
        zs.push(z);
      }

      const trace = {
        x: xs,
        y: ys,
        z: zs,
        mode: "lines",
        type: "scatter3d",
        line: { color: "rgb(255, 0, 0)", width: 2 },
      };

      const layout = {
        margin: { l: 0, r: 0, b: 0, t: 0 },
        scene: {
          xaxis: { title: "X" },
          yaxis: { title: "Y" },
          zaxis: { title: "Z" },
        },
      };

      const data = [trace];

      Plotly.newPlot(this.$refs.plotContainer, data, layout);

      this.loading = false;
    },
    async onSavePlot() {
      const parameters = {
        parameters: {
          point_number: this.pointNumber,
          step_size: this.stepSize,
          a: this.a,
          b: this.b,
          c: this.c,
          x: this.x,
          y: this.y,
          z: this.z,
        },
        type: 4
      };

      const token = localStorage.getItem("access_token");
      
      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      await axios.post("/api/graphic", parameters, config)
        .then(() => {
          Swal.fire({
            title: 'Éxito',
            text: 'El gráfico ha sido registrado',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          })
        })
        .catch((error) => {
          Swal.fire({
            title: 'Error',
            text: 'Se produjo un error en la operación',
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
          })
        });
    },
  },
};
</script>
