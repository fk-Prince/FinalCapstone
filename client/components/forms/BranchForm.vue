<template>
    <div class="max-w-4xl mx-auto p-6 space-y-4">
        <!-- <LabelInput
            v-model="checkout.branch.name"
            label="Branch Name"
            @update:modelValue="clearError('branch_name')"
            :error="checkout.errors?.branch_name"
        />

        <LabelInput
            v-model="checkout.branch.description"
            label="Description"
            @update:modelValue="clearError('branch_description')"
            :error="checkout.errors?.branch_description"
        />

        <LabelInput
            v-model="checkout.branch.contact_number"
            label="Description"
            @update:modelValue="clearError('branch_contact_number')"
            :error="checkout.errors?.branch_contact_number"
        /> -->
    </div>
</template>
<!-- 
<script setup lang="ts">
import LabelInput from "../ui/BaseInput.vue";
import { useSubscriptionCheckout } from "~/stores/subscription";
import { onMounted, nextTick } from "vue";

defineProps<{
    errors?: Record<string, string>;
}>();

const checkout = useSubscriptionCheckout();

function clearError(field: string) {
    delete checkout.errors[field];
}

function countryChange() {
    clearError("branch_country");
    checkout.branch.province = "";
    checkout.branch.city = "";
}

function provinceChange() {
    clearError("branch_province");
    checkout.branch.city = "";
}

onMounted(async () => {
    await nextTick();

    if (!checkout.branch.country) {
        checkout.branch.country = "PH";
    }
});
</script> -->
