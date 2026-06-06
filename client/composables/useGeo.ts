// ~/composables/useGeo.ts

interface City {
    id: number;
    name: string;
}

interface State {
    name: string;
    iso2: string;
    cities: City[];
}

interface Country {
    name: string;
    iso2: string;
    emoji: string;
    states: State[];
}

let cache: Country[] | null = null;

// Lazy loader (prevents full bundle load on startup)
async function loadGeoData(): Promise<Country[]> {
    if (cache) return cache;

    const module = await import("~/data/countries+states+cities.json");
    cache = module.default as Country[];

    return cache;
}

export async function getCountries() {
    const data = await loadGeoData();

    return data.map((c) => ({
        name: c.name,
        iso2: c.iso2,
        emoji: c.emoji,
    }));
}

export async function getStatesOfCountry(countryIso2: string) {
    const data = await loadGeoData();

    const country = data.find((c) => c.iso2 === countryIso2);

    return (
        country?.states?.map((s) => ({
            name: s.name,
            iso2: s.iso2,
        })) ?? []
    );
}

export async function getCitiesOfState(
    countryIso2: string,
    stateIso2: string,
) {
    const data = await loadGeoData();

    const country = data.find((c) => c.iso2 === countryIso2);
    const state = country?.states?.find((s) => s.iso2 === stateIso2);

    return state?.cities ?? [];
}