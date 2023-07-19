<template>
  <div>
    <!-- Data Fields -->
    <div class="container mx-auto p-4">
      <label
        v-if="$page.props.auth.user"
        for="title"
        class="block mb-1 dark:text-gray-300"
        >Título</label
      >
      <input
        v-if="$page.props.auth.user"
        v-model="title"
        type="text"
        id="title"
        class="w-full border-gray-300 border rounded-md p-2 mb-1"
        :disabled="isReadOnly"
      />
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div>
          <label for="pointsNumber" class="block mb-1 dark:text-gray-300"
            >Número de Puntos</label
          >
          <input
            v-model="pointNumber"
            type="number"
            id="pointsNumber"
            class="w-full border-gray-300 border rounded-md p-2"
            step="1"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="stepSize" class="block mb-1 dark:text-gray-300"
            >Número de Pasos</label
          >
          <input
            v-model="stepSize"
            type="number"
            id="stepSize"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.01"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="a" class="block mb-1 dark:text-gray-300">Parámetro a</label>
          <input
            v-model="a"
            type="number"
            id="a"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="b" class="block mb-1 dark:text-gray-300">Parámetro b</label>
          <input
            v-model="b"
            type="number"
            id="b"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="c" class="block mb-1 dark:text-gray-300">Parámetro c</label>
          <input
            v-model="c"
            type="number"
            id="c"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="x" class="block mb-1 dark:text-gray-300">Variable X</label>
          <input
            v-model="x"
            type="number"
            id="x"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="y" class="block mb-1 dark:text-gray-300">Variable Y</label>
          <input
            v-model="y"
            type="number"
            id="y"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
            :disabled="isReadOnly"
          />
        </div>
        <div>
          <label for="z" class="block mb-1 dark:text-gray-300">Variable Z</label>
          <input
            v-model="z"
            type="number"
            id="z"
            class="w-full border-gray-300 border rounded-md p-2"
            step="0.1"
            :disabled="isReadOnly"
          />
        </div>
      </div>
    </div>
    <!-- Change view -->
    <div class="flex space-x-4 dark:text-gray-300">
      <label class="flex items-center">
        <input
          type="radio"
          class="form-radio"
          name="view"
          value="view3d"
          v-model="plotView"
        />
        <span class="ml-2">Vista 3D</span>
      </label>
      <label class="flex items-center">
        <input
          type="radio"
          class="form-radio"
          name="view"
          value="viewxy"
          v-model="plotView"
        />
        <span class="ml-2">Vista XY</span>
      </label>
      <label class="flex items-center">
        <input
          type="radio"
          class="form-radio"
          name="view"
          value="viewxz"
          v-model="plotView"
        />
        <span class="ml-2">Vista XZ</span>
      </label>
      <label class="flex items-center">
        <input
          type="radio"
          class="form-radio"
          name="view"
          value="viewyz"
          v-model="plotView"
        />
        <span class="ml-2">Vista YZ</span>
      </label>
    </div>
    <!-- Action Buttons -->
    <div class="my-10">
      <SecondaryButton @click="generatePlot"> Graficar </SecondaryButton>
      <PrimaryButton
        v-if="$page.props.auth.user && !isReadOnly"
        @click="onSavePlot"
        class="ml-4"
      >
        Guardar
      </PrimaryButton>
    </div>
    <!-- Sprott Atractor Plots -->
    <div
      v-if="!loading && plotView == 'view3d'"
      ref="plotContainer3d"
      class="mx-auto max-h-full max-w-md"
    ></div>
    <div
      v-if="!loading && plotView == 'viewxz'"
      ref="plotContainerXZ"
      class="mx-auto max-h-full max-w-md"
    ></div>
    <div
      v-if="!loading && plotView == 'viewxy'"
      ref="plotContainerXY"
      class="mx-auto max-h-full max-w-md"
    ></div>
    <div
      v-if="!loading && plotView == 'viewyz'"
      ref="plotContainerYZ"
      class="mx-auto max-h-full max-w-md"
    ></div>
    <!-- Loading label -->
    <div v-if="loading" class="mx-auto dark:text-white">Cargando...</div>
  </div>
</template>
<script>
import Plotly from "plotly.js-dist-min";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from "sweetalert2";

export default {
  components: { SecondaryButton, PrimaryButton },
  props: {
    isReadOnly: { type: Boolean, default: false },
    atractor: { type: Object, default: null },
  },
  data() {
    return {
      title: "",
      pointNumber: 10000,
      stepSize: 0.01,
      a: 5.0,
      b: 2.0,
      c: 1.6,
      x: 1.0,
      y: 1.0,
      z: 1.0,
      loading: false,
      plotView: "view3d",
    };
  },
  mounted() {
    if (this.atractor) {
      this.title = this.atractor.title;
      this.pointNumber = this.atractor.parameters.point_number;
      this.stepSize = this.atractor.parameters.step_size;
      this.a = this.atractor.parameters.a;
      this.b = this.atractor.parameters.b;
      this.c = this.atractor.parameters.c;
      this.x = this.atractor.parameters.x;
      this.y = this.atractor.parameters.y;
      this.z = this.atractor.parameters.z;
    }
    this.generatePlot();
  },
  methods: {
    generatePlot() {
      this.loading = true;
      const N = this.pointNumber;
      const dt = this.stepSize;
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
        const dx = a * (y - x);
        const dy = b * x * z;
        const dz = c - x  * y;

        x += dt * dx;
        y += dt * dy;
        z += dt * dz;

        xs.push(x);
        ys.push(y);
        zs.push(z);
      }

      if (this.plotView == "view3d") {
        const trace3d = {
          x: xs,
          y: ys,
          z: zs,
          mode: "lines",
          type: "scatter3d",
          line: { color: "rgb(255, 0, 0)", width: 1 },
        };

        const layout3d = {
          margin: { l: 0, r: 0, b: 0, t: 40 },
          scene: {
            xaxis: { title: "X" },
            yaxis: { title: "Y" },
            zaxis: { title: "Z" },
          },
          title: this.title,
        };

        const data3d = [trace3d];
        Plotly.newPlot(this.$refs.plotContainer3d, data3d, layout3d);
      }

      if (this.plotView == "viewxz") {
        const traceXZ = {
          x: xs,
          y: zs,
          mode: "lines",
          type: "scatter",
          line: { color: "rgb(255, 0, 0)", width: 1 },
        };

        const layoutXZ = {
          margin: { l: 0, r: 0, b: 0, t: 40 },
          xaxis: { title: "X" },
          yaxis: { title: "Z" },
          title: this.title,
        };

        const dataXZ = [traceXZ];
        Plotly.newPlot(this.$refs.plotContainerXZ, dataXZ, layoutXZ);
      }

      if (this.plotView == "viewxy") {
        const traceXY = {
          x: xs,
          y: ys,
          mode: "lines",
          type: "scatter",
          line: { color: "rgb(255, 0, 0)", width: 1 },
        };

        const layoutXY = {
          margin: { l: 0, r: 0, b: 0, t: 40 },
          xaxis: { title: "X" },
          yaxis: { title: "Y" },
          title: this.title,
        };

        const dataXY = [traceXY];
        Plotly.newPlot(this.$refs.plotContainerXY, dataXY, layoutXY);
      }

      if (this.plotView == "viewyz") {
        const traceYZ = {
          x: ys,
          y: zs,
          mode: "lines",
          type: "scatter",
          line: { color: "rgb(255, 0, 0)", width: 1 },
        };

        const layoutYZ = {
          margin: { l: 0, r: 0, b: 0, t: 40 },
          xaxis: { title: "Y" },
          yaxis: { title: "Z" },
          title: this.title,
        };

        const dataYZ = [traceYZ];
        Plotly.newPlot(this.$refs.plotContainerYZ, dataYZ, layoutYZ);
      }

      this.loading = false;
    },
    async onSavePlot() {
      const parameters = {
        title: this.title,
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
        type: "sprott",
      };

      const token = localStorage.getItem("access_token");

      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      if (!this.atractor) {
        await axios
          .post("/api/graphic", parameters, config)
          .then(() => {
            Swal.fire({
              title: "Éxito",
              text: "El gráfico ha sido registrado",
              icon: "success",
              showConfirmButton: false,
              timer: 1500,
            });
          })
          .catch((error) => {
            const errors = error.response.data.errors;
            const errorMessages = Object.values(errors);
            
            Swal.fire({
              title: "Error",
              html: `Se produjeron los siguientes errores:<br>- ${errorMessages.join("<br>- ")}`,
              icon: "error",
              showConfirmButton: false,
              timer: 5000,
            });
          });
      } else {
        const graphId = this.atractor.id;
        await axios
          .put(`/api/graphic/${graphId}`, parameters, config)
          .then(() => {
            Swal.fire({
              title: "Éxito",
              text: "El gráfico ha sido actualizado",
              icon: "success",
              showConfirmButton: false,
              timer: 1500,
            });
          })
          .catch((error) => {
            const errors = error.response.data.errors;
            const errorMessages = Object.values(errors);
            
            Swal.fire({
              title: "Error",
              html: `Se produjeron los siguientes errores:<br>- ${errorMessages.join("<br>- ")}`,
              icon: "error",
              showConfirmButton: false,
              timer: 5000,
            });
          });
      }
    },
  },
};
</script>
