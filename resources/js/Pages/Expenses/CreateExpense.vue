<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-semibold">Create Expense</h3>
            <form @submit.prevent="submitExpense">
                <div class="mb-4">
                    <label
                        for="expense_type"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Expense Type
                    </label>
                    <select
                        v-model="newExpense.expense_type"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    >
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.expense_name"
                        >
                            {{ category.expense_name }}
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label
                        for="amount"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Amount
                    </label>
                    <input
                        v-model="newExpense.amount"
                        required
                        type="number"
                        step="0.01"
                        min="0"
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label
                        for="entry_date"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Entry Date
                    </label>
                    <input
                        v-model="newExpense.entry_date"
                        type="date"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
                    >
                        Create
                    </button>
                    <button
                        type="button"
                        @click="closeModal"
                        class="ml-2 inline-flex justify-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div
        v-if="toastMessage"
        class="fixed bottom-4 right-4 transform rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg"
    >
        {{ toastMessage }}
    </div>
</template>

<script setup>
import axios from 'axios';
import { defineEmits, defineExpose, ref } from 'vue';

const emit = defineEmits(['create-expense']);

const isOpen = ref(false);
const newExpense = ref({
    expense_type: '',
    amount: '',
    entry_date: '',
});
const categories = ref([]); // Store available categories
const toastMessage = ref('');

// Fetch categories from the server
const fetchCategories = async () => {
    try {
        const response = await axios.get('/show-expense-categories'); // Fetch categories from server
        categories.value = response.data; // Store categories
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

// Open modal for expense creation
const openModal = () => {
    isOpen.value = true;
    fetchCategories(); // Fetch categories when opening the modal
};

// Close modal
const closeModal = () => {
    isOpen.value = false;
    newExpense.value = {
        expense_type: '',
        amount: '',
        entry_date: '',
    }; // Reset expense data
};

// Show toast notification
const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = ''; // Hide toast after 3 seconds
    }, 3000);
};

// Handle expense creation form submission
const submitExpense = async () => {
    try {
        const response = await axios.post('/add-expense', newExpense.value); // Updated API endpoint for creating expense
        emit('create-expense', response.data); // Emit event with newly created expense data
        showToast('Expense created successfully!'); // Show success message
        closeModal(); // Close the modal
    } catch (error) {
        console.error('Error creating expense:', error); // Log error if creation fails
    }
};

defineExpose({
    openModal,
    closeModal,
});
</script>

<style scoped></style>
