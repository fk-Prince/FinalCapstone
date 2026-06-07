<template>
    <div class="max-w-4xl mx-auto p-6 space-y-4">
        <div
            v-if="mode === 'choice'"
            class="grid grid-cols-1 md:grid-cols-2 gap-4"
        >
            <button
                class="flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-lg hover:border-blue-500 hover:bg-blue-50 transition"
                @click="mode = 'new'"
            >
                <span class="text-3xl font-bold text-blue-600">+</span>
                <span class="mt-2 font-medium">Create New Agency</span>
                <span class="text-sm text-slate-500"
                    >Register a new agency</span
                >
            </button>

            <button
                class="flex flex-col items-center justify-center p-6 border rounded-lg hover:border-green-500 hover:bg-green-50 transition"
                @click="mode = 'existing'"
            >
                <span class="text-3xl text-green-600">🏢</span>
                <span class="mt-2 font-medium">Select Existing Agency</span>
                <span class="text-sm text-slate-500">Use existing agency</span>
            </button>
        </div>

        <div v-else-if="mode === 'new'" class="space-y-4">
            <button
                class="text-sm text-primary w-fit"
                @click="
                    () => {
                        mode = 'choice';
                        resetAgencies();
                        clearAgency();
                    }
                "
            >
                ← Back
            </button>

            <LabelInput
                v-model="checkout.agency.name"
                label="Agency Name"
                :error="agencyNameError"
            />
            <LabelInput
                v-model="checkout.agency.description"
                label="Description"
            />
            <LocationSelector @location-selected="handleLocation" />
        </div>

        <div v-else class="space-y-4">
            <div class="flex gap-5 flex-col">
                <button
                    class="text-sm text-primary w-fit"
                    @click="
                        () => {
                            mode = 'choice';
                            resetAgencies();
                            clearAgency();
                        }
                    "
                >
                    ← Back
                </button>

                <div class="flex justify-between">
                    <label class="text-sm font-semibold text-slate-700"
                        >Select Agency</label
                    >
                    <button
                        class="text-sm text-red-500 hover:text-red-600 w-fit"
                        @click="clearAgency"
                    >
                        Clear
                    </button>
                </div>
            </div>

            <div v-if="agencies && agencies.length > 0" class="flex flex-col">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label
                        v-for="agency in agencies"
                        :key="agency.agency_id"
                        class="flex items-start justify-between rounded-xl border p-4 cursor-pointer transition"
                        :class="
                            selectedId === agency.agency_id
                                ? 'border-primary bg-blue-50'
                                : 'border-gray-200 hover:border-gray-300'
                        "
                        @click.prevent="selectAgency(agency)"
                    >
                        <div class="flex items-start gap-3">
                            <input
                                type="radio"
                                class="mt-1 accent-primary"
                                :value="agency.agency_id"
                                :checked="selectedId === agency.agency_id"
                                readonly
                            />
                            <div class="space-y-1">
                                <p class="font-semibold text-gray-900">
                                    {{ agency.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ agency.description }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ formatAddress(agency) }}
                                </p>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="w-full flex justify-center mt-2">
                    <button
                        v-if="!isFullyLoaded"
                        @click="loadMoreAgencies"
                        class="py-2 px-4 border rounded-lg text-primary hover:bg-primary/20 w-full"
                    >
                        {{ loading ? "Loading..." : "Show more" }}
                    </button>

                    <button
                        v-else
                        @click="resetAgencies"
                        class="py-2 px-4 border rounded-lg text-danger hover:bg-danger/20 w-full"
                    >
                        Show less
                    </button>
                </div>
            </div>

            <div v-else class="text-center py-8 text-gray-500">
                No agencies registered to you.
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import LabelInput from "../ui/BaseInput.vue";
import LocationSelector from "../ui/LocationSelector.vue";
import { useSubscriptionCheckout } from "~/stores/subscription";
import { agencyService } from "~/api/agency/AgencyService.js";

const checkout = useSubscriptionCheckout();
const mode = ref<"choice" | "new" | "existing">("choice");
const selectedId = ref<number | null>(null);

const agencyNameError = computed(() => {
    const hasLocation =
        checkout.agency.city ||
        checkout.agency.province ||
        checkout.agency.country;

    if (hasLocation && !checkout.agency.name) {
        return "Agency name is required when a location is provided.";
    }

    return "";
});

const formatAddress = (agency: any) => {
    return `${agency.locations.street}, ${agency.locations.city}, ${agency.locations.province}, ${agency.locations.country}`;
};

const page = ref(1);
const lastPage = ref(1);
const loading = ref(false);
const agencies = ref<any[]>([]);

const isFullyLoaded = computed(() => page.value >= lastPage.value);

const selectAgency = (agency: any) => {
    selectedId.value = agency.agency_id;
    checkout.agency.id = agency.agency_id;
    checkout.agency.name = agency.name;
    checkout.agency.description = agency.description;
    checkout.agency.lat = agency.locations?.lat ?? 0;
    checkout.agency.lng = agency.locations?.lng ?? 0;
    checkout.agency.street = agency.locations?.street ?? "";
    checkout.agency.city = agency.locations?.city ?? "";
    checkout.agency.province = agency.locations?.province ?? "";
    checkout.agency.country = agency.locations?.country ?? "";
};

const clearAgency = () => {
    selectedId.value = null;
    checkout.clearAgency();
};

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

const resetAgencies = async () => {
    page.value = 1;
    agencies.value = [];
    await fetchAgencies(true);
};

fetchAgencies();

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
    checkout.agency.lat = lat;
    checkout.agency.lng = lng;
    checkout.agency.street = street;
    checkout.agency.city = city;
    checkout.agency.province = province;
    checkout.agency.country = country;
};
</script>
