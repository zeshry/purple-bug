<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import CreateExpense from './CreateExpense.vue';
import EditExpense from './EditExpense.vue';

const expenses = ref([]); // Store all expenses
const selectedExpense = ref(null); // Store selected expense for editing
const isDeleteModalOpen = ref(false); // Manage delete confirmation modal visibility
const toastMessage = ref(''); // Store toast message for notifications
const createExpenseModal = ref(null); // Create a reference for the CreateExpense modal
const editExpenseModal = ref(null); // Create a reference for the EditExpense modal

// Fetch all expenses
const fetchExpenses = async () => {
    try {
        const response = await axios.get('/show-expense');
        expenses.value = response.data;
    } catch (error) {
        console.error('Error fetching expenses:', error);
    }
};

// Fetch and open the edit modal with the expense data
const editExpense = (expense) => {
    // Ensure the modal reference exists before calling openModal
    if (editExpenseModal.value) {
        editExpenseModal.value.openModal(expense); // Open the modal with expense data
    }
};

// Update the expense using POST request
const updateExpense = async (updatedExpense) => {
    try {
        const response = await axios.post(
            `/update-expense/${updatedExpense.id}`,
            updatedExpense,
        );

        // Update the expense in the list
        const index = expenses.value.findIndex(
            (e) => e.id === updatedExpense.id,
        );
        expenses.value[index] = response.data;

        // Show success toast
        showToast('Expense updated successfully!');
        editExpenseModal.value.closeModal(); // Close edit modal
        fetchExpenses(); // Refresh expenses list after update
    } catch (error) {
        console.error('Error updating expense:', error);
    }
};

// Show toast message
const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = ''; // Hide the toast after 3 seconds
    }, 3000);
};

const handleCreateExpense = (expense) => {
    expenses.value.push(expense); // Add the expense to the list after creation
    fetchExpenses(); // Refresh the expenses list after creation
};

// Open the delete confirmation modal
const openDeleteConfirmationModal = (expense) => {
    selectedExpense.value = expense; // Set the expense to be deleted
    isDeleteModalOpen.value = true; // Open the confirmation modal
};

// Close the delete confirmation modal
const closeDeleteConfirmationModal = () => {
    isDeleteModalOpen.value = false; // Close the modal
};

// Delete an expense after confirmation
const deleteExpense = async () => {
    try {
        if (selectedExpense.value) {
            await axios.delete(`/delete-expense/${selectedExpense.value.id}`);
            // Remove the deleted expense from the list
            expenses.value = expenses.value.filter(
                (expense) => expense.id !== selectedExpense.value.id,
            );
            showToast('Expense deleted successfully!');
            closeDeleteConfirmationModal(); // Close the confirmation modal

            // Refresh the expenses list after deletion
            fetchExpenses();
        }
    } catch (error) {
        console.error('Error deleting expense:', error);
    }
};

// Initialize expenses on mount
onMounted(() => {
    fetchExpenses();
});
</script>

<template>
    <Head title="Expenses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Expenses
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Button to create a new expense -->
                        <div class="mb-4 flex justify-end">
                            <button
                                @click="createExpenseModal.openModal()"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Create Expense
                            </button>
                        </div>

                        <!-- Table for listing expenses -->
                        <div class="mt-6">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Expense Category
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Amount
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Entry Date
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="expense in expenses"
                                        :key="expense.id"
                                    >
                                        <td class="px-4 py-2">
                                            {{ expense.expense_type }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ expense.amount }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ expense.entry_date }}
                                        </td>

                                        <td class="px-4 py-2">
                                            <button
                                                @click="editExpense(expense)"
                                                class="rounded-md bg-blue-600 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    openDeleteConfirmationModal(
                                                        expense,
                                                    )
                                                "
                                                class="ml-2 rounded-md bg-red-600 px-2 py-1 text-sm font-medium text-white hover:bg-red-700"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Toast -->
        <div
            v-if="toastMessage"
            class="fixed bottom-4 right-4 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg"
        >
            {{ toastMessage }}
        </div>

        <!-- Confirmation Modal -->
        <div
            v-if="isDeleteModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
                <h3 class="text-lg font-semibold">
                    Are you sure you want to delete this expense?
                </h3>
                <div class="mt-4 flex justify-end space-x-2">
                    <button
                        @click="deleteExpense"
                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"
                    >
                        Confirm
                    </button>
                    <button
                        @click="closeDeleteConfirmationModal"
                        class="inline-flex justify-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- CreateExpense Modal -->
        <CreateExpense
            ref="createExpenseModal"
            @create-expense="handleCreateExpense"
        />

        <!-- EditExpense Modal -->
        <EditExpense ref="editExpenseModal" @edit-expense="updateExpense" />
    </AuthenticatedLayout>
</template>

