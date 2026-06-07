<template>
    <ClientOnly>
        <SigninSection />
    </ClientOnly>
</template>

<script setup lang="ts">
import SigninSection from "~/components/sections/SigninSection.vue";
definePageMeta({
    middleware: "auth-client",
    navVariant: "logo",
    navHeaderClass:
        "fixed top-0 left-0 w-full h-[90px] bg-transparent z-[9999]",
});

useHead({
    title: "Sign In - AMUMA",
});
</script>
