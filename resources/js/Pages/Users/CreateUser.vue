<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="w-1/3 rounded-lg bg-white p-6 shadow-lg">
            <h3 class="mb-4 text-xl font-semibold">Create User</h3>
            <form @submit.prevent="submitUser">
                <div class="mb-4">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Name
                    </label>
                    <input
                        v-model="newUser.name"
                        type="text"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>
                    <input
                        v-model="newUser.email"
                        type="email"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Password
                    </label>
                    <input
                        v-model="newUser.password"
                        type="password"
                        required
                        class="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Confirm Password
                    </label>
                    <input
                        v-model="newUser.password_confirmation"
                        type="password"
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
                        v-model="newUser.role"
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

const emit = defineEmits(['create-user']);

const isOpen = ref(false);
const newUser = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
}); // Add password_confirmation
const roles = ref([]); // Store available roles
const toastMessage = ref('');

// Fetch roles from the server
const fetchRoles = async () => {
    try {
        const response = await axios.get('/show-roles'); // Fetch roles from server
        roles.value = response.data; // Store roles
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

// Open modal for user creation
const openModal = () => {
    isOpen.value = true;
    fetchRoles(); // Fetch roles when opening the modal
};

// Close modal
const closeModal = () => {
    isOpen.value = false;
    newUser.value = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
    }; // Reset user data
};

// Show toast notification
const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = ''; // Hide toast after 3 seconds
    }, 3000);
};

// Handle user creation form submission
const submitUser = async () => {
    try {
        const response = await axios.post('/add-users', newUser.value); // Updated API endpoint for creating user
        emit('create-user', response.data); // Emit event with newly created user data
        showToast('User created successfully!'); // Show success message
        closeModal(); // Close the modal
    } catch (error) {
        console.error('Error creating user:', error); // Log error if creation fails
    }
};

defineExpose({
    openModal,
    closeModal,
});
</script>

<style scoped></style>
