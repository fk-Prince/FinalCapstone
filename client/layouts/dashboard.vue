<template>
    <div class="min-h-screen flex flex-col bg-[#EEF3FB]">
        <DashboardSidebar
            :open="true"
            :logo="logoAmuma"
            :navItems="navItems"
            :user="user"
            :avatarSrc="avatar"
            @close="isOpen = false"
            @logout="handleLogout"
        />

        <div class="flex-1 flex flex-col min-w-0">
            <DefaultNavbar />

            <main class="flex-1 p-6 overflow-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import DefaultNavbar from "~/components/sections/DefaultNavbar.vue";
import DashboardSidebar from "~/components/sections/DashboardSidebar.vue";
import logoAmuma from "~/assets/logo/logoAmuma.png";

import { ref } from "vue";
import { useAuthUser } from "~/composables/useAuthUser";
import { userInitials, avatarSrc } from "~/utils/user";

const user = useAuthUser();

const isOpen = ref(true);

const navItems = [
    { label: "Pricing", to: "/pricing" },
    { label: "Booking", to: "/booking" },
    { label: "Docs", to: "/docs" },
    { label: "Company", to: "/company" },
];

const logout = () => {};

const handleLogout = () => {
    logout();
    isOpen.value = false;
};

const initials = userInitials(user);
const avatar = avatarSrc(initials);
</script>
