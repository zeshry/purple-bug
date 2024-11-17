<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import ExpensePieChart from './Expenses/ExpensePieChart.vue';

// Reactive variables
const expenses = ref([]);
const expenseCategories = ref([]);

// Fetch expenses and expense categories
const fetchExpenses = async () => {
    try {
        const response = await axios.get('/show-expense');
        expenses.value = response.data;
    } catch (error) {
        console.error('Error fetching expenses:', error);
    }
};

const fetchExpenseCategories = async () => {
    try {
        const response = await axios.get('/show-expense-categories');
        expenseCategories.value = response.data;
    } catch (error) {
        console.error('Error fetching expense categories:', error);
    }
};

// Computed property to get the count of categories
const categoryCount = computed(() => {
    return expenseCategories.value.length;
});

// Computed property to group expenses by category and calculate the total
const totalExpensesByCategory = computed(() => {
    const totals = {};

    // Initialize totals object with categories using expense_name
    expenseCategories.value.forEach((category) => {
        totals[category.expense_name] = 0;
    });

    // Sum expenses per category using expense_type
    expenses.value.forEach((expense) => {
        if (expense.expense_type && expense.amount) {
            if (!totals[expense.expense_type]) {
                totals[expense.expense_type] = 0;
            }
            totals[expense.expense_type] += parseFloat(expense.amount);
        }
    });

    return totals;
});

// Computed property to count how many expenses use each category
const expenseCountByCategory = computed(() => {
    const counts = {};

    // Initialize counts object with categories using expense_name
    expenseCategories.value.forEach((category) => {
        counts[category.expense_name] = 0;
    });

    // Count expenses per category using expense_type
    expenses.value.forEach((expense) => {
        if (expense.expense_type) {
            if (!counts[expense.expense_type]) {
                counts[expense.expense_type] = 0;
            }
            counts[expense.expense_type] += 1;
        }
    });

    return counts;
});

// Fetch data on component mount
onMounted(() => {
    fetchExpenses();
    fetchExpenseCategories();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Total Expenses
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="grid grid-cols-1 gap-8 p-6 text-gray-900 lg:grid-cols-2"
                    >
                        <!-- First column (Everything except Pie Chart) -->
                        <div>
                            <div class="ml-32 mt-8">
                                <p>Total Categories: {{ categoryCount }}</p>

                                <h1>Expense Categories:</h1>
                                <ul>
                                    <li
                                        v-for="(
                                            category, index
                                        ) in expenseCategories"
                                        :key="index"
                                    >
                                        {{ category.expense_name }} -
                                        {{
                                            expenseCountByCategory[
                                                category.expense_name
                                            ] || 0
                                        }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Second column (Pie Chart) -->
                        <div class="flex items-center justify-center">
                            <div class="mt-8">
                                <h1>Expenses Distribution by Category</h1>
                                <ExpensePieChart
                                    :expenseData="totalExpensesByCategory"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

