<template>
    <div class="max-w-4xl mx-auto p-6 space-y-4">
        <!-- NAME -->
        <LabelInput
            v-model="checkout.agency.name"
            label="Agency Name"
            @update:modelValue="clearError('agency_name')"
            :error="checkout.errors?.agency_name"
        />

        <!-- DESCRIPTION -->
        <LabelInput
            v-model="checkout.agency.description"
            label="Description"
            @update:modelValue="clearError('agency_description')"
            :error="checkout.errors?.agency_description"
        />
        <!-- COUNTRY -->
        <LabelInput
            v-model="checkout.agency.country"
            label="Country"
            mode="country"
            @update:modelValue="handleCountryChange"
            :error="checkout.errors?.agency_country"
        />

        <!-- STATE -->
        <LabelInput
            v-model="checkout.agency.province"
            label="Province"
            mode="state"
            :country-code="checkout.agency.country"
            @update:modelValue="handleProvinceChange"
            :error="checkout.errors?.agency_province"
        />

        <!-- CITY -->
        <LabelInput
            v-model="checkout.agency.city"
            label="City"
            mode="city"
            :country-code="checkout.agency.country"
            :state-code="checkout.agency.province"
            @update:modelValue="clearError('agency_city')"
            :error="checkout.errors?.agency_city"
        />

        <!-- STREET -->
        <LabelInput
            v-model="checkout.agency.street"
            label="Street"
            @update:modelValue="clearError('agency_street')"
            :error="checkout.errors?.agency_street"
        />
    </div>
</template>

<script setup lang="ts">
import LabelInput from "../ui/BaseInput.vue";
import { useSubscriptionCheckout } from "~/stores/subscription";
import { onMounted, nextTick } from "vue";

const checkout = useSubscriptionCheckout();

function clearError(field: string) {
    delete checkout.errors[field];
}

function handleCountryChange() {
    clearError("agency_country");
    checkout.agency.province = "";
    checkout.agency.city = "";
}

function handleProvinceChange() {
    clearError("agency_province");
    checkout.agency.city = "";
}

onMounted(async () => {
    await nextTick();
});
</script>
