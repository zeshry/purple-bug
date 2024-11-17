<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-semibold">Create Role</h3>
            <form @submit.prevent="submitRole">
                <div class="mb-4">
                    <label
                        for="role_name"
                        class="block text-sm font-medium text-gray-700"
                        >Role Name</label
                    >
                    <input
                        v-model="newRole.role_name"
                        type="text"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700"
                        >Description</label
                    >
                    <textarea
                        v-model="newRole.description"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    ></textarea>
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

const emit = defineEmits(['create-role']);

const isOpen = ref(false);
const newRole = ref({ role_name: '', description: '' });
const toastMessage = ref('');

const openModal = () => {
    isOpen.value = true;
};

const closeModal = () => {
    isOpen.value = false;
    newRole.value = { role_name: '', description: '' };
};

const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = '';
    }, 3000);
};

const submitRole = async () => {
    try {
        const response = await axios.post('/add-roles', newRole.value);
        emit('create-role', response.data);
        showToast('Role created successfully!');
        closeModal();
    } catch (error) {
        console.error('Error creating role:', error);
    }
};

defineExpose({
    openModal,
    closeModal,
});
</script>

<style scoped></style>

