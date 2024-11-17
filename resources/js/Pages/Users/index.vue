<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import CreateUser from './CreateUser.vue';
import EditUser from './EditUser.vue';

const users = ref([]); // Store all users
const selectedUser = ref(null); // Store selected user for editing
const isDeleteModalOpen = ref(false); // Manage delete confirmation modal visibility
const toastMessage = ref(''); // Store toast message for notifications
const createUserModal = ref(null); // Create a reference for the CreateUser modal
const editUserModal = ref(null); // Create a reference for the EditUser modal

// Fetch all users
const fetchUsers = async () => {
    try {
        const response = await axios.get('/show-users');
        users.value = response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

// Fetch and open the edit modal with the user data
const editUser = (user) => {
    // Ensure the modal reference exists before calling openModal
    if (editUserModal.value) {
        editUserModal.value.openModal(user); // Open the modal with user data
    }
};

// Update the user using POST request
const updateUser = async (updatedUser) => {
    try {
        const response = await axios.post(
            `/update-user/${updatedUser.id}`,
            updatedUser,
        );

        // Update the user in the list
        const index = users.value.findIndex((u) => u.id === updatedUser.id);
        users.value[index] = response.data;

        // Show success toast
        showToast('User updated successfully!');
        editUserModal.value.closeModal(); // Close edit modal
        fetchUsers(); // Refresh users list after update
    } catch (error) {
        console.error('Error updating user:', error);
    }
};

// Show toast message
const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = ''; // Hide the toast after 3 seconds
    }, 3000);
};

const handleCreateUser = (user) => {
    users.value.push(user); // Add the user to the list after creation
    fetchUsers(); // Refresh the users list after creation
};

// Open the delete confirmation modal
const openDeleteConfirmationModal = (user) => {
    selectedUser.value = user; // Set the user to be deleted
    isDeleteModalOpen.value = true; // Open the confirmation modal
};

// Close the delete confirmation modal
const closeDeleteConfirmationModal = () => {
    isDeleteModalOpen.value = false; // Close the modal
};

// Delete a user after confirmation
const deleteUser = async () => {
    try {
        if (selectedUser.value) {
            await axios.delete(`/delete-user/${selectedUser.value.id}`);
            // Remove the deleted user from the list
            users.value = users.value.filter(
                (user) => user.id !== selectedUser.value.id,
            );
            showToast('User deleted successfully!');
            closeDeleteConfirmationModal(); // Close the confirmation modal

            // Refresh the users list after deletion
            fetchUsers();
        }
    } catch (error) {
        console.error('Error deleting user:', error);
    }
};

// Initialize users on mount
onMounted(() => {
    fetchUsers();
});
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Users
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Button to create a new user -->
                        <div class="mb-4 flex justify-end">
                            <button
                                @click="createUserModal.openModal()"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Create User
                            </button>
                        </div>

                        <!-- Table for listing users -->
                        <div class="mt-6">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            User Name
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Role
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="px-4 py-2">
                                            {{ user.name }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ user.email }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ user.role }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <!-- Edit button -->
                                            <button
                                                @click="editUser(user)"
                                                class="rounded-md bg-blue-600 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700"
                                            >
                                                Edit
                                            </button>
                                            <!-- Delete button -->
                                            <button
                                                @click="
                                                    openDeleteConfirmationModal(
                                                        user,
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
                    Are you sure you want to delete this user?
                </h3>
                <div class="mt-4 flex justify-end space-x-2">
                    <button
                        @click="deleteUser"
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

        <!-- CreateUser Modal -->
        <CreateUser ref="createUserModal" @create-user="handleCreateUser" />

        <!-- EditUser Modal -->
        <EditUser ref="editUserModal" @edit-user="updateUser" />
    </AuthenticatedLayout>
</template>

