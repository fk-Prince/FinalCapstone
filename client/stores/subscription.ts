import { defineStore } from "pinia";

export const branchFields = [
    { key: "name", label: "Branch Name" },
    { key: "contact_number", label: "Contact Number" },
    { key: "street", label: "Street" },
    { key: "city", label: "City" },
    { key: "province", label: "Province" },
    { key: "postal_code", label: "Postal Code" },
    { key: "country", label: "Country" },
];

export const agencyFields = [
    { key: "name", label: "Agency Name" },
    { key: "description", label: "Description" },
];

export interface Subscription {
    plans: any[];
    selectedPlan: any;
    selectedInterval: "monthly" | "yearly";
    payment_method: string;
    branch: Branch;
    agency: Agency;
}

export const useSubscriptionCheckout = defineStore(
    "subscriptionCheckout",
    {
        state: (): Subscription => ({
            plans: [] as any[],
            selectedPlan: null as any,
            selectedInterval: "monthly" as "monthly" | "yearly",
            payment_method: "CREDIT-CARD",
            branch: {
                name: "Obrero Main Branch",
                contact_number: "+63 917 123 4567",
                image: null as File | null,
                street: "Palma Gil Street",
                postal_code: "8000",
                city: "Davao City",
                province: "Davao del Sur",
                country: "Philippines",
            } as Branch,
            agency: {
                name: "",
                description: "",
                street: "",
                postal_code: "",
                city: "",
                province: "",
                country: "",
            } as Agency,
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

            reset() {
                this.selectedPlan = null;
                this.selectedInterval = "monthly";
                this.payment_method = "CREDIT-CARD";
            },
        },

        persist: true,
    }
);