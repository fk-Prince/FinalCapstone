<template>
    <header
        class="h-24 bg-white border-b border-gray-200 flex items-center justify-between px-8"
    >
        <h1 class="text-4xl font-bold text-primary">
            {{ title }}
        </h1>

        <div class="flex items-center gap-6">
            <button class="relative text-gray-600">
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 10h8m-8 4h5m7 5l-3-3H6a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-1z"
                    />
                </svg>
            </button>

            <button class="relative">
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    />
                </svg>

                <span
                    class="absolute -top-1 -right-1 w-2 h-2 bg-danger rounded-full"
                />
            </button>

            <BaseDropdownMenu align="right">
                <template #trigger="{ toggle }">
                    <button
                        @click="toggle"
                        class="w-12 h-12 rounded-full bg-blue-100 text-primary font-semibold"
                    >
                        A
                    </button>
                </template>

                <template #default>
                    <div class="p-4">Profile Menu</div>
                </template>
            </BaseDropdownMenu>
        </div>
    </header>
</template>

<script setup lang="ts">
import BaseDropdownMenu from "../ui/BaseDropdownMenu.vue";
defineProps<{
    title: string;
}>();
</script>
