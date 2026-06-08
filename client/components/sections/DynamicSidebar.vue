<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 z-[60] md:hidden bg-black/50 backdrop-blur-sm"
                @click="$emit('close')"
            />
        </Transition>

        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-300 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div
                v-if="open"
                class="fixed left-0 top-0 h-full w-72 bg-white shadow-2xl z-[70] md:hidden flex flex-col"
            >
                <div
                    class="flex items-center justify-between px-5 h-[90px] border-b shrink-0"
                >
                    <NuxtLink
                        to="/"
                        class="flex items-center"
                        @click="$emit('close')"
                    >
                        <img
                            :src="logo"
                            alt="Logo"
                            class="w-[150px] object-contain"
                        />
                    </NuxtLink>
                    <button
                        @click="$emit('close')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500 hover:text-gray-900"
                        aria-label="Close menu"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="w-5 h-5"
                        >
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>

                <nav
                    class="flex flex-col px-4 py-6 gap-1 flex-1 overflow-y-auto"
                >
                    <NuxtLink
                        v-for="item in navItems"
                        :key="item.to"
                        :to="item.to"
                        class="px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors text-sm font-medium"
                        @click="$emit('close')"
                    >
                        {{ item.label }}
                    </NuxtLink>
                </nav>

                <template v-if="user">
                    <div class="px-4 pb-6 pt-2 border-t shrink-0">
                        <div class="flex items-center gap-3 px-3 py-3">
                            <div class="relative shrink-0">
                                <img
                                    :src="avatarSrc"
                                    class="w-9 h-9 rounded-full border-2 border-white shadow-sm object-cover"
                                    alt="Profile"
                                />
                                <span
                                    class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full"
                                />
                            </div>
                            <div class="flex flex-col min-w-0">
                                <p
                                    class="text-sm font-medium text-gray-800 truncate"
                                >
                                    {{ user.first_name }} {{ user.last_name }}
                                </p>
                                <p class="text-xs text-gray-400 truncate">
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="$emit('logout')"
                            class="w-full mt-1 px-3 py-2.5 text-sm text-left text-red-500 hover:bg-red-50 rounded-lg transition-colors font-medium"
                        >
                            Log out
                        </button>
                    </div>
                </template>

                <template v-else>
                    <div class="px-4 pb-6 flex flex-col gap-2 shrink-0">
                        <NuxtLink
                            to="/auth/signin"
                            class="px-4 py-3 text-center text-sm font-medium bg-primary border rounded-sm uppercase tracking-wide transition-opacity hover:opacity-80"
                            @click="$emit('close')"
                        >
                            Sign in
                        </NuxtLink>
                        <NuxtLink
                            to="/auth/signup"
                            class="px-4 py-3 text-center text-sm font-medium bg-secondary rounded-sm uppercase tracking-wide transition-opacity hover:opacity-80"
                            @click="$emit('close')"
                        >
                            Sign up
                        </NuxtLink>
                    </div>
                </template>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup lang="ts">
defineProps<{
    open: boolean;
    logo: string;
    navItems: { label: string; to: string }[];
    user:
        | { first_name: string; last_name: string; email: string }
        | null
        | undefined;
    avatarSrc: string;
}>();

defineEmits<{
    close: [];
    logout: [];
}>();
</script>
