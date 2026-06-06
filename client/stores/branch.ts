export interface Branch {
    name: string;
    address: string;
    contact_number: string;
    description: string;
    image: File | null;
    street: string,
    city: string,
    province: string,
    country: string,
};
export const branchFields: Array<{ key: string; label: string; type: string }> = [
    { key: "name", label: "Branch Name", type: "text" },
    { key: "description", label: "Description", type: "text" },
    { key: "contact_number", label: "Contact Number", type: "text" },
    { key: "country", label: "Country", type: "text" },
    { key: "province", label: "Province", type: "text" },
    { key: "city", label: "City", type: "text" },
    { key: "street", label: "Street", type: "text" },
];