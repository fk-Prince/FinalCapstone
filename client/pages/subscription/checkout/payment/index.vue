<script setup lang="ts">
import { reactive, ref } from "vue";
import PaymentForm from "~/components/forms/PaymentForm.vue";
import {
    cardPayment,
    gcashPayment,
} from "~/composables/useSubscriptionPayment";
import { useSubscriptionCheckout } from "~/stores/subscription";

definePageMeta({
    middleware: ["auth-client", "subscription-guard"],
});

const checkout = useSubscriptionCheckout();

const closeModal = ref(null);
const data = ref(null);

const card = reactive({
    number: 4000000000002503,
    expMonth: "04",
    expYear: "29",
    cvc: "123",
    firstName: "prince",
    lastName: "sestoso",
});

const payCard = async () => {
    try {
        if (!checkout.subscriptionPayload) return;
        const res = await cardPayment(
            card,
            checkout,
            closeModal,
            checkout.subscriptionPayload,
        );
        alert(res);
    } catch (err) {
        console.error("Payment error:", err);
    }
};

const payGCash = async () => {
    try {
        const res = await gcashPayment(checkout, closeModal);
        alert(res);
    } catch (err) {
        console.error("Payment error:", err);
    }
};
</script>

<template>
    <ClientOnly>
        <div class="min-h-screen flex justify-center py-10 px-4 bg-gray-50">
            <div class="w-full max-w-7xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    <div class="relative">
                        <div class="sticky top-6">
                            <div class="bg-white rounded-2xl shadow p-6">
                                <h2 class="text-xl font-bold mb-6">
                                    Subscription Summary
                                </h2>

                                <div class="mb-6">
                                    <p
                                        class="text-xs font-bold uppercase text-gray-400 mb-3"
                                    >
                                        Subscription
                                    </p>

                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500"
                                                >Plan</span
                                            >
                                            <span class="font-semibold">
                                                {{
                                                    checkout.selectedPlan
                                                        ?.name ?? "—"
                                                }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="text-gray-500"
                                                >Billing</span
                                            >
                                            <span
                                                class="font-semibold capitalize"
                                            >
                                                {{
                                                    checkout.selectedInterval ??
                                                    "—"
                                                }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="text-gray-500"
                                                >Price</span
                                            >
                                            <span
                                                class="font-bold text-blue-600"
                                            >
                                                {{
                                                    checkout.selectedPrice !=
                                                    null
                                                        ? `₱${checkout.selectedPrice}`
                                                        : "—"
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t pt-4 mb-6">
                                    <p
                                        class="text-xs font-bold uppercase text-gray-400 mb-3"
                                    >
                                        Branch Information
                                    </p>

                                    <div class="space-y-2 text-sm pl-4">
                                        <div
                                            v-for="field in branchFields"
                                            :key="field.key"
                                            class="flex justify-between"
                                        >
                                            <span class="text-gray-500">
                                                {{ field.label }}
                                            </span>

                                            <span
                                                class="font-semibold text-right max-w-[220px] truncate"
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
                                <div class="border-t pt-4 mb-6">
                                    <p
                                        class="text-xs font-bold uppercase text-gray-400 mb-3"
                                    >
                                        Branch Information
                                    </p>

                                    <div class="space-y-2 text-sm pl-4">
                                        <div
                                            v-for="field in agencyFields"
                                            :key="field.key"
                                            class="flex justify-between"
                                        >
                                            <span class="text-gray-500">
                                                {{ field.label }}
                                            </span>

                                            <span
                                                class="font-semibold text-right max-w-[220px] truncate"
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
                                    <span class="font-bold text-lg">Total</span>
                                    <span
                                        class="text-xl font-bold text-blue-600"
                                    >
                                        {{
                                            checkout.selectedPrice != null
                                                ? `₱${checkout.selectedPrice}`
                                                : "—"
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="bg-white rounded-2xl shadow p-6">
                            <PaymentForm
                                :card="card"
                                :onCardPay="payCard"
                                :onGCashPay="payGCash"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ClientOnly>
</template>
