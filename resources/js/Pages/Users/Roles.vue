<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import CreateRole from './CreateRole.vue';
import EditRole from './EditRole.vue';

const roles = ref([]);
const selectedRole = ref(null);
const isDeleteModalOpen = ref(false);
const toastMessage = ref('');
const createRoleModal = ref(null);
const editRoleModal = ref(null);

const fetchRoles = async () => {
    try {
        const response = await axios.get('/show-roles');
        roles.value = response.data;
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

const editRole = (role) => {
    selectedRole.value = { ...role };
    if (editRoleModal.value) {
        editRoleModal.value.openModal(selectedRole.value);
    }
};

const updateRole = async (updatedRole) => {
    try {
        const response = await axios.post(
            `/update-role/${updatedRole.id}`,
            updatedRole,
        );

        const index = roles.value.findIndex((r) => r.id === updatedRole.id);
        roles.value[index] = response.data;

        editRoleModal.value.closeModal();
    } catch (error) {
        console.error('Error updating role:', error);
    }
};

const showToast = (message) => {
    toastMessage.value = message;
    setTimeout(() => {
        toastMessage.value = '';
    }, 3000);
};

const handleCreateRole = (role) => {
    roles.value.push(role);
    fetchRoles();
};

const openDeleteConfirmationModal = (role) => {
    selectedRole.value = role;
    isDeleteModalOpen.value = true;
};

const closeDeleteConfirmationModal = () => {
    isDeleteModalOpen.value = false;
};

const deleteRole = async () => {
    try {
        if (selectedRole.value) {
            await axios.delete(`/delete-role/${selectedRole.value.id}`);
            roles.value = roles.value.filter(
                (role) => role.id !== selectedRole.value.id,
            );
            showToast('Role deleted successfully!');
            closeDeleteConfirmationModal();

            fetchRoles();
        }
    } catch (error) {
        console.error('Error deleting role:', error);
    }
};

onMounted(() => {
    fetchRoles();
});
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Roles
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4 flex justify-end">
                            <button
                                @click="createRoleModal.openModal()"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Create Role
                            </button>
                        </div>

                        <div class="mt-6">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="px-4 py-2 text-left text-sm font-medium text-gray-500"
                                        >
                                            Role Name
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
                                    <tr v-for="role in roles" :key="role.id">
                                        <td class="px-4 py-2">
                                            {{ role.role_name }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ role.description }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <button
                                                @click="editRole(role)"
                                                class="rounded-md bg-blue-600 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    openDeleteConfirmationModal(
                                                        role,
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
                    Are you sure you want to delete this role?
                </h3>
                <div class="mt-4 flex justify-end space-x-2">
                    <button
                        @click="deleteRole"
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

        <CreateRole ref="createRoleModal" @create-role="handleCreateRole" />

        <EditRole ref="editRoleModal" @edit-role="updateRole" />
    </AuthenticatedLayout>
</template>

