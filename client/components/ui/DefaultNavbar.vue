<template>
    <header :class="header">
        <nav
            class="mx-auto flex h-full max-w-[115rem] items-center justify-between px-6"
        >
            <NuxtLink to="/" class="shrink-0">
                <img
                    :src="logoAmuma"
                    alt="AMUMA logo"
                    class="w-[250px] object-contain"
                />
            </NuxtLink>

            <template v-if="variant === 'full'">
                <div
                    class="md:flex items-center flex gap-[3rem] text-sm text-gray-600"
                >
                    <NuxtLink
                        v-for="item in navItems"
                        :key="item.to"
                        :to="item.to"
                        class="hover:text-gray-900 transition"
                    >
                        {{ item.label }}
                    </NuxtLink>
                </div>

                <div class="flex items-center gap-4">
                    <div v-if="!user">
                        <NuxtLink
                            to="/auth/signin"
                            class="px-7 py-2 text-black uppercase bg-primary border rounded-sm"
                        >
                            SIGN IN
                        </NuxtLink>

                        <NuxtLink
                            to="/auth/signup"
                            class="px-7 py-2 text-black uppercase bg-secondary border rounded-sm"
                        >
                            SIGN UP
                        </NuxtLink>
                    </div>

                    <div v-else>
                        <NuxtLink class="flex items-center gap-2">
                            <img
                                :src="user?.avatar || '/default-avatar.png'"
                                class="w-9 h-9 rounded-full object-cover border"
                                alt="Profile"
                            />
                        </NuxtLink>
                    </div>
                </div>
            </template>
        </nav>
    </header>
</template>

<script setup lang="ts">
import logoAmuma from "~/assets/logo/logoAmuma.png";
import { useAuthUser, resetAuth } from "~/composables/useAuthUser";

const route = useRoute();

const user = useAuthUser();

type NavItem = { label: string; to: string };
type CTA = { label: string; to: string };

const props = withDefaults(
    defineProps<{
        navItems?: NavItem[];
        cta?: CTA;
    }>(),
    {
        navItems: () => [
            { label: "Pricing", to: "/pricing" },
            { label: "Booking", to: "/booking" },
            { label: "Docs", to: "/docs" },
            { label: "Company", to: "/company" },
        ],
    },
);

const variant = computed(() => route.meta.navVariant ?? "full");

const header = computed(
    () => route.meta.navHeaderClass ?? "w-full h-[90px] bg-white",
);
</script>
