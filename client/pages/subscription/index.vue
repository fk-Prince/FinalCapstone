<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import SubscriptionForm from "@/components/forms/SubscriptionForm.vue";
import {
    subscriptionService,
    type SubscriptionRequest,
} from "~/api/subscription/SubscriptionService";
import { planService } from "@/api/plan/PlanService";
import {
    useSubscriptionCheckout,
    branchFields,
    agencyFields,
} from "~/stores/subscription";

definePageMeta({
    middleware: "auth-client",
});

const checkout = useSubscriptionCheckout();

const loading = ref(true);

const errors = ref<Record<string, string>>({});
const clearError = (field: string) => {
    delete errors.value[field];
};

onMounted(async () => {
    try {
        const res = await planService.list();
        checkout.setPlans(res);
    } finally {
        loading.value = false;
    }
});
const confirmPayment = async () => {
    errors.value = {};

    try {
        const subData: SubscriptionRequest = {
            plan_code: checkout.selectedPlan.plan_code,
            payment_method: checkout.payment_method,
            billing_interval: checkout.selectedInterval,

            branch_name: checkout.branch.name,
            branch_postal_code: checkout.branch.postal_code,
            branch_street: checkout.branch.street,
            branch_city: checkout.branch.city,
            branch_province: checkout.branch.province,
            branch_country: checkout.branch.country,
            branch_contact_number: checkout.branch.contact_number,
            branch_image: checkout.branch.image ?? null,

            agency_name: checkout.agency.name,
            agency_description: checkout.agency.description,
        };

        await subscriptionService.validateSubscription(subData);

        await navigateTo({
            path: "/subscription/checkout/payment",
            query: {
                code: checkout.selectedPlan?.plan_id,
                interval: checkout.selectedInterval,
            },
        });
    } catch (err: any) {
        const validationErrors =
            err?.response?.data?.errors || err?.data?.errors || {};
        Object.keys(validationErrors).forEach((key) => {
            errors.value[key] = validationErrors[key][0];
        });
    }
};
</script>

<template>
    <ClientOnly>
        <div class="bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto p-6">
                <h1 class="text-3xl font-bold mb-6">
                    Complete Your Subscription
                </h1>

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-8">
                    <div class="space-y-6">
                        <div
                            v-if="loading"
                            class="bg-white rounded-2xl shadow p-10 flex justify-center"
                        >
                            <div
                                class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"
                            />
                        </div>

                        <template v-else>
                            <SubscriptionForm
                                :errors="errors"
                                @clear-error="clearError"
                            />
                        </template>
                    </div>

                    <div class="sticky top-6 space-y-4">
                        <div class="bg-white rounded-2xl shadow-sm border p-6">
                            <h2 class="text-lg font-bold mb-4">Summary</h2>
                            <div class="mb-5">
                                <p
                                    class="text-xs text-gray-400 uppercase font-semibold mb-2"
                                >
                                    Subscription
                                </p>

                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Plan</span>
                                        <span class="font-semibold">
                                            {{
                                                checkout.selectedPlan?.name ||
                                                "—"
                                            }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-500"
                                            >Billing</span
                                        >
                                        <span class="font-semibold capitalize">
                                            {{
                                                checkout.selectedInterval || "—"
                                            }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Price</span>
                                        <span class="font-bold text-blue-600">
                                            {{
                                                checkout.selectedPrice != null
                                                    ? `₱${checkout.selectedPrice}`
                                                    : "—"
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5 border-t pt-4">
                                <p
                                    class="text-xs text-gray-400 uppercase font-semibold mb-2"
                                >
                                    Branch
                                </p>

                                <div class="space-y-2 text-sm">
                                    <div
                                        v-for="field in branchFields"
                                        :key="field.key"
                                        class="flex justify-between gap-3"
                                    >
                                        <span class="text-gray-500">
                                            {{ field.label }}
                                        </span>

                                        <span
                                            class="font-semibold text-right max-w-[200px] truncate"
                                        >
                                            {{
                                                checkout.branch[
                                                    field.key as keyof typeof checkout.branch
                                                ] || "—"
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- AGENCY -->
                            <div class="mb-5 border-t pt-4">
                                <p
                                    class="text-xs text-gray-400 uppercase font-semibold mb-2"
                                >
                                    Agency
                                </p>

                                <div class="space-y-2 text-sm">
                                    <div
                                        v-for="field in agencyFields"
                                        :key="field.key"
                                        class="flex justify-between gap-3"
                                    >
                                        <span class="text-gray-500">
                                            {{ field.label }}
                                        </span>

                                        <span
                                            class="font-semibold text-right max-w-[200px] truncate"
                                        >
                                            {{
                                                checkout.agency[
                                                    field.key as keyof typeof checkout.agency
                                                ] || "—"
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="border-t pt-4 flex justify-between items-center"
                            >
                                <span class="font-bold">Total</span>

                                <span class="text-xl font-bold text-blue-600">
                                    {{
                                        checkout.selectedPrice != null
                                            ? `₱${checkout.selectedPrice}`
                                            : "—"
                                    }}
                                </span>
                            </div>

                            <button
                                @click="confirmPayment"
                                class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition"
                            >
                                Confirm & Pay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ClientOnly>
</template>
