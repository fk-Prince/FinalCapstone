import { useAuthUser } from "~/composables/useAuthUser";

const AUTH_ROUTES = ["/auth/signin", "/auth/signup"];
export default defineNuxtRouteMiddleware(async (to) => {
    const user = useAuthUser();

    if (import.meta.server) return;


    const isAuthRoute = AUTH_ROUTES.includes(to.path);
    const isAuthenticated = !!user.value;

    if (!isAuthenticated && !isAuthRoute) {
        return await navigateTo({ path: "/auth/signin", query: { redirect: to.fullPath } });
    }

    if (isAuthenticated && isAuthRoute) {
        return await navigateTo("/");
    }
});