<script setup lang="ts">
import SubscriptionSummary from "~/components/sections/SubscriptionSummary.vue";

definePageMeta({
    middleware: "auth-client",
});
</script>

<template>
    <ClientOnly>
        <!-- <div class="bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-6">
                    Complete Your Subscription
                </h1>

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-8">
                    <div>
                        <SubscrptionSection />
                    </div>

                    <div class="sticky top-6 mt-6">
                        <SubscriptionSummary class="sticky top-6 self-start" />
                    </div>
                </div>
            </div>
        </div> -->
    </ClientOnly>
</template>
