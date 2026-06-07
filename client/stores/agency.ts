export interface Agency {
    id?: number;
    name: string;
    description: string;
    street: string;
    city: string;
    province: string;
    country: string;
    lat?: number,
    lng?: number
    location?: Location
}

export interface Location {
    street: string;
    city: string;
    province: string;
    country: string;
}
export const agencyFields: Array<{ key: string; label: string; type: string }> = [
    { key: "name", label: "Agency Name", type: "text" },
    { key: "description", label: "Description", type: "text" },
    { key: "address", label: "Address", type: "computed" },
];