<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-semibold">Edit User</h3>
            <form @submit.prevent="submitUser">
                <div class="mb-4">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                        >Name</label
                    >
                    <input
                        v-model="user.name"
                        type="text"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                        >Email</label
                    >
                    <input
                        v-model="user.email"
                        type="email"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label
                        for="role"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Role
                    </label>
                    <select
                        v-model="user.role"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    >
                        <option
                            v-for="role in roles"
                            :key="role.role_name"
                            :value="role.role_name"
                        >
                            {{ role.role_name }}
                        </option>
                    </select>
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

const emit = defineEmits(['update-user']);

const isOpen = ref(false);
const toastMessage = ref('');

// User data model for editing
const user = ref({
    id: '',
    name: '',
    email: '',
    password: '', // Assuming you want to allow updating password as well
    role_id: '', // Store the selected role ID
});

const roles = ref([]); // Store available roles

// Open modal with user data
const openModal = (userData) => {
    user.value = { ...userData };
    fetchRoles(); // Fetch roles when the modal is opened
    isOpen.value = true;
};

// Close the modal
const closeModal = () => {
    isOpen.value = false;
};

// Show toast message
const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = ''; // Hide the toast after 3 seconds
    }, 3000);
};

// Fetch roles from the server
const fetchRoles = async () => {
    try {
        const response = await axios.get('/show-roles'); // Fetch roles from server
        roles.value = response.data; // Store roles
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

// Submit the updated user data
const submitUser = async () => {
    try {
        const response = await axios.post(
            `/update-user/${user.value.id}`, // Updated API endpoint
            user.value, // Include the user data with the selected role
        );

        emit('update-user', response.data); // Emit the updated user data

        showToast('User updated successfully!'); // Show success toast
        closeModal(); // Close the modal
    } catch (error) {
        console.error('Error updating user:', error); // Log any error
    }
};

defineExpose({
    openModal,
    closeModal,
});
</script>

<style scoped></style>
