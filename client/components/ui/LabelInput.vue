<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    label: {
        type: String,
        default: "",
    },
    type: {
        type: String,
        default: "text",
    },
    placeholder: {
        type: String,
        default: "",
    },
    error: {
        type: String,
        default: "",
    },
    inputClass: {
        type: String,
        default: "w-full border rounded-lg px-4 py-2 outline-none transition",
    },
});

const emit = defineEmits(["update:modelValue"]);

const showPassword = ref(false);

const inputType = computed(() => {
    if (props.type === "password") {
        return showPassword.value ? "text" : "password";
    }

    return props.type;
});

const value = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});
</script>

<template>
    <div>
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700 mb-1"
        >
            {{ label }}
        </label>

        <div class="relative">
            <input
                v-bind="$attrs"
                v-model="value"
                :type="inputType"
                :placeholder="placeholder"
                :class="[
                    inputClass,
                    type === 'password' ? 'pr-10' : '',
                    error
                        ? 'border-red-500 focus:ring-1 focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500',
                ]"
            />

            <button
                v-if="type === 'password'"
                type="button"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                @mousedown.prevent
                @click="showPassword = !showPassword"
            >
                <span v-if="showPassword">🙈</span>
                <span v-else>👁️</span>
            </button>
        </div>

        <p v-if="error" class="mt-1 text-xs text-red-500">
            {{ error }}
        </p>
    </div>
</template>
