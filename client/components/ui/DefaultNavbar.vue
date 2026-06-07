<template>
    <ClientOnly>
        <header :class="header">
            <nav
                class="relative mx-auto flex h-full max-w-[115rem] items-center px-6"
            >
                <NuxtLink to="/" class="shrink-0 z-10">
                    <img
                        :src="logoAmuma"
                        alt="AMUMA logo"
                        class="md:w-[250px] w-[170px] object-contain"
                    />
                </NuxtLink>

                <div
                    v-if="variant === 'full'"
                    class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center gap-[3rem] text-sm text-gray-600"
                >
                    <NuxtLink
                        v-for="item in navItems"
                        :key="item.to"
                        :to="item.to"
                        class="hover:text-gray-900 transition"
                    >
                        {{ item.label }}
                    </NuxtLink>
                    <div v-if="!user" class="flex items-center gap-2">
                        <NuxtLink
                            to="/auth/signin"
                            class="px-7 py-2 text-black uppercase bg-primary border rounded-sm"
                        >
                            Sign in
                        </NuxtLink>
                        <NuxtLink
                            to="/auth/signup"
                            class="px-7 py-2 text-black uppercase bg-secondary border rounded-sm"
                        >
                            Sign up
                        </NuxtLink>
                    </div>
                </div>

                <div class="flex items-center gap-4 ml-auto z-10">
                    <div v-if="user" class="flex items-center gap-3">
                        <BaseDropdownMenu align="right" width="w-56">
                            <template #trigger="{ toggle, open }">
                                <button
                                    @click="toggle"
                                    class="flex items-center gap-2.5 px-2 py-1.5 rounded-xl hover:bg-gray-100 transition-colors focus:outline-none"
                                >
                                    <div class="relative">
                                        <img
                                            :src="avatarSrc"
                                            class="w-9 h-9 rounded-full border-2 border-white shadow-sm object-cover"
                                            alt="Profile"
                                        />
                                        <span
                                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full"
                                        />
                                    </div>
                                    <div
                                        class="hidden md:flex flex-col items-start leading-tight"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-800"
                                        >
                                            {{ user.first_name }}
                                            {{ user.last_name }}
                                        </span>
                                        <span class="text-xs text-gray-400"
                                            >View profile</span
                                        >
                                    </div>
                                    <ChevronIcon
                                        :isOpen="open"
                                        class="text-gray-400 w-4 h-4 hidden md:block"
                                    />
                                </button>
                            </template>

                            <template #default="{ close }">
                                <div
                                    class="px-4 py-3 border-b border-gray-100 flex items-center gap-3"
                                >
                                    <img
                                        :src="avatarSrc"
                                        class="w-10 h-10 rounded-full border object-cover shrink-0"
                                        alt="Profile"
                                    />
                                    <div class="flex flex-col min-w-0">
                                        <p
                                            class="text-sm font-medium text-gray-800 truncate"
                                        >
                                            {{ user.first_name }}
                                            {{ user.last_name }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-400 truncate"
                                        >
                                            {{ user.email }}
                                        </p>
                                    </div>
                                </div>

                                <div class="py-1">
                                    <DropdownItem
                                        icon="user"
                                        label="My profile"
                                        @click="
                                            () => {
                                                navigateTo('/profile');
                                                close();
                                            }
                                        "
                                    />
                                    <DropdownItem
                                        icon="settings"
                                        label="Settings"
                                        @click="
                                            () => {
                                                navigateTo('/settings');
                                                close();
                                            }
                                        "
                                    />
                                </div>

                                <DropdownDivider />

                                <div class="py-1">
                                    <DropdownItem
                                        icon="logout"
                                        label="Log out"
                                        danger
                                        @click="
                                            () => {
                                                logout();
                                                close();
                                            }
                                        "
                                    />
                                </div>
                            </template>
                        </BaseDropdownMenu>
                    </div>

                    <button
                        class="md:hidden flex items-center justify-center w-10 h-10"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-6 h-6 text-black"
                        >
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                </div>
            </nav>
        </header>
    </ClientOnly>
</template>

<script setup lang="ts">
import logoAmuma from "~/assets/logo/logoAmuma.png";
import { useAuthUser } from "~/composables/useAuthUser";
import BaseDropdownMenu from "./BaseDropdownMenu.vue";
import DropdownDivider from "./DropdownDivider.vue";
import DropdownItem from "./DropdownItem.vue";
import ChevronIcon from "../icons/dropdown.vue";
import { authService } from "~/api/auth/AuthService.js";
import { useToast } from "~/composables/useToast";

const { success, error } = useToast();
const route = useRoute();
const user = useAuthUser();

const initials = computed(() => {
    const u = user.value;
    const first = u?.first_name?.[0] ?? "";
    const last = u?.last_name?.[0] ?? "";
    return (first + last).toUpperCase();
});

const avatarSrc = computed(
    () =>
        `https://ui-avatars.com/api/?name=${initials.value}&background=random&color=fff&bold=true`,
);

const logout = async () => {
    try {
        const res = await authService.logout();
        success(res.message);
        resetAuth();
        await navigateTo("/auth/signin");
    } catch (err: any) {
        console.error(err);
        error(err);
    }
};

type NavItem = { label: string; to: string };

const props = withDefaults(defineProps<{ navItems?: NavItem[] }>(), {
    navItems: () => [
        { label: "Pricing", to: "/pricing" },
        { label: "Booking", to: "/booking" },
        { label: "Docs", to: "/docs" },
        { label: "Company", to: "/company" },
    ],
});

const variant = computed(() => route.meta.navVariant ?? "full");
const header = computed(
    () => route.meta.navHeaderClass ?? "w-full h-[90px] bg-white",
);
</script>
