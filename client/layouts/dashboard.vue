<template>
    <div class="h-screen flex flex-col bg-[#EEF3FB]">
        <DashboardHeader @open="isOpen = true" />

        <div class="flex flex-1 min-h-0">
            <DynamicSidebar
                :open="isOpen"
                :logo="logoAmuma"
                :navItems="navItems"
                :user="user"
                :avatarSrc="avatar"
                @close="isOpen = false"
                :variant="2"
            />

            <main class="flex-1 p-6 overflow-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
<script setup lang="ts">
import logoAmuma from "~/assets/logo/logoAmuma.png";
import DynamicSidebar from "~/components/sections/DynamicSidebar.vue";
import { ref } from "vue";
import { useAuthUser } from "~/composables/useAuthUser";
import { userInitials, avatarSrc } from "~/utils/user";
import DashboardHeader from "~/components/sections/DashboardHeader.vue";

const user = useAuthUser();
const isOpen = ref(false);

const navItems = [
    { label: "Branches", to: "/app/branches" },
    { label: "Booking", to: "/" },
    { label: "Docs", to: "/" },
    { label: "Company", to: "/" },
];

const initials = userInitials(user);
const avatar = avatarSrc(initials);
</script>
