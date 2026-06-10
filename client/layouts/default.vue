<template>
    <div class="flex flex-col min-h-screen">
        <DefaultNavbar />

        <main class="flex-1">
            <slot />
        </main>
<<<<<<< HEAD

        <AppFooter v-if="footer" />
=======
        <template v-if="footer">
            <AppFooter />
        </template>
>>>>>>> 7bf6bf092706de0c57a61998403f3f46fc423517
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useRoute } from "vue-router";

import DefaultNavbar from "~/components/sections/DefaultNavbar.vue";
import AppFooter from "~/components/sections/AppFooter.vue";
<<<<<<< HEAD

const route = useRoute();

=======
import { useRoute } from "vue-router";

const route = useRoute();
>>>>>>> 7bf6bf092706de0c57a61998403f3f46fc423517
const footer = computed(() => route.meta.footer ?? true);
</script>
