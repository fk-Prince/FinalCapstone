<template>
    <div class="flex flex-col gap-1.5 font-primary">
        <label v-if="label" class="text-sm font-semibold text-slate-700">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <div
            class="flex items-center border-[1.5px] rounded-xl bg-white overflow-hidden transition"
            :class="[
                error
                    ? 'border-red-400 focus-within:ring-red-500/15'
                    : 'border-slate-200 focus-within:border-blue-500 focus-within:ring-blue-500/15',
                'focus-within:ring-2',
            ]"
        >
            <span
                v-if="hasPrefix"
                class="flex items-center pl-3.5 text-slate-400 flex-shrink-0"
            >
                <slot name="prefix" />
            </span>
            <input
                v-model="value"
                :type="inputType"
                :placeholder="placeholder"
                class="flex-1 min-w-0 px-3.5 py-2.5 text-sm text-slate-800 bg-transparent outline-none placeholder:text-slate-400"
                :class="hasPrefix ? 'pl-2' : ''"
            />
            <span v-if="hasSuffix" class="flex items-center pr-1 flex-shrink-0">
                <slot name="suffix" />
            </span>
        </div>

        <p v-if="error" class="text-xs text-red-500 mt-0.5">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from "vue";

defineOptions({ name: "BaseInput" });

const props = defineProps({
    modelValue: { type: [String, Number], default: "" },
    label: { type: String, required: true },
    placeholder: String,
    error: String,
    required: { type: Boolean, default: false },
    mode: { type: String, default: "text" },
    countryCode: { type: String, default: "" },
    stateCode: { type: String, default: "" },
});

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit("update:modelValue", val),
});

const slots = useSlots();
const hasPrefix = computed(() => !!slots.prefix);
const hasSuffix = computed(() => !!slots.suffix);

const inputType = computed(() => {
    if (props.mode === "password") return "password";
    if (props.mode === "email") return "email";
    return "text";
});
</script>
