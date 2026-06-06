<template>
    <div class="flex items-center justify-center gap-3">
        <span
            :class="[
                'text-sm font-medium transition-colors',
                !annual ? 'bg-gray-30' : 'text-gray-400',
            ]"
        >
            Monthly
        </span>

        <button
            role="switch"
            :aria-checked="annual"
            @click="$emit('update:annual', !annual)"
            :class="[
                'relative w-12 h-7 rounded-full transition-colors duration-200 bg-primary',
                // annual ? 'bg-primary' : 'bg-gray-300',
            ]"
        >
            <span
                :class="[
                    'absolute top-[3px] left-[3px] w-5 h-5 rounded-full shadow transition-transform duration-200',
                    annual
                        ? 'translate-x-5 bg-white'
                        : 'translate-x-0 bg-white',
                ]"
            />
        </button>

        <span
            :class="[
                'text-sm font-medium transition-colors flex items-center gap-2',
                annual ? 'text-gray-900' : 'text-gray-400',
            ]"
        >
            Annually
        </span>
    </div>
</template>

<script setup>
defineProps({ annual: Boolean });
defineEmits(["update:annual"]);
</script>
