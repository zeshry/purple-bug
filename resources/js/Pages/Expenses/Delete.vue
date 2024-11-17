<!-- ConfirmDeleteModal.vue -->
<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-semibold">Confirm Deletion</h3>
            <p class="mb-4">Are you sure you want to delete this category?</p>
            <div class="flex justify-end">
                <button
                    @click="confirmDelete"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"
                >
                    Confirm
                </button>
                <button
                    @click="cancelDelete"
                    class="ml-2 inline-flex justify-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineExpose, ref } from 'vue';

const isOpen = ref(false); // Modal open state
const categoryToDelete = ref(null); // Category to delete

// Open the modal and set the category to delete
const openModal = (category) => {
    isOpen.value = true;
    categoryToDelete.value = category;
};

// Close the modal
const closeModal = () => {
    isOpen.value = false;
    categoryToDelete.value = null;
};

// Emit event for confirming the delete
const confirmDelete = () => {
    if (categoryToDelete.value) {
        // Emit the category ID to the parent
        $emit('confirm-delete', categoryToDelete.value.id);
    }
    closeModal();
};

// Cancel delete
const cancelDelete = () => {
    closeModal();
};

// Expose methods to the parent component
defineExpose({
    openModal,
    closeModal,
});
</script>
