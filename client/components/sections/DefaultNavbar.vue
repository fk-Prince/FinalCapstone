<template>
    <header :class="header">
        <nav class="flex justify-between items-center px-6 h-[90px]">
            <NuxtLink to="/">
                <img
                    :src="logoAmuma"
                    alt="AMUMA logo"
                    class="w-[170px] md:w-[250px] object-contain"
                />
            </NuxtLink>

            <div v-if="variant === 'full'" class="hidden md:flex gap-10">
                <NuxtLink v-for="i in navItems" :key="i.to" :to="i.to">
                    {{ i.label }}
                </NuxtLink>
            </div>

            <div v-if="variant === 'full'" class="flex items-center gap-3">
                <div v-if="!hydrated" class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 bg-gray-200 rounded-full animate-pulse"
                    />
                    <div class="flex flex-col gap-2">
                        <div
                            class="w-24 h-3 bg-gray-200 rounded animate-pulse"
                        />
                        <div
                            class="w-14 h-3 bg-gray-200 rounded animate-pulse"
                        />
                    </div>
                </div>

                <div v-else class="flex items-center gap-3">
                    <NuxtLink v-if="!user" to="/auth/signin">Sign in</NuxtLink>
                    <NuxtLink v-if="!user" to="/auth/signup">Sign up</NuxtLink>
                    <NavbarProfileDropdown v-if="user" :user="user" />
                    <button class="md:hidden" @click="mobileMenuOpen = true">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="w-6 h-6"
                        >
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <ClientOnly>
            <DynamicSidebar
                :open="mobileMenuOpen"
                :logo="logoAmuma"
                :navItems="navItems"
                :user="user"
                :avatarSrc="avatarUrl"
                @close="mobileMenuOpen = false"
            />
        </ClientOnly>
    </header>
</template>
<script setup lang="ts">
import logoAmuma from "~/assets/logo/logoAmuma.png";
import NavbarProfileDropdown from "../ui/NavbarProfileDropdown.vue";
import DynamicSidebar from "./DynamicSidebar.vue";
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useAuthUser } from "~/composables/useAuthUser";
import { userInitials, avatarSrc } from "~/utils/user";

const user = useAuthUser();
const route = useRoute();
const hydrated = ref(false);

onMounted(() => {
    hydrated.value = true;
});

const mobileMenuOpen = ref(false);

const props = withDefaults(
    defineProps<{ navItems?: { label: string; to: string }[] }>(),
    {
        navItems: () => [
            { label: "Pricing", to: "/pricing" },
            { label: "Booking", to: "/" },
            { label: "Docs", to: "/" },
            { label: "Company", to: "/" },
            // { label: "Pricing", to: "/pricing" },
            // { label: "Booking", to: "/booking" },
            // { label: "Docs", to: "/docs" },
            // { label: "Company", to: "/company" },
        ],
    },
);

const initials = computed(() => userInitials(user));
const avatarUrl = computed(() => avatarSrc(initials.value));

const variant = computed(() => route.meta.navVariant ?? "full");
const header = computed(
    () => route.meta.navHeaderClass ?? "w-full h-[90px] bg-white border-b",
);
</script>
