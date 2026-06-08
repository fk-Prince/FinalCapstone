<template>
    <div class="flex flex-col min-h-screen">
        <DefaultNavbar />
        <main class="flex-1">
            <slot />
        </main>
        <AppFooter />
    </div>
</template>

<script setup lang="ts">
import DefaultNavbar from "~/components/sections/DefaultNavbar.vue";
import AppFooter from "~/components/sections/AppFooter.vue";
</script>
