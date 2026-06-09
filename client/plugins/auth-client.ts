import { authService } from "~/api/auth/AuthService";
import { useAuthUser, useAuthReady, resetAuth } from "~/composables/useAuthUser";

export default defineNuxtPlugin(async () => {
    const user = useAuthUser();
    const ready = useAuthReady();

    if (import.meta.server) {
        ready.value = true;
        return;
    }
    const hasSession = localStorage.getItem("auth");
    if (!hasSession) {
        ready.value = true;
        return;
    }

    try {
        const res = await authService.me();
        user.value = res;
    } catch (err) {
        resetAuth();
    } finally {
        ready.value = true;
    }
    return;
})
