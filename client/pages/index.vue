<script setup lang="ts">
import { authService } from "~/api/auth/AuthService";
import {
    useAuthUser,
    useAuthReady,
    resetAuth,
} from "~/composables/useAuthUser";

const user = useAuthUser();

const logout = async () => {
    try {
        const res = await authService.logout();
        alert(res.message);
        resetAuth();
        await navigateTo("/auth/signin");
    } catch (err) {
        console.error(err);
    }
};
</script>

<template>
    <ClientOnly>
        <div class="w-full bg-black min-h-screen text-white p-6">
            <h1 class="text-primary text-2xl mb-4">Home</h1>

            <NuxtLink to="/auth/signup" class="mr-4">SIGNUP</NuxtLink>
            <NuxtLink to="/auth/signin" class="mr-4">SIGNIN</NuxtLink>
            <NuxtLink to="/subscription" class="mr-4">SUBSCRIPTION</NuxtLink>
            <button @click="logout" class="ml-4 text-red-400">LOGOUT</button>

            <div class="mt-6" v-if="user?.first_name">
                Welcome {{ user.first_name }}
            </div>
            <pre class="mt-6 text-green-400">{{ user }}</pre>
        </div>
    </ClientOnly>
</template>
