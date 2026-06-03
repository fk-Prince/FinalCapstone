<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { useSubscriptionCheckout } from "~/stores/subscription";
import { agencyService } from "~/api/agency/AgencyService";
import LabelInput from "@/components/ui/LabelInput.vue";
import type { ComponentPublicInstance } from "vue";
const props = defineProps<{
    errors?: Record<string, string>;
}>();

const checkout = useSubscriptionCheckout();
const mode = ref<"existing" | "new">("existing");
const selectedAgency = ref<string | null>(null);

const agencies = ref<any[]>([]);

const page = ref(1);
const lastPage = ref(1);
const loading = ref(false);

const open = ref({
    agency: true,
    branch: true,
});

const intervalOptions = [
    { value: "monthly", label: "Monthly", description: "Billed monthly" },
    { value: "yearly", label: "Yearly", description: "Save more yearly" },
];

const fetchAgencies = async (reset = false) => {
    if (loading.value) return;

    loading.value = true;

    try {
        const res = await agencyService.list({
            page: page.value,
            per_page: 3,
            owned: true,
        });

        if (reset) {
            agencies.value = res.data;
        } else {
            agencies.value.push(...res.data);
        }

        lastPage.value = res.meta.last_page;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
};

const loadMoreAgencies = async () => {
    if (page.value >= lastPage.value) return;

    page.value++;
    await fetchAgencies();
};

watch(selectedAgency, (name) => {
    if (mode.value !== "existing") return;

    const selected = agencies.value.find((a) => a.name === name);

    if (selected) {
        checkout.agency.name = selected.name;
        checkout.agency.description = selected.description;
    }
});

watch(mode, () => {
    selectedAgency.value = null;
    checkout.agency.name = "";
    checkout.agency.description = "";
});

onMounted(async () => {
    await fetchAgencies(true);
    if (checkout.agency?.name) {
        selectedAgency.value = checkout.agency.name;
    }
});

const fieldRefs = ref<Record<string, HTMLElement | null>>({});
const setFieldRef =
    (key: string) => (el: Element | ComponentPublicInstance | null) => {
        if (!el) return;

        const htmlEl = (el as ComponentPublicInstance).$el ?? el;

        fieldRefs.value[key] = htmlEl as HTMLElement;
    };
watch(
    () => props.errors,
    async (errors) => {
        if (!errors) return;

        await nextTick();

        const firstKey = Object.keys(errors)[0];
        if (!firstKey) return;

        const el = fieldRefs.value[firstKey];

        if (el) {
            el.scrollIntoView({
                behavior: "smooth",
                block: "center",
            });
        }
    },
    { deep: true },
);

const emit = defineEmits<{
    (e: "clear-error", field: string): void;
}>();
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 space-y-4">
        <!-- PLAN -->
        <div class="bg-white border rounded-2xl shadow-sm">
            <button class="w-full flex justify-between items-center p-5">
                <h2 class="font-semibold text-lg">Plan & Billing</h2>
            </button>

            <div class="p-5 border-t space-y-6">
                <div class="space-y-3">
                    <label
                        v-for="plan in checkout.plans"
                        :key="plan.plan_id"
                        class="flex justify-between border rounded-xl p-4 cursor-pointer"
                        :class="
                            checkout.selectedPlan?.plan_id === plan.plan_id
                                ? 'border-blue-600 bg-blue-50'
                                : 'border-gray-200'
                        "
                    >
                        <div class="flex gap-3">
                            <input
                                type="radio"
                                v-model="checkout.selectedPlan"
                                :value="plan"
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

                <div class="grid grid-cols-2 gap-3">
                    <label
                        v-for="opt in intervalOptions"
                        :key="opt.value"
                        class="border rounded-xl p-4 cursor-pointer"
                        :class="
                            checkout.selectedInterval === opt.value
                                ? 'border-blue-600 bg-blue-50'
                                : 'border-gray-200'
                        "
                    >
                        <input
                            type="radio"
                            v-model="checkout.selectedInterval"
                            :value="opt.value"
                        />

                        <p class="font-semibold text-sm">
                            {{ opt.label }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ opt.description }}
                        </p>
                    </label>
                </div>
            </div>
        </div>

        <!-- BRANCH -->
        <div class="bg-white border rounded-2xl shadow-sm">
            <button
                class="w-full flex justify-between items-center p-5"
                @click="open.branch = !open.branch"
            >
                <h2 class="font-semibold text-lg">Branch</h2>
                <span>{{ open.branch ? "▲" : "▼" }}</span>
            </button>

            <div v-if="open.branch" class="p-5 border-t">
                <div class="grid grid-cols-2 gap-4">
                    <LabelInput
                        :ref="setFieldRef('branch_name')"
                        v-model="checkout.branch.name"
                        @update:modelValue="emit('clear-error', 'branch_name')"
                        label="Branch Name"
                        :error="props.errors?.branch_name"
                    />

                    <LabelInput
                        :ref="setFieldRef('branch_contact_number')"
                        v-model="checkout.branch.contact_number"
                        @update:modelValue="
                            emit('clear-error', 'branch_contact_number')
                        "
                        label="Contact Number"
                        :error="props.errors?.branch_contact_number"
                    />

                    <LabelInput
                        :ref="setFieldRef('branch_street')"
                        v-model="checkout.branch.street"
                        @update:modelValue="
                            emit('clear-error', 'branch_street')
                        "
                        label="Street Address"
                        class="col-span-2"
                        :error="props.errors?.branch_street"
                    />

                    <LabelInput
                        :ref="setFieldRef('branch_postal_code')"
                        v-model="checkout.branch.postal_code"
                        @update:modelValue="
                            emit('clear-error', 'branch_postal_code')
                        "
                        label="Postal Code"
                        :error="props.errors?.branch_postal_code"
                    />

                    <LabelInput
                        :ref="setFieldRef('branch_city')"
                        v-model="checkout.branch.city"
                        @update:modelValue="emit('clear-error', 'branch_city')"
                        label="City"
                        :error="props.errors?.branch_city"
                    />

                    <LabelInput
                        :ref="setFieldRef('branch_province')"
                        v-model="checkout.branch.province"
                        @update:modelValue="
                            emit('clear-error', 'branch_province')
                        "
                        label="Province"
                        :error="props.errors?.branch_province"
                    />

                    <LabelInput
                        :ref="setFieldRef('branch_country')"
                        v-model="checkout.branch.country"
                        @update:modelValue="
                            emit('clear-error', 'branch_country')
                        "
                        label="Country"
                        :error="props.errors?.branch_country"
                    />

                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-2">
                            Branch Image
                        </label>

                        <input
                            type="file"
                            accept="image/*"
                            class="w-full border rounded-lg p-2"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- AGENCY -->
        <div class="bg-white border rounded-2xl shadow-sm">
            <button
                class="w-full flex justify-between items-center p-5"
                @click="open.agency = !open.agency"
            >
                <h2 class="font-semibold text-lg">Agency</h2>
                <span>{{ open.agency ? "▲" : "▼" }}</span>
            </button>

            <div v-if="open.agency" class="p-5 border-t space-y-4">
                <div class="flex bg-gray-100 p-1 rounded-xl">
                    <button
                        type="button"
                        class="flex-1 py-2 rounded-lg text-sm"
                        :class="
                            mode === 'existing'
                                ? 'bg-white shadow text-blue-600'
                                : ''
                        "
                        @click="mode = 'existing'"
                    >
                        Existing
                    </button>

                    <button
                        type="button"
                        class="flex-1 py-2 rounded-lg text-sm"
                        :class="
                            mode === 'new'
                                ? 'bg-white shadow text-blue-600'
                                : ''
                        "
                        @click="mode = 'new'"
                    >
                        New
                    </button>
                </div>

                <div v-if="mode === 'existing'" class="space-y-3">
                    <label
                        v-for="agency in agencies"
                        :key="agency.agency_id"
                        class="border rounded-xl p-4 flex gap-3 cursor-pointer"
                        :class="
                            selectedAgency === agency.name
                                ? 'border-blue-600 bg-blue-50'
                                : 'border-gray-200'
                        "
                    >
                        <input
                            type="radio"
                            v-model="selectedAgency"
                            :ref="setFieldRef('agency_name')"
                            @update:modelValue="
                                emit('clear-error', 'agency_name')
                            "
                            :value="agency.name"
                        />

                        <div>
                            <p class="font-semibold">
                                {{ agency.name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ agency.description }}
                            </p>
                            <span
                                v-if="props.errors?.agency_name"
                                class="text-sm text-red-500"
                            >
                                {{ props.errors.agency_name }}
                            </span>
                        </div>
                    </label>

                    <button
                        v-if="page < lastPage"
                        @click="loadMoreAgencies"
                        class="w-full py-2 border rounded-lg text-blue-600"
                    >
                        {{ loading ? "Loading..." : "Load more" }}
                    </button>
                </div>

                <div v-else class="space-y-3">
                    <LabelInput
                        v-model="checkout.agency.name"
                        :ref="setFieldRef('agency_name')"
                        @update:modelValue="emit('clear-error', 'agency_name')"
                        label="Agency Name"
                        :error="props.errors?.agency_name"
                    />

                    <LabelInput
                        v-model="checkout.agency.description"
                        :ref="setFieldRef('agency_description')"
                        @update:modelValue="
                            emit('clear-error', 'agency_description')
                        "
                        label="Description"
                        :error="props.errors?.agency_description"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
