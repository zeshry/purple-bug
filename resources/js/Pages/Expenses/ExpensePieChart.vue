<!-- ExpensePieChart.vue -->
<template>
    <div>
        <Pie :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import {
    ArcElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    Title,
    Tooltip,
} from 'chart.js';
import { computed } from 'vue';
import { Pie } from 'vue-chartjs';

// Register chart components
ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

const props = defineProps({
    expenseData: {
        type: Object,
        required: true,
    },
});

// Generate chart data from props
const chartData = computed(() => {
    return {
        labels: Object.keys(props.expenseData),
        datasets: [
            {
                label: 'Expenses per Category',
                data: Object.values(props.expenseData),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#FF6384',
                ],
                hoverBackgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#FF6384',
                ],
            },
        ],
    };
});

// Chart options (you can customize this)
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
    },
};
</script>

<style scoped>
/* Adjust the chart size as needed */
div {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    z-index: 1;
}
</style>
