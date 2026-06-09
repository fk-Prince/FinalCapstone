export class BaseService {
    async request<T>(
        url: string,
        method: string,
        params: object = {},
    ): Promise<T> {
        const runtimeConfig = useRuntimeConfig();

        const headers: Record<string, string> = {
            Accept: "application/json",
        };

        if (typeof window !== "undefined") {
            const token = localStorage.getItem("auth");
            if (token) {
                headers.Authorization = `Bearer ${token}`;
            }
        }

        const config: any = {
            baseURL: runtimeConfig.public.backendApi,
            method,
            credentials: "include",
            headers,
        };

        if (method.toUpperCase() === "GET") {
            config.params = params;
        } else {
            config.body = params;
        }

        try {
            return await $fetch<T>(url, config);
        } catch (error: any) {
            // const status = error?.response?.status;
            // const message =
            //     error?.response?._data?.message ||
            //     error?.data?.message ||
            //     error?.message;

            // switch (status) {
            //     case 400:
            //         throw new Error(message);
            //     case 401:
            //         throw new Error(message);
            //     case 404:
            //         throw new Error(message);
            //     case 422:
            //         throw new Error(message);
            //     case 429:
            //         throw new Error(message || "Validation or Request Error");
            //     case 500:
            //         throw new Error(
            //             "Server error. Please try again or contact the administrator." + message,
            //         );
            //     default:
            //         throw new Error(message || "Something went wrong. Please try again.");
            // }
            const status = error?.response?.status;
            const data = error?.response?._data;

            const message =
                data?.message ||
                error?.message ||
                "Something went wrong";

            const payload = {
                status,
                message,
                errors: data?.errors || null,
                data: data || null,
            };

            switch (status) {
                case 400:
                    throw new Error(message);
                case 401:
                    throw new Error(message);
                case 404:
                    throw new Error(message);
                case 422:
                    throw payload;
                case 429:
                    throw new Error(message || "Validation or Request Error");
                case 500:
                    throw new Error(
                        "Server error. Please try again or contact the administrator." + message,
                    );
                default:
                    throw payload;
            }
        }
    }
}

export default BaseService;