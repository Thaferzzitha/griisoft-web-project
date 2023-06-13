<template>
  <div>
    <div ref="plotContainer"></div>
  </div>
</template>
<script>
import Plotly from 'plotly.js-dist-min';
export default {
  mounted() {
    this.generatePlot();
  },
  methods: {
    generatePlot() {
      const N = 10000
      const dt = 0.01
      const a = 0.2
      const b = 0.2
      const c = 5.7

      let x = 0.1
      let y = 0.1
      let z = 0.1

      const xs = []
      const ys = []
      const zs = []

      for (let i = 0; i < N; i++) {
        const dx = -y - z
        const dy = x + a * y
        const dz = b + z * (x - c)

        x += dt * dx
        y += dt * dy
        z += dt * dz

        xs.push(x)
        ys.push(y)
        zs.push(z)
      }

      const trace = {
        x: xs,
        y: ys,
        z: zs,
        mode: 'lines',
        type: 'scatter3d',
        line: { color: 'rgb(255, 0, 0)', width: 2 }
      }

      const layout = {
        margin: { l: 0, r: 0, b: 0, t: 0 },
        scene: {
          xaxis: { title: 'X' },
          yaxis: { title: 'Y' },
          zaxis: { title: 'Z' }
        }
      }

      const data = [trace]

      Plotly.newPlot(this.$refs.plotContainer, data, layout)
    },
  },
};
</script>
