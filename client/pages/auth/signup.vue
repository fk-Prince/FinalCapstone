<script setup lang="ts">
import SignupSection from "~/components/sections/SignupSection.vue";
definePageMeta({
    middleware: "auth-client",
    navVariant: "logo",
    navHeaderClass:
        "fixed top-0 left-0 w-full h-[90px] bg-transparent z-[9999]",
    footer: false,
});
useHead({
    title: "Sign Up - AMUMA",
});
</script>

<template>
    <ClientOnly>
        <SignupSection />
    </ClientOnly>
</template>
