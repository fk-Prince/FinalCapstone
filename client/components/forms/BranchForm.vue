<template>
    <div class="max-w-4xl mx-auto p-6 space-y-4">
        <div class="flex gap-4 items-start">
            <div class="flex flex-col flex-1 gap-2">
                <LabelInput
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
                    label="Contact Number"
                    @update:modelValue="clearError('branch_contact_number')"
                    :error="checkout.errors?.branch_contact_number"
                />
            </div>

            <div class="flex flex-col gap-1">
                <div class="flex justify-between">
                    <label class="block text-sm font-semibold text-slate-700"
                        >Branch Logo</label
                    >
                    <button
                        v-if="checkout.branch.image"
                        @click="removeBranchImage"
                        class="text-[12px] text-danger hover:underline flex gap-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>

                        Remove
                    </button>
                </div>
                <div
                    class="h-48 w-48 border-2 border-dashed rounded-lg cursor-pointer flex items-center justify-center overflow-hidden hover:border-primary"
                    @click="branchImageInput?.click()"
                >
                    <img
                        v-if="branchImagePreview"
                        :src="branchImagePreview"
                        alt="Preview"
                        class="h-full w-full object-cover"
                    />

                    <div v-else class="text-center text-gray-400 text-sm">
                        <div class="text-2xl">📷</div>
                        <div>Upload</div>
                    </div>
                </div>

                <input
                    ref="branchImageInput"
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleBranchImage"
                />

                <p
                    v-if="checkout.errors?.branch_image"
                    class="mt-1 text-xs text-red-500"
                >
                    {{ checkout.errors.branch_image }}
                </p>
            </div>
        </div>

        <div class="flex gap-2 flex-col">
            <label class="text-sm font-semibold text-slate-700">Location</label>
            <LocationSelector @location-selected="handleLocation" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import LocationSelector from "../ui/LocationSelector.vue";
import LabelInput from "../ui/BaseInput.vue";
import { useSubscriptionCheckout } from "~/stores/subscription";

const props = defineProps<{
    errors?: Record<string, string>;
    choices?: Boolean;
}>();

const checkout = useSubscriptionCheckout();
const branchImagePreview = ref<string | null>(null);
const branchImageInput = ref<HTMLInputElement | null>(null);

const handleBranchImage = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;
    checkout.branch.image = file;
    branchImagePreview.value = URL.createObjectURL(file);
    clearError("branch_image");
};
const removeBranchImage = () => {
    checkout.branch.image = null;
    branchImagePreview.value = null;
    if (branchImageInput.value) {
        branchImageInput.value.value = "";
    }
};
const handleLocation = ({
    lat,
    lng,
    street,
    city,
    province,
    country,
}: {
    lat: number;
    lng: number;
    street: string;
    city: string;
    province: string;
    country: string;
}) => {
    checkout.branch.lat = lat;
    checkout.branch.lng = lng;
    checkout.branch.street = street;
    checkout.branch.city = city;
    checkout.branch.province = province;
    checkout.branch.country = country;
};

function clearError(field: string) {
    delete checkout.errors[field];
}
</script>
