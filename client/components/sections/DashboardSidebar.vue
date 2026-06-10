<template>
    <div>HELLOS</div>
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
