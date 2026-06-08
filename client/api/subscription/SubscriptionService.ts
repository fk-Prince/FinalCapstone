import BaseService from '~/api/BaseService';

export interface SubscriptionRequest {
    token_id?: string;
    authentication_id?: string;
    plan_code: string;
    billing_interval: string;
    payment_method: string;

    branch_name: string;
    branch_description?: string;
    branch_street: string;
    branch_city: string;
    branch_province: string;
    branch_country: string;
    branch_contact_number?: string;
    branch_image?: File | null;
    branch_settings?: any;
    branch_latitude?: number | null;
    branch_longitude?: number | null;

    agency_id?: number;
    agency_name?: string;
    agency_description?: string;
    agency_street?: string;
    agency_city?: string;
    agency_province?: string;
    agency_country?: string;
    agency_latitude?: number | null;
    agency_longitude?: number | null;
}

class SubscriptionService extends BaseService {
    private static instance: SubscriptionService;

    public static getInstance(): SubscriptionService {
        if (!SubscriptionService.instance) {
            SubscriptionService.instance = new SubscriptionService();
        }
        return SubscriptionService.instance;
    }

    async createSubscription(payload: SubscriptionRequest): Promise<any> {
        return await this.request(this.resource, 'POST', payload);
    }

    async retrieveSubscriptionDetail(payload: SubscriptionRequest): Promise<any> {
        return await this.request(this.resource + '-detail', 'GET', payload);
    }

    async validateSubscription(payload: SubscriptionRequest) {
        return await this.request(this.resource + '-validate', 'POST', payload);
    }

    private get resource(): string {
        const config = useRuntimeConfig();
        return `${config.public.backendApi}/api/subscription`;
    }
}

export const subscriptionService = SubscriptionService.getInstance();

