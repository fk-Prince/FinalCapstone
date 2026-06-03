<template>
    <ClientOnly>
        <div class="min-h-screen flex items-center justify-center bg-gray-100">
            <form
                class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm"
                @submit.prevent="loginAction"
            >
                <h1
                    class="text-5xl font-bold text-center text-gray-800 my-10 uppercase"
                >
                    Signin
                </h1>

                <div class="w-full flex flex-col gap-y-5">
                    <LabelInput
                        label="Email"
                        type="email"
                        v-model="signinData.email"
                    />

                    <LabelInput
                        label="Password"
                        type="password"
                        v-model="signinData.password"
                    />

                    <div class="flex justify-end">
                        <span
                            class="text-[12px] text-blue-400 hover:underline cursor-pointer"
                            >Forgot Password?</span
                        >
                    </div>

                    <div class="flex justify-center mt-5">
                        <BaseButton
                            type="submit"
                            :loading="isLoading"
                            buttonClass="w-[95%]"
                        >
                            Sign In
                        </BaseButton>
                    </div>
                </div>
            </form>
        </div>
    </ClientOnly>
</template>

<script setup lang="ts">
import { ref } from "vue";
import LabelInput from "~/components/ui/LabelInput.vue";
import BaseButton from "~/components/ui/BaseButton.vue";
import { authService } from "~/api/auth/AuthService";
import { useAuthUser } from "~/composables/useAuthUser";
import type { SigninRequest } from "~/stores/auth";

definePageMeta({
    middleware: "auth-client",
});
const route = useRoute();
const user = useAuthUser();

const isLoading = ref(false);

const signinData = ref<SigninRequest>({
    email: "prince.sestoso@gmail.com",
    password: "password",
});

const loginAction = async () => {
    isLoading.value = true;
    try {
        const res = await authService.login(signinData.value);
        user.value = res.user;
        localStorage.setItem("auth", user.value.uuid);
        alert(res.message);
        await navigateTo((route.query.redirect as string) || "/");
    } catch (err: any) {
        console.error("Login error:", err);
    } finally {
        isLoading.value = false;
    }
};
</script>
