<template>
    <div class="p-10">
        <h1 class="text-2xl font-bold text-green-600">Payment Status</h1>

        <p class="mt-2 text-gray-600">Status: {{ status }}</p>
    </div>
</template>

<script setup lang="ts">
const route = useRoute();
const status = computed(() => route.query.status || "unknown");
</script>
