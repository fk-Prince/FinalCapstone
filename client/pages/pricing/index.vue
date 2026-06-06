<template>
    <div class="min-h-screen bg-white">
        <main class="max-w-6xl mx-auto px-6 py-16">
            <div class="text-center mb-10">
                <p
                    class="text-xs font-bold tracking-[0.2em] text-gray-400 uppercase mb-4"
                >
                    Pricing
                </p>
                <h1
                    class="font-display font-extrabold text-4xl md:text-5xl text-gray-900 leading-tight"
                >
                    One calm price. All the
                    <span class="text-primary">features</span> you need.
                </h1>
            </div>
            <BillingToggle v-model:annual="annual" />

            <ComparableTable />
        </main>
    </div>
</template>

<script setup>
import BillingToggle from "~/components/ui/BillingToggle.vue";
import PricingCard from "~/components/ui/PricingCard.vue";
import ComparableTable from "~/components/ui/ComparableTable.vue";
import { ref } from "vue";

useHead({
    title: "Pricing",
});

const annual = ref(false);

const plans = [
    {
        planLabel: "Plan A",
        title: "Home-Care",
        description: "Perfect for small home care agencies",
        price: 1500,
        ctaText: "Subscribe to Homecare",
        featured: false,
        features: [
            "Daily home care visits",
            "Live-in caregivers",
            "Family portal",
        ],
    },
    {
        planLabel: "Plan B",
        title: "In-House Facility",
        description: "For growing agencies with facility needs",
        price: 2500,
        ctaText: "Subscribe to Homecare",
        featured: false,
        features: [
            "Facility Management",
            "Admission & rooms",
            "eMAR & vitals",
            "VIP CCTV access",
            "Family Portal",
            "VIP & Common Rooms",
        ],
    },
    {
        planLabel: "Plan C",
        title: "Hybrid",
        description: "For In-house facility and Homecare Services",
        price: 3500,
        ctaText: "Subscribe to Hybrid",
        featured: true,
        features: [
            "All Home Care features",
            "All Facility features",
            "Multi-branch up to 5",
            "Priority support",
        ],
    },
];
</script>
