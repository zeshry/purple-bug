<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-semibold">Edit Role</h3>
            <form @submit.prevent="submitRole">
                <div class="mb-4">
                    <label
                        for="role_name"
                        class="block text-sm font-medium text-gray-700"
                        >Role Name</label
                    >
                    <input
                        v-model="role.role_name"
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
                        v-model="role.description"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
                    >
                        Update
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
        class="fixed bottom-4 right-4 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg"
    >
        {{ toastMessage }}
    </div>
</template>

<script setup>
import axios from 'axios';
import { defineEmits, defineExpose, ref } from 'vue';

const emit = defineEmits(['update-role']);

const isOpen = ref(false);
const toastMessage = ref('');

const role = ref({
    id: '',
    role_name: '',
    description: '',
});

const openModal = (roleData) => {
    role.value = { ...roleData };
    isOpen.value = true;
};

const closeModal = () => {
    isOpen.value = false;
};

const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = '';
    }, 3000);
};
const submitRole = async () => {
    try {
        const response = await axios.post(
            `/update-role/${role.value.id}`,
            role.value,
        );

        emit('update-role', response.data);

        showToast('Role updated successfully!');
        closeModal();
    } catch (error) {
        console.error('Error updating role:', error);
    }
};

defineExpose({
    openModal,
    closeModal,
});
</script>

