import { defineStore } from "pinia";
import { type SubscriptionRequest } from '~/api/subscription/SubscriptionService';

export interface Subscription {
    plans: any[];
    selectedPlan: any;
    selectedInterval: "" | "monthly" | "yearly";
    payment_method: string;
    branch: Branch;
    agency: Agency;
    errors?: any;
    subscriptionPayload?: SubscriptionRequest | null;
    settings?: any;
}

export const useSubscriptionCheckout = defineStore(
    "subscriptionCheckout",
    {
        state: (): Subscription => ({
            plans: [] as any[],
            selectedPlan: null as any,
            selectedInterval: "monthly",
            payment_method: "CREDIT-CARD",
            branch: {
                name: "AMUMA",
                contact_number: "09771171913",
                image: null as File | null,
                street: "Roxas Avenue",
                description: "Homecare Facility",
                city: "Davao City",
                province: "Davao del Sur",
                country: "PH",
                lat: 0,
                lng: 0
            } as Branch,
            agency: {
                id: null as number | null | undefined,
                name: "",
                description: "",
                street: "",
                city: "",
                province: "",
                country: "",
                lat: 0,
                lng: 0
            } as Agency,
            settings: {
                opening: "12:00 AM",
                closing: "12:00 AM",
                currency: "PHP",
                // additional_payment: "0.00",
            },
            errors: {},
            subscriptionPayload: null as SubscriptionRequest | null,
        }),


        getters: {
            selectedPrice: (state) => {
                if (!state.selectedPlan) return null;

                return state.selectedInterval === "yearly"
                    ? state.selectedPlan.yearly_price?.price
                    : state.selectedPlan.monthly_price?.price;
            },
        },

        actions: {
            setPlans(plans: any[]) {
                this.plans = plans;
                if (plans.length > 0 && !this.selectedPlan) {
                    this.selectedPlan = plans[0];
                }
            },

            setErrors(errors: Record<string, string>) {
                this.errors = errors;
            },

            clearError(field: string) {
                delete this.errors[field];
            },

            clearAgency() {
                this.agency = {
                    id: undefined,
                    name: "",
                    description: "",
                    street: "",
                    city: "",
                    province: "",
                    country: "",
                    lat: 0,
                    lng: 0
                };
            },

            clearAllErrors() {
                this.errors = {};
            },

            reset() {
                this.selectedPlan = null;
                this.selectedInterval = "monthly";
                this.payment_method = "CREDIT-CARD";
            },

        },
    }
);