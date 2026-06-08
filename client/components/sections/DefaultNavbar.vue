<template>
    <ClientOnly>
        <header :class="header">
            <nav
                class="mx-auto grid h-full w-full grid-cols-2 md:grid-cols-3 items-center px-6"
            >
                <div class="flex justify-start">
                    <NuxtLink to="/" class="flex items-center">
                        <img
                            :src="logoAmuma"
                            alt="AMUMA logo"
                            class="w-[170px] md:w-[250px] object-contain"
                        />
                    </NuxtLink>
                </div>

                <div
                    v-if="variant === 'full'"
                    class="hidden md:flex justify-center items-center gap-12 text-sm text-gray-600"
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

                <div class="flex justify-end items-center gap-4">
                    <template v-if="!user">
                        <div
                            v-if="variant === 'full'"
                            class="hidden md:flex items-center gap-2"
                        >
                            <NuxtLink
                                to="/auth/signin"
                                class="px-7 py-2 text-black uppercase bg-primary border rounded-sm"
                            >
                                Sign in
                            </NuxtLink>
                            <NuxtLink
                                to="/auth/signup"
                                class="px-7 py-2 text-black uppercase bg-secondary rounded-sm"
                            >
                                Sign up
                            </NuxtLink>
                        </div>
                    </template>

                    <template v-else>
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
                                            >{{ user.first_name }}
                                            {{ user.last_name }}</span
                                        >
                                        <span class="text-xs text-gray-400"
                                            >View profile</span
                                        >
                                    </div>
                                    <ChevronIcon
                                        :isOpen="open"
                                        class="hidden md:block w-4 h-4 text-gray-400"
                                    />
                                </button>
                            </template>

                            <template #default="{ close }">
                                <div
                                    class="px-4 py-3 border-b border-gray-100 flex items-center gap-3"
                                >
                                    <img
                                        :src="avatarSrc"
                                        class="w-10 h-10 rounded-full border object-cover"
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
                    </template>

                    <button
                        @click="mobileMenuOpen = true"
                        class="flex md:hidden items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors"
                        aria-label="Open menu"
                    >
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
            </nav>

            <DynamicSidebar
                :open="mobileMenuOpen"
                :logo="logoAmuma"
                :navItems="navItems"
                :user="user"
                :avatarSrc="avatarSrc"
                @close="mobileMenuOpen = false"
                @logout="
                    () => {
                        logout();
                        mobileMenuOpen = false;
                    }
                "
            />
        </header>
    </ClientOnly>
</template>

<script setup lang="ts">
import logoAmuma from "~/assets/logo/logoAmuma.png";
import { useAuthUser } from "~/composables/useAuthUser";
import BaseDropdownMenu from "../ui/BaseDropdownMenu.vue";
import DropdownDivider from "../ui/DropdownDivider.vue";
import DropdownItem from "../ui/DropdownItem.vue";
import ChevronIcon from "../icons/dropdown.vue";
import { ref, computed } from "vue";
import DynamicSidebar from "./DynamicSidebar.vue";
import { useRoute, navigateTo } from "#imports";
import { authService } from "~/api/auth/AuthService.js";
import { useToast } from "~/composables/useToast";

const { success, error } = useToast();
const user = useAuthUser();
const route = useRoute();

const mobileMenuOpen = ref(false);

const props = withDefaults(
    defineProps<{ navItems?: { label: string; to: string }[] }>(),
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

const initials = computed(() => {
    const u = user.value;
    return (
        (u?.first_name?.[0] ?? "") + (u?.last_name?.[0] ?? "")
    ).toUpperCase();
});

const avatarSrc = computed(
    () =>
        `https://ui-avatars.com/api/?name=${initials.value}&background=random&color=fff`,
);
</script>
