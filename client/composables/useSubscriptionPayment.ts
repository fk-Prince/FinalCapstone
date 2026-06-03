import { subscriptionService, type SubscriptionRequest } from "~/api/subscription/SubscriptionService";
import { use3DS } from "./use3DS";
const { handle3DS } = use3DS();


export interface CardDetails {
    number: number
    expMonth: string,
    expYear: string,
    cvc: string,
    firstName: string,
    lastName: string,
}




export async function cardPayment(card: CardDetails, checkout: any, closeModal: any) {
    let data;
    const config = useRuntimeConfig();
    window.Xendit.setPublishableKey(config.public.xenditPublicKey);

    const subData: SubscriptionRequest = {
        plan_code: checkout.selectedPlan.plan_code,
        payment_method: checkout.payment_method,
        billing_interval: checkout.selectedInterval,
        //BRANCH DATA
        branch_name: checkout.branch.name,
        branch_postal_code: checkout.branch.postal_code,
        branch_street: checkout.branch.street,
        branch_city: checkout.branch.city,
        branch_province: checkout.branch.province,
        branch_country: checkout.branch.country,
        branch_contact_number: checkout.branch.contact_number,
        branch_image: checkout.branch.image,

        // AGENCY DATA
        agency_name: checkout.agency.name,
        agency_description: checkout.agency.description,
    };





    const res = await subscriptionService.retrieveSubscriptionDetail(subData);
    if (!res.status) return;

    let cardData = {
        amount: Number(res.total_amount),
        card_number: card.number.toString().replace(/\s/g, ""),
        card_exp_month: String(card.expMonth),
        card_exp_year: String(
            Math.floor(new Date().getFullYear() / 100) * 100 +
            Number(card.expYear),
        ),
        card_cvc: card.cvc,
        card_holder_first_name: res.user.first_name,
        card_holder_last_name: res.user.last_name,
        card_holder_email: res.user.email,
    };

    window.Xendit.card.createToken(
        {
            ...cardData,
            is_multiple_use: false,
            should_authenticate: true,
        },
        async (err: any, token: any) => {
            if (err) return console.error(err);

            window.Xendit.card.createAuthentication(
                {
                    token_id: token.id,
                    amount: cardData.amount,
                },
                async (err: any, auth: any) => {
                    if (err) return console.error(err);

                    const executePayment = async () => {
                        const result = await subscriptionService.createSubscription({
                            token_id: token.id,
                            authentication_id: auth.id,
                            ...subData
                        });

                        console.log("Payment success:", result);
                        data = result;
                        return data;
                    };

                    if (
                        auth.status === "IN_REVIEW" &&
                        auth.payer_authentication_url
                    ) {
                        const { close, onComplete } = handle3DS(
                            auth.payer_authentication_url,
                        );

                        closeModal.value = close;

                        onComplete(async () => {
                            try {
                                const result = await executePayment();

                                if (closeModal.value) {
                                    closeModal.value();
                                }

                                await navigateTo({

                                    path: "/subscription/summary",
                                    query: {
                                        status: result.status,
                                    },
                                });
                                return data = result;
                            } catch (err) {
                                console.error(err);
                            }
                        });
                        return;
                    }

                    if (auth.status === "VERIFIED") {
                        try {
                            const result = await executePayment();
                            setTimeout(() => {
                                if (closeModal.value) {
                                    closeModal.value();
                                }
                            }, 3000);

                            await navigateTo({
                                path: "/subscription/summary",
                                query: {
                                    status: result.status,
                                },
                            });
                        } catch (err) {
                            console.error(err);
                        }
                    }
                },
            );
        },
    );
}


export async function gcashPayment(checkout: any, closeModal: any) {
    try {
        const res = await subscriptionService.createSubscription({
            plan_code: checkout.selectedPlan.plan_code,
            payment_method: checkout.payment_method,
            billing_interval: checkout.selectedInterval,
            //BRANCH DATA
            branch_name: checkout.branch.name,
            branch_postal_code: checkout.branch.postal_code,
            branch_street: checkout.branch.street,
            branch_city: checkout.branch.city,
            branch_province: checkout.branch.province,
            branch_country: checkout.branch.country,
            branch_contact_number: checkout.branch.contact_number,
            branch_image: checkout.branch.image,
            // AGENCY DATA
            agency_name: checkout.agency.name,
            agency_description: checkout.agency.description,
        });
        const url = res?.invoice_url;
        if (!url) return;

        const { close, onComplete } = handle3DS(url, "GCash Payment");
        closeModal.value = close;
        onComplete(async () => {
            setTimeout(() => {
                if (closeModal.value) {
                    closeModal.value();
                }
            }, 3000);

            await navigateTo({
                path: "/subscription/summary",
                query: {
                    status: res.status,
                },
            });
        });
    } catch (err) {
        console.error(err);
    }
};
