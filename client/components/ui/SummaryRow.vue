<template>
    <div
        class="flex justify-between items-start gap-3 py-1 border-t border-gray-100 first:border-0"
    >
        <span class="text-gray-400 shrink-0">{{ label }}</span>
        <span
            class="text-gray-700 font-medium text-right break-words max-w-[60%]"
        >
            {{ value || "–" }}
        </span>
    </div>
</template>

<script setup lang="ts">
defineProps<{ label: string; value?: string }>();
</script>
