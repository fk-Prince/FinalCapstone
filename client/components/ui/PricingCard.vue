<template>
    <ClientOnly>
        <!-- <div
            :class="[
                'relative rounded-2xl flex flex-col transition-all duration-300',
                featured
                    ? 'bg-primary text-white shadow-2xl pt-10 pb-8 px-8'
                    : 'bg-white text-secondary shadow-sm border border-muted-light p-8',
            ]"
        >
            <div
                v-if="featured"
                class="absolute -top-4 left-1/2 -translate-x-1/2 whitespace-nowrap"
            >
                <span
                    class="bg-white text-primary text-xs font-bold px-5 py-1.5 rounded-full shadow-md tracking-wide border border-primary/10"
                >
                    Most Popular
                </span>
            </div>

            <div class="mb-4">
                <span
                    :class="[
                        'text-xs font-semibold px-3 py-1 rounded-full',
                        featured
                            ? 'bg-white/20 text-white'
                            : 'bg-light text-primary',
                    ]"
                >
                    {{ planLabel }}
                </span>
            </div>
        </div> -->
    </ClientOnly>
</template>

<script setup>
// import { computed } from "vue";
// const props = defineProps({
//     planLabel: String,
//     title: String,
//     description: String,
//     price: Number,
//     annual: Boolean,
//     ctaText: String,
//     features: Array,
//     featured: { type: Boolean, default: false },
// });

// const displayPrice = computed(() =>
//     props.annual ? Math.round(props.price * 0.8) : props.price,
// );
</script>
