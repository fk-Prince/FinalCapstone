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
    lat?: number,
    lng?: number
};
export const branchFields: Array<{ key: string; label: string; type: string }> = [
    { key: "name", label: "Branch Name", type: "text" },
    { key: "description", label: "Description", type: "text" },
    { key: "contact_number", label: "Contact Number", type: "text" },
    { key: "address", label: "Address", type: "computed" },
    { key: "hours", label: "Business Hours", type: "computed" },
    { key: "currency", label: "Currency", type: "text" },
    // { key: "additional_payment", label: "Online Additional Payment", type: "text" },
];