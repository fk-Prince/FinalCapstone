<template>
    <div class="min-h-screen flex items-start justify-center py-10 px-6">
        <div class="flex w-full max-w-[80%] items-start">
            <CheckoutSummary />

            <PaymentForm
                :card="card"
                :processing="processing"
                :onCardPay="payCard"
                :onGCashPay="payGCash"
            />
        </div>
    </div>
</template>
<script setup lang="ts">
import { reactive } from "vue";
import CheckoutSummary from "~/components/sections/CheckoutSummary.vue";
import PaymentForm from "~/components/forms/PaymentForm.vue";
import { useSubscriptionCheckout } from "~/stores/subscription";
import {
    gcashPayment,
    cardPayment,
} from "~/composables/useSubscriptionPayment";
import type { SubscriptionRequest } from "~/api/subscription/SubscriptionService";
import { useToast } from "~/composables/useToast";

const checkout = useSubscriptionCheckout();
useHead({ title: "Subscription Checkout" });
definePageMeta({
    middleware: ["auth-client", "subscription-guard"],
    navVariant: "full",
    navHeaderClass: "w-full h-[90px] bg-white z-[9999] border-b border-muted",
});
const card = reactive({
    number: 4000000000002503,
    expMonth: "04",
    expYear: "29",
    cvc: "123",
    firstName: "prince",
    lastName: "sestoso",
});

const processing = ref(false);
const isModalOpen = ref(false);
const closeModal = ref<(() => void) | null>(null);
closeModal.value = () => {
    isModalOpen.value = false;
};

const { success, error } = useToast();

const payCard = async () => {
    if (processing.value) return;
    processing.value = true;
    try {
        const data: SubscriptionRequest = {
            plan_code: checkout.selectedPlan?.plan_code,
            payment_method: checkout.payment_method,
            billing_interval: checkout.selectedInterval,

            branch_name: checkout.branch?.name,
            branch_street: checkout.branch?.street,
            branch_city: checkout.branch?.city,
            branch_province: checkout.branch?.province,
            branch_country: checkout.branch?.country,
            branch_contact_number: checkout.branch?.contact_number,
            branch_image: checkout.branch?.image,
            branch_description: checkout.branch?.description,
            branch_settings: checkout.settings,
            branch_latitude: checkout.branch?.lat,
            branch_longitude: checkout.branch?.lng,

            agency_id: checkout.agency?.id,
            agency_name: checkout.agency?.name,
            agency_description: checkout.agency?.description,
            agency_street: checkout.agency?.street,
            agency_city: checkout.agency?.city,
            agency_province: checkout.agency?.province,
            agency_country: checkout.agency?.country,
            agency_latitude: checkout.agency?.lat,
            agency_longitude: checkout.agency?.lng,
        };

        const res = await cardPayment(card, closeModal, data);
        if (res) {
            success(res.message);
        }
    } catch (err: any) {
        error(err.message);
    } finally {
        processing.value = false;
    }
};
const payGCash = async () => {
    if (processing.value) return;
    processing.value = true;
    try {
        const data: SubscriptionRequest = {
            plan_code: checkout.selectedPlan?.plan_code,
            payment_method: checkout.payment_method,
            billing_interval: checkout.selectedInterval,

            branch_name: checkout.branch?.name,
            branch_street: checkout.branch?.street,
            branch_city: checkout.branch?.city,
            branch_province: checkout.branch?.province,
            branch_country: checkout.branch?.country,
            branch_contact_number: checkout.branch?.contact_number,
            branch_image: checkout.branch?.image,
            branch_description: checkout.branch?.description,
            branch_settings: checkout.settings,
            branch_latitude: checkout.branch?.lat,
            branch_longitude: checkout.branch?.lng,

            agency_id: checkout.agency?.id,
            agency_name: checkout.agency?.name,
            agency_description: checkout.agency?.description,
            agency_street: checkout.agency?.street,
            agency_city: checkout.agency?.city,
            agency_province: checkout.agency?.province,
            agency_country: checkout.agency?.country,
            agency_latitude: checkout.agency?.lat,
            agency_longitude: checkout.agency?.lng,
        };

        const res = await gcashPayment(closeModal, data);
        if (res) {
            success(res.message);
        }
    } catch (err: any) {
        error(err.message);
    } finally {
        processing.value = false;
    }
};
</script>
