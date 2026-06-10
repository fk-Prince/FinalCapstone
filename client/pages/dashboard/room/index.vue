<script setup lang="ts">
definePageMeta({
    layout: "dashboard",
    middleware: "auth-client",
    navVariant: "dashboard",
    navHeaderClass:
        "fixed top-0 left-0 bg-transparent w-full h-[90px] z-[9999]",
});

useHead({ title: "Room" });
</script>

<template>
    <div>HELLO</div>
</template>
