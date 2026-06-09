<template>
    <div class="max-w-3xl mx-auto px-3 flex flex-col gap-5">
        <Combobox
            v-model="selectedCurrency"
            :items="currencies"
            placeholder="Select currency"
            class="w-full"
            label="Currency"
        />

        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-1 text-slate-700">
                Business Hours
            </label>

            <div class="grid grid-cols-2 gap-4">
                <Combobox
                    v-model="opening"
                    :items="timeItems"
                    required
                    placeholder="Opening time"
                    class="w-full"
                />

                <Combobox
                    v-model="closing"
                    :items="timeItems"
                    required
                    placeholder="Closing time"
                    class="w-full"
                />
            </div>
        </div>
        <!-- 
        <div class="flex flex-col gap-1">
            <LabelInput
                v-model="additionalPayment"
                label="Additional Payment Online"
                type="number"
                min="0"
                @blur="validateAdditionalPayment"
            />
            <p v-if="additionalPaymentError" class="text-xs text-red-500">
                {{ additionalPaymentError }}
            </p>
        </div> -->
    </div>
</template>
<script setup lang="ts">
import { ref, computed, watch } from "vue";
import Combobox from "../ui/Combobox.vue";
import { Currency } from "~/utils/currency";
import { generateAmPmTimes } from "~/utils/time";
import LabelInput from "../ui/BaseInput.vue";

const props = defineProps<{
    setting: any;
}>();

const times = generateAmPmTimes();
const currencyList = Currency();

const currencies = ref(currencyList);
const selectedCurrency = ref(
    props.setting?.currency ?? currencyList[0]?.value ?? "",
);
const opening = ref(props.setting?.opening ?? times[0] ?? "");
const closing = ref(props.setting?.closing ?? times[0] ?? "");
const additionalPayment = ref(props.setting?.additional_payment ?? "0.00");
const additionalPaymentError = ref("");

const timeItems = computed(() =>
    times.map((t) => ({
        label: t,
        value: t,
    })),
);

props.setting.currency = selectedCurrency.value;
props.setting.opening = opening.value;
props.setting.closing = closing.value;
// props.setting.additional_payment = additionalPayment.value;

// const validateAdditionalPayment = () => {
//     const val = Number(additionalPayment.value);
//     if (additionalPayment.value === "" || additionalPayment.value === null) {
//         additionalPaymentError.value = "Additional payment is required.";
//     } else if (val < 0) {
//         additionalPaymentError.value =
//             "Additional payment must be 0 or greater.";
//     } else {
//         additionalPaymentError.value = "";
//     }
// };

watch(selectedCurrency, (val) => (props.setting.currency = val));
watch(opening, (val) => (props.setting.opening = val));
watch(closing, (val) => (props.setting.closing = val));
// watch(additionalPayment, (val) => {
//     validateAdditionalPayment();
//     props.setting.additional_payment = val;
// });
</script>
