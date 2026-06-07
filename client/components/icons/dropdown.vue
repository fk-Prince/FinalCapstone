<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5 transition-transform duration-200"
        :class="[{ 'rotate-180': isOpen }, extraClass]"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M19 9l-7 7-7-7"
        />
    </svg>
</template>

<script setup lang="ts">
defineProps<{
    isOpen: boolean;
    extraClass?: string;
}>();
</script>
