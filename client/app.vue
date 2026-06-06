<template>
    <div>
        <!-- <UApp> -->
        <NuxtLayout class="font-sans m-0 p-0">
            <NuxtPage />
        </NuxtLayout>
        <!-- </UApp> -->
        <AppToast ref="toastRef" />
    </div>
</template>

<script setup lang="ts">
import AppToast from "./components/ui/AppToast.vue";
import { onMounted, ref } from "vue";
import { registerToast } from "@/composables/useToast";

const toastRef = ref();

onMounted(() => {
    registerToast(toastRef.value);
});
</script>
