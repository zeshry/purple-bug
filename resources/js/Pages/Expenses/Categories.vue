<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import Create from './Create.vue';
import Edit from './Edit.vue';

const expenseCategories = ref([]); // Change from roles to expense categories
const selectedExpenseCategory = ref(null);
const isDeleteModalOpen = ref(false);
const toastMessage = ref('');
const createExpenseCategoryModal = ref(null);
const editExpenseCategoryModal = ref(null);

const fetchExpenseCategories = async () => {
    try {
        const response = await axios.get('/show-expense-categories'); // Endpoint for expense categories
        expenseCategories.value = response.data;
    } catch (error) {
        console.error('Error fetching expense categories:', error);
    }
};

const editExpenseCategory = (expenseCategory) => {
    selectedExpenseCategory.value = { ...expenseCategory };
    if (editExpenseCategoryModal.value) {
        editExpenseCategoryModal.value.openModal(selectedExpenseCategory.value);
    }
};

const updateExpenseCategory = async (updatedExpenseCategory) => {
    try {
        const response = await axios.post(
            `/update-expense-category/${updatedExpenseCategory.id}`, // Update expense category endpoint
            updatedExpenseCategory,
        );

        const index = expenseCategories.value.findIndex(
            (e) => e.id === updatedExpenseCategory.id,
        );
        expenseCategories.value[index] = response.data;

        editExpenseCategoryModal.value.closeModal();
    } catch (error) {
        console.error('Error updating expense category:', error);
    }
};

const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = '';
    }, 3000);
};

const handleCreateExpenseCategory = (expenseCategory) => {
    expenseCategories.value.push(expenseCategory);
    fetchExpenseCategories();
};

const openDeleteConfirmationModal = (expenseCategory) => {
    selectedExpenseCategory.value = expenseCategory;
    isDeleteModalOpen.value = true;
};

const closeDeleteConfirmationModal = () => {
    isDeleteModalOpen.value = false;
    selectedExpenseCategory.value = null; // Clear selection after closing
};

const deleteExpenseCategory = async () => {
    try {
        if (selectedExpenseCategory.value) {
            await axios.delete(
                `/delete-expense-category/${selectedExpenseCategory.value.id}`, // Fixed endpoint
            );
            expenseCategories.value = expenseCategories.value.filter(
                (expenseCategory) =>
                    expenseCategory.id !== selectedExpenseCategory.value.id,
            );
            showToast('Expense Category deleted successfully!');
            closeDeleteConfirmationModal();
        }
    } catch (error) {
        console.error('Error deleting expense category:', error);
    }
};

onMounted(() => {
    fetchExpenseCategories();
});
</script>

<template>
    <Head title="Expense Categories" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Expense Categories
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4 flex justify-end">
                            <button
                                @click="createExpenseCategoryModal.openModal()"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Create Expense Category
                            </button>
                        </div>

                        <div class="mt-6">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Category Name
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Description
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
                                        v-for="expenseCategory in expenseCategories"
                                        :key="expenseCategory.id"
                                    >
                                        <td class="px-4 py-2">
                                            {{ expenseCategory.expense_name }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ expenseCategory.description }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <button
                                                @click="
                                                    editExpenseCategory(
                                                        expenseCategory,
                                                    )
                                                "
                                                class="rounded-md bg-blue-600 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    openDeleteConfirmationModal(
                                                        expenseCategory,
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

        <div
            v-if="toastMessage"
            class="fixed bottom-4 right-4 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg"
        >
            {{ toastMessage }}
        </div>

        <div
            v-if="isDeleteModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
                <h3 class="text-lg font-semibold">
                    Are you sure you want to delete this expense category?
                </h3>
                <div class="mt-4 flex justify-end space-x-2">
                    <button
                        @click="deleteExpenseCategory"
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

        <Create
            ref="createExpenseCategoryModal"
            @create-expense-category="handleCreateExpenseCategory"
        />

        <Edit
            ref="editExpenseCategoryModal"
            @edit-expense-category="updateExpenseCategory"
        />
    </AuthenticatedLayout>
</template>

