import { useAuthUser } from "~/composables/useAuthUser";

export const usePermissions = () => {
    const user = useAuthUser();

    const hasRole = (...roles: string[]) => {
        return roles.includes(user.value?.role);
    };

    return {
        hasRole,
    };
};