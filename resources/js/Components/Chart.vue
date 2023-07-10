<template>
    <div>
        <div>
            <select id="chart-type" v-model="selectedChartType"
                class="mx-auto mb-5 block w-full sm:w-11/12 text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="bar">Barras</option>
                <option value="scatter">Dispersión</option>
            </select>
        </div>
        <div ref="chart"></div>
    </div>
</template>
  
<script>
import Plotly from 'plotly.js-dist-min';

export default {
    props: {
        dates: {
            type: Array,
            required: true,
        },
        values: {
            type: Array,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            selectedChartType: 'bar',
        };
    },
    mounted() {
        this.renderChart();
    },
    watch: {
        selectedChartType() {
            this.renderChart();
        },
    },
    methods: {
        generateXLabels() {
            let startDate = '';
            let endDate = '';

            if (this.dates.length > 0) {
                startDate = this.dates[0];
                endDate = this.dates[this.dates.length - 1];
            }

            return [startDate, endDate];
        },
        renderChart() {
            const xLabels = this.generateXLabels();

            const data = [
                {
                    x: this.dates,
                    y: this.values,
                    type: this.selectedChartType,
                },
            ];

            const layout = {
                title: this.name,
                xaxis: {
                    title: 'Fechas',
                    ticktext: xLabels,
                    tickvals: xLabels,
                },
                yaxis: {
                    title: 'Cantidad',
                },
            };

            const config = {
                responsive: true,
            };

            Plotly.newPlot(this.$refs.chart, data, layout, config);
        },
    },
};
</script>
  