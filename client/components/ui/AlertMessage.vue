<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "info",
        validator: (value) => ["success", "error", "info"].includes(value),
    },
    message: {
        type: String,
        required: true,
    },
});

const styles = {
    success: "bg-green-50 border-green-500 text-green-800",
    error: "bg-red-50 border-red-500 text-red-800",
    info: "bg-blue-50 border-blue-500 text-blue-800",
};
</script>

<template>
    <div class="p-4 rounded-md" :class="styles[type]" role="alert">
        <p class="text-center mt-1">{{ message }}</p>
    </div>
</template>
