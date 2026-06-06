<script setup lang="ts">
import { authService } from "~/api/auth/AuthService";
import { useAuthUser, resetAuth } from "~/composables/useAuthUser";
import { useToast } from "~/composables/useToast";

const { success, error } = useToast();
useHead({
    title: "AMUMA",
});
definePageMeta({
    navVariant: "full",
    navHeaderClass: "w-full h-[90px] bg-white z-[9999]",
});
</script>

<template>
    <div class="min-h-screen w-full bg-white"></div>
</template>
