<template>
    <div class="max-w-4xl mx-auto p-6">
        <div
            v-if="loading"
            class="bg-white rounded-2xl shadow p-10 flex justify-center"
        >
            <div
                class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"
            />
        </div>
        <template v-else>
            <div class="bg-white border rounded-2xl shadow-sm">
                <div class="p-5 border-b">
                    <h2 class="font-semibold text-lg">Subscription Plan</h2>
                </div>

                <div v-if="loading" class="p-5"></div>

                <template v-else>
                    <div class="p-5 space-y-3">
                        <label
                            v-for="plan in checkout.plans"
                            :key="plan.plan_id"
                            class="flex items-center justify-between border rounded-xl p-4 cursor-pointer transition"
                            :class="
                                checkout.selectedPlan?.plan_id === plan.plan_id
                                    ? 'border-blue-600 bg-blue-50'
                                    : 'border-gray-200'
                            "
                        >
                            <div class="flex items-start gap-3">
                                <input
                                    type="radio"
                                    :checked="
                                        checkout.selectedPlan?.plan_id ===
                                        plan.plan_id
                                    "
                                    @change="checkout.selectedPlan = plan"
                                />

                                <div>
                                    <p class="font-semibold">
                                        {{ plan.name }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ plan.description }}
                                    </p>
                                </div>
                            </div>

                            <div class="font-bold text-blue-600">
                                ₱{{
                                    checkout.selectedInterval === "yearly"
                                        ? plan.yearly_price?.price
                                        : plan.monthly_price?.price
                                }}
                            </div>
                        </label>
                    </div>

                    <div class="border-t p-5 mb-5">
                        <h2 class="font-semibold text-lg mb-5">
                            Billing Interval
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label
                                v-for="opt in intervalOptions"
                                :key="opt.value"
                                class="border rounded-xl p-4 cursor-pointer transition"
                                :class="
                                    checkout.selectedInterval === opt.value
                                        ? 'border-blue-600 bg-blue-50'
                                        : 'border-gray-200'
                                "
                            >
                                <div class="flex items-start gap-3">
                                    <input
                                        type="radio"
                                        v-model="checkout.selectedInterval"
                                        :value="opt.value"
                                    />

                                    <div>
                                        <p class="font-semibold text-sm">
                                            {{ opt.label }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ opt.description }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <div class="border-y mb-5">
                            <h2 class="text-lg p-5 font-semibold text-gray-900">
                                Branch Information
                            </h2>
                        </div>
                        <div v-if="loading" class="p-5"></div>
                        <BranchForm :branch="checkout.branch" />
                    </div>

                    <div>
                        <div class="border-y">
                            <h2 class="text-lg p-5 font-semibold text-gray-900">
                                Agency Information
                                <p class="text-sm font-normal text-gray-500">
                                    Optional
                                </p>
                            </h2>
                        </div>
                        <AgencyForm :agency="checkout.agency" />
                    </div>
                </template>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useSubscriptionCheckout } from "~/stores/subscription";
import { planService } from "@/api/plan/PlanService";
import AgencyForm from "../forms/AgencyForm.vue";
import BranchForm from "../forms/BranchForm.vue";

const checkout = useSubscriptionCheckout();
const loading = ref(true);

const intervalOptions = [
    {
        value: "monthly",
        label: "Monthly",
        description: "Billed monthly",
    },
    {
        value: "yearly",
        label: "Yearly",
        description: "Save more yearly",
    },
];

onMounted(async () => {
    try {
        const plans = await planService.list();
        checkout.setPlans(plans);
    } finally {
        loading.value = false;
    }
});
</script>
