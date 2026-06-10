import type { Ref } from "vue";

export interface Alert {
    show: boolean;
    type: "success" | "error" | "info";
    message: string;
}

export function showAlert(
    alert: Ref<Alert>,
    type: Alert["type"],
    message: string,
    duration = 5000
) {
    alert.value = {
        show: true,
        type,
        message,
    };

    if (duration > 0) {
        setTimeout(() => {
            alert.value.show = false;
        }, duration);
    }
}