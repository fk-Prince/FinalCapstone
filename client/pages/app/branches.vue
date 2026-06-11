<template>
    <div>HELLO FROM BRANCH</div>
</template>
<script setup lang="ts">
definePageMeta({
    layout: "dashboard",
    middleware: "auth-client",
});

useHead({ title: "Branches" });
</script>
