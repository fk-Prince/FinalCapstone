<script setup lang="ts">
import { useSubscriptionCheckout } from "~/stores/subscription";
import visaIcon from "@/assets/icons/visa.png";
import gcashIcon from "@/assets/icons/gcash.jpeg";
import LabelInput from "../ui/BaseInput.vue";
import { type CardDetails } from "~/composables/useSubscriptionPayment";

const checkout = useSubscriptionCheckout();

const props = defineProps<{
    card: CardDetails;
    onCardPay?: () => void | Promise<void>;
    onGCashPay?: () => void | Promise<void>;
}>();

const emit = defineEmits(["update:card"]);

const updateCard = (key: any, value: any) => {
    emit("update:card", {
        ...props.card,
        [key]: value,
    });
};

const methods = [
    { value: "CREDIT-CARD", label: "CREDIT-CARD", image: visaIcon },
    { value: "GCASH", label: "GCASH", image: gcashIcon },
];
</script>

<template>
    <div
        class="bg-white overflow-hidden max-w-md border border-muted-light p-5 rounded-xl shadow"
    >
        <div class="p-5">
            <h2 class="text-2xl font-bold text-gray-800">Payment Method</h2>
        </div>

        <div class="py-6">
            <div class="flex justify-center gap-5">
                <button
                    v-for="method in methods"
                    :key="method.value"
                    @click="checkout.payment_method = method.value"
                    class="w-20 h-14 rounded-xl flex items-center justify-center overflow-hidden transition border-2"
                    :class="
                        checkout.payment_method === method.value
                            ? 'border-blue-600 bg-white shadow-md'
                            : 'border-transparent bg-white'
                    "
                >
                    <img
                        :src="method.image"
                        :alt="method.label"
                        class="w-full h-full object-cover"
                    />
                </button>
            </div>
        </div>

        <div
            v-if="checkout.payment_method === 'CREDIT-CARD'"
            class="p-6 space-y-5"
        >
            <LabelInput
                :model-value="props.card.number"
                @update:model-value="updateCard('number', $event)"
                label="Card Number"
            />

            <div class="grid grid-cols-2 gap-4">
                <LabelInput
                    :model-value="props.card.expMonth"
                    @update:model-value="updateCard('expMonth', $event)"
                    label="Expiration Month"
                />
                <LabelInput
                    :model-value="props.card.expYear"
                    @update:model-value="updateCard('expYear', $event)"
                    label="Expiration Year"
                />
            </div>

            <LabelInput
                :model-value="props.card.cvc"
                @update:model-value="updateCard('cvc', $event)"
                label="Security Code"
            />

            <div class="grid grid-cols-2 gap-4">
                <LabelInput
                    :model-value="props.card.firstName"
                    @update:model-value="updateCard('firstName', $event)"
                    label="First Name"
                />
                <LabelInput
                    :model-value="props.card.lastName"
                    @update:model-value="updateCard('lastName', $event)"
                    label="Last Name"
                />
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" class="accent-primary" />
                Save my payment information
            </label>
            <button
                @click="onCardPay"
                class="w-full bg-primary hover:bg-primary/80 text-white font-semibold py-3 rounded-lg transition"
            >
                Confirm Payment
            </button>
        </div>

        <div v-else-if="checkout.payment_method === 'GCASH'" class="p-6">
            <div
                class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center"
            >
                <h3 class="font-semibold text-lg text-primary">
                    GCash Payment
                </h3>
                <button
                    @click="onGCashPay"
                    class="w-full bg-primary hover:bg-primary/80 text-white font-semibold py-3 rounded-lg transition"
                >
                    Confirm Payment
                </button>
                <p class="text-sm text-gray-600 mt-2">
                    You will be redirected to GCash to complete your payment.
                </p>
            </div>
        </div>
    </div>
</template>
