import BaseService from '~/api/BaseService';

class AgencyService extends BaseService {
    private static instance: AgencyService;

    public static getInstance(): AgencyService {
        if (!AgencyService.instance) {
            AgencyService.instance = new AgencyService();
        }
        return AgencyService.instance;
    }

    async list(params: object = {}): Promise<any> {
        return await this.request(this.resource, 'GET', params);
    }

    private get resource(): string {
        const config = useRuntimeConfig();
        return `${config.public.backendApi}/api/agencies`;
    }
}

export const agencyService = AgencyService.getInstance();
