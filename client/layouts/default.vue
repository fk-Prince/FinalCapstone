<template>
    <div class="relative min-h-screen">
        <DefaultNavbar />
        <slot />
    </div>
</template>

<script setup lang="ts">
import DefaultNavbar from "~/components/ui/DefaultNavbar.vue";
</script>
