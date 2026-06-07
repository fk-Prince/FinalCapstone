<script setup lang="ts">
import { ref, watch } from "vue";
import BaseInput from "../ui/BaseInput.vue";
import BaseButton from "../ui/BaseButton.vue";
import OtpDialog from "../ui/OtpDialog.vue";
import { useToast } from "~/composables/useToast";
import { otpService } from "~/api/otp/OtpService";

defineOptions({ name: "SignupForm" });

const { success, error } = useToast();

/* ---------------------------
   STATE
----------------------------*/
const signupData = ref({
    email: "",
    password: "",
    confirmPassword: "",
});

const errors = ref({
    email: "",
    password: "",
    confirmPassword: "",
});

const showPassword = ref(false);
const loading = ref(false);
const otpLoading = ref(false);
const showOtpDialog = ref(false);

/* ---------------------------
   WATCHERS
----------------------------*/
watch(
    () => signupData.value.email,
    () => {
        errors.value.email = "";
    },
);

watch(
    () => [signupData.value.password, signupData.value.confirmPassword],
    ([password, confirmPassword]) => {
        errors.value.password = "";

        if (!confirmPassword) {
            errors.value.confirmPassword = "";
            return;
        }

        errors.value.confirmPassword =
            password === confirmPassword ? "" : "Passwords do not match.";
    },
);

async function handleSignUp() {
    errors.value = {
        email: "",
        password: "",
        confirmPassword: "",
    };

    if (!signupData.value.email) {
        errors.value.email = "Email is required.";
    }

    if (!signupData.value.password) {
        errors.value.password = "Password is required.";
    }

    if (!signupData.value.confirmPassword) {
        errors.value.confirmPassword = "Please confirm your password.";
    }

    if (
        signupData.value.password &&
        signupData.value.confirmPassword &&
        signupData.value.password !== signupData.value.confirmPassword
    ) {
        errors.value.confirmPassword = "Passwords do not match.";
    }

    if (
        errors.value.email ||
        errors.value.password ||
        errors.value.confirmPassword
    ) {
        return;
    }

    loading.value = true;

    try {
        const res = await otpService.createOtp({
            email: signupData.value.email,
        });
        console.log(res);

        if (res?.otp_key) {
            localStorage.setItem("otp_key", res.otp_key);
        }

        showOtpDialog.value = true;
        success("OTP sent to your email.");
    } catch (err: any) {
        error(err?.message || "Failed to send OTP.");
    } finally {
        loading.value = false;
    }
}

async function verifyOtp(code: string) {
    otpLoading.value = true;

    try {
        const otp_key = localStorage.getItem("otp_key");

        if (!otp_key) {
            otpLoading.value = false;
            error("OTP session expired. Please try again.");
            return;
        }

        const res = await otpService.validateOtp({
            otp_key,
            user: {
                email: signupData.value.email,
                password: signupData.value.password,
            },
            otp_value: code,
        });

        success(res.message);

        localStorage.removeItem("otp_key");

        showOtpDialog.value = false;

        await navigateTo("/auth/signin");
    } catch (err: any) {
        error(err?.message || "Invalid OTP.");
    } finally {
        otpLoading.value = false;
    }
}

// async function resendOtp() {
//     try {
//         const res = await otpService.createOtp({
//             email: signupData.value.email,
//         });

//         if (res?.key) {
//             localStorage.setItem("otp_key", res.key);
//         }

//         success("OTP resent.");
//     } catch (err: any) {
//         error(err?.message || "Failed to resend OTP.");
//     }
// }
</script>

<template>
    <div
        class="w-full max-w-[460px] rounded-2xl bg-white px-10 py-11 shadow-xl"
    >
        <!-- @resend="resendOtp" -->
        <OtpDialog
            v-if="showOtpDialog"
            :loading="otpLoading"
            @verify="verifyOtp"
            @close="showOtpDialog = false"
        />

        <div class="mb-10 text-center">
            <h2 class="text-[1.85rem] font-extrabold text-slate-900">
                Create an Account
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Join
                <span class="font-semibold text-blue-600">AMUMA</span> today
            </p>
        </div>

        <div class="flex flex-col gap-5">
            <BaseInput
                v-model="signupData.email"
                label="Email"
                placeholder="Enter your email address"
                mode="email"
                :error="errors.email"
            />

            <BaseInput
                v-model="signupData.password"
                label="Password"
                placeholder="Create a password"
                :mode="showPassword ? 'text' : 'password'"
                :error="errors.password"
            />

            <BaseInput
                v-model="signupData.confirmPassword"
                label="Confirm Password"
                placeholder="Confirm your password"
                :mode="showPassword ? 'text' : 'password'"
                :error="errors.confirmPassword"
            />

            <BaseButton
                type="submit"
                variant="primary"
                size="lg"
                :full="true"
                :loading="loading"
                class="mt-3"
                @click="handleSignUp"
            >
                {{ loading ? "Sign Up..." : "Sign Up" }}
            </BaseButton>

            <p class="mt-2 text-center text-sm text-slate-500">
                Already have an account?
                <NuxtLink
                    to="/auth/signin"
                    class="font-semibold text-blue-600 hover:underline"
                >
                    Sign in
                </NuxtLink>
            </p>
        </div>
    </div>
</template>
