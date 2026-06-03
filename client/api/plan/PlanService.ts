import BaseService from '~/api/BaseService';

class PlanService extends BaseService {
    private static instance: PlanService;

    public static getInstance(): PlanService {
        if (!PlanService.instance) {
            PlanService.instance = new PlanService();
        }
        return PlanService.instance;
    }

    async list(params: object = {}): Promise<any> {
        return await this.request(this.resource, 'GET', params);
    }

    private get resource(): string {
        const config = useRuntimeConfig();
        return `${config.public.backendApi}/api/plans`;
    }
}

export const planService = PlanService.getInstance();
