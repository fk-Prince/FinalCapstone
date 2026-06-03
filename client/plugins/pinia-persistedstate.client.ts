import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

export default defineNuxtPlugin((nuxtApp) => {
    const pinia: any = nuxtApp.$pinia;
    pinia.use(piniaPluginPersistedstate);
});