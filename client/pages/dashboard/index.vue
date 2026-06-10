<script setup lang="ts">
definePageMeta({
    layout: "dashboard",
    middleware: "auth-client",
    navVariant: "dashboard",
    navHeaderClass: "bg-white w-full h-[90px] z-[9999]",
});

useHead({ title: "Dashboard" });
</script>

<template>
    <div class="w-full min-h-screen p-0 m-0">HELLO</div>
</template>
