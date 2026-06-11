<template>
    <ClientOnly>
        <BaseDropdownMenu align="right" width="w-56">
            <template #trigger="{ toggle, open }">
                <button
                    @click="toggle"
                    class="flex items-center gap-2.5 px-2 py-1.5 rounded-xl hover:bg-gray-100 transition-colors focus:outline-none"
                >
                    <div class="relative">
                        <img
                            :src="avatar"
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
                        <span class="text-sm font-medium text-gray-800">
                            {{ user.first_name }} {{ user.last_name }}
                        </span>
                        <span class="text-xs text-gray-400">View profile</span>
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
                        :src="avatar"
                        class="w-10 h-10 rounded-full border object-cover"
                        alt="Profile"
                    />
                    <div class="flex flex-col min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ user.first_name }} {{ user.last_name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ user.email }}
                        </p>
                    </div>
                </div>

                <div class="py-1">
                    <DropdownItem
                        v-for="item in menuItems"
                        :key="item.label"
                        :icon="item.icon"
                        :label="item.label"
                        @click="
                            () => {
                                navigateTo(item.to);
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
    </ClientOnly>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { navigateTo } from "#imports";
import BaseDropdownMenu from "../ui/BaseDropdownMenu.vue";
import DropdownDivider from "../ui/DropdownDivider.vue";
import DropdownItem from "../ui/DropdownItem.vue";
import ChevronIcon from "../icons/dropdown.vue";
import { authService } from "~/api/auth/AuthService.js";
import { resetAuth } from "~/composables/useAuthUser";
import { useToast } from "~/composables/useToast";
import { userInitials, avatarSrc } from "~/utils/user";

const props = defineProps<{
    user: {
        first_name: string;
        last_name: string;
        email: string;
        avatar?: string;
    };
}>();

const { success, error } = useToast();

const menuItems = [
    { icon: "user", label: "My profile", to: "/" },
    { icon: "user", label: "Dashboard", to: "/app/dashboard" },
    { icon: "settings", label: "Settings", to: "/" },
];

const avatar = computed(() => {
    const initials = userInitials(props.user);
    return props.user?.avatar || avatarSrc(initials);
});

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
</script>
