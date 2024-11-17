<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// State to toggle the Expense and User dropdowns independently
const isExpenseDropdownOpen = ref(false);
const isUserDropdownOpen = ref(false);

// Method to toggle Expense dropdown visibility
const toggleExpenseDropdown = () => {
    isExpenseDropdownOpen.value = !isExpenseDropdownOpen.value;
};

// Method to toggle User dropdown visibility
const toggleUserDropdown = () => {
    isUserDropdownOpen.value = !isUserDropdownOpen.value;
};

const { props } = usePage(); // Use the usePage hook to get the page props
const isAdmin = props.auth.user.role === 'Admin';
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 border-r border-gray-200 bg-white">
            <div class="flex justify-center py-4">
                <!-- Logo -->
                <Link :href="route('dashboard')">
                    <ApplicationLogo
                        class="h-9 w-auto fill-current text-gray-800"
                    />
                </Link>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-2 px-4 py-6">
                <!-- Dashboard Link (Non-dropdown) -->
                <NavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                    class="block px-4 py-2 text-gray-500 hover:bg-gray-100"
                >
                    Dashboard
                </NavLink>

                <!-- User Management Dropdown -->
                <div v-if="isAdmin">
                    <button
                        @click="toggleUserDropdown"
                        class="flex w-full items-center px-4 py-2 text-left text-gray-500 hover:bg-gray-100"
                    >
                        User Management
                        <svg
                            class="ml-2 inline-block h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 20 20"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            />
                        </svg>
                    </button>

                    <!-- User Dropdown Menu (Visible when toggled) -->
                    <div v-if="isUserDropdownOpen" class="space-y-1 pl-4">
                        <div class="flex flex-col space-y-2">
                            <NavLink
                                :href="route('roles')"
                                :active="route().current('roles')"
                                class="block px-4 py-2 text-gray-500 hover:bg-gray-100"
                            >
                                Roles
                            </NavLink>
                            <NavLink
                                :href="route('users')"
                                :active="route().current('users')"
                                class="block px-4 py-2 text-gray-500 hover:bg-gray-100"
                            >
                                Users
                            </NavLink>
                        </div>
                    </div>
                </div>

                <!-- Expense Management Dropdown -->
                <div class="mt-4">
                    <button
                        @click="toggleExpenseDropdown"
                        class="flex w-full items-center px-4 py-2 text-left text-gray-500 hover:bg-gray-100"
                    >
                        Expense Management
                        <svg
                            class="ml-2 inline-block h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 20 20"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            />
                        </svg>
                    </button>

                    <!-- Expense Dropdown Menu (Visible when toggled) -->
                    <div v-if="isExpenseDropdownOpen" class="space-y-1 pl-4">
                        <div v-if="isAdmin">
                            <NavLink
                                :href="route('expense-category')"
                                :active="route().current('expense-category')"
                                class="block px-4 py-2 text-gray-500 hover:bg-gray-100"
                            >
                                Expense Categories
                            </NavLink>
                        </div>
                        <NavLink
                            :href="route('expense')"
                            :active="route().current('expense')"
                            class="block px-4 py-2 text-gray-500 hover:bg-gray-100"
                        >
                            Expenses
                        </NavLink>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header with User Dropdown in top-right -->
            <div
                class="flex items-center justify-between bg-white px-4 py-6 shadow"
            >
                <div class="flex items-center">
                    <!-- Render the header slot content here -->
                    <slot name="header" />
                </div>

                <!-- User Dropdown on the top-right corner -->
                <div class="relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="flex items-center space-x-2 text-gray-500 hover:text-gray-700"
                            >
                                <span>{{ $page.props.auth.user.name }}</span>
                                <svg
                                    class="ml-2 inline-block h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 20 20"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')"
                                >Profile</DropdownLink
                            >
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                >Log Out</DropdownLink
                            >
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

