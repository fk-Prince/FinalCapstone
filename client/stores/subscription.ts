
import { defineStore } from "pinia";
import { type SubscriptionRequest } from "~/api/subscription/SubscriptionService";

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

export const useSubscriptionCheckout = defineStore("subscriptionCheckout", {
    state: (): Subscription => ({
        plans: [],
        selectedPlan: null,
        selectedInterval: "",
        payment_method: "CREDIT-CARD",
        branch: {
            name: "",
            contact_number: "",
            image: null,
            street: "",
            description: "",
            city: "",
            province: "",
            country: "",
            lat: 0,
            lng: 0,
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
        },
        errors: {},
        subscriptionPayload: null,
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

        setSelectedPlan(plan: any) {
            this.selectedPlan = plan;
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
                lng: 0,
            } as Agency;
        },

        clearAllErrors() {
            this.errors = {};
        },

        reset() {
            this.selectedPlan = null;
            this.selectedInterval = "";
            this.branch = {
                name: "",
                contact_number: "",
                image: null,
                street: "",
                description: "",
                city: "",
                province: "",
                country: "",
                lat: 0,
                lng: 0,
            } as Branch;

            this.agency = {
                id: undefined,
                name: "",
                description: "",
                street: "",
                city: "",
                province: "",
                country: "",
                lat: 0,
                lng: 0,
            } as Agency;

            this.settings = {
                opening: "12:00 AM",
                closing: "12:00 AM",
                currency: "PHP",
            };

            this.errors = {};
            this.subscriptionPayload = null;
        },
    },
});