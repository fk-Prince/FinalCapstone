<template>
    <div class="mt-20">
        <h2
            class="font-display font-bold text-2xl text-center text-gray-900 mb-8"
        >
            Compare Features
        </h2>

        <div
            class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm"
        >
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th
                            class="text-left px-6 py-4 font-semibold text-gray-600 w-1/2"
                        >
                            Features
                        </th>
                        <th
                            class="text-center px-4 py-4 font-semibold text-gray-600"
                        >
                            Homecare
                        </th>
                        <th
                            class="text-center px-4 py-4 font-semibold text-gray-600"
                        >
                            Facility
                        </th>
                        <th
                            class="text-center px-4 py-4 font-semibold text-brand-blue"
                        >
                            Hybrid
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, i) in features"
                        :key="row.name"
                        :class="[
                            'border-b border-gray-50 transition-colors hover:bg-blue-50/30',
                            i % 2 === 0 ? 'bg-white' : 'bg-gray-50/50',
                        ]"
                    >
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ row.name }}
                        </td>
                        <td class="text-center px-4 py-4">
                            <svg
                                v-if="row.homecare"
                                class="w-5 h-5 text-green-500 mx-auto"
                                viewBox="0 0 20 20"
                                fill="none"
                            >
                                <circle cx="10" cy="10" r="9" fill="#f0fdf4" />
                                <path
                                    d="M6 10l3 3 5-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                            <span
                                v-else
                                class="block w-5 h-0.5 bg-gray-200 mx-auto rounded-full"
                            />
                        </td>
                        <td class="text-center px-4 py-4">
                            <svg
                                v-if="row.facility"
                                class="w-5 h-5 text-green-500 mx-auto"
                                viewBox="0 0 20 20"
                                fill="none"
                            >
                                <circle cx="10" cy="10" r="9" fill="#f0fdf4" />
                                <path
                                    d="M6 10l3 3 5-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                            <span
                                v-else
                                class="block w-5 h-0.5 bg-gray-200 mx-auto rounded-full"
                            />
                        </td>
                        <td class="text-center px-4 py-4">
                            <svg
                                v-if="row.hybrid"
                                class="w-5 h-5 text-green-500 mx-auto"
                                viewBox="0 0 20 20"
                                fill="none"
                            >
                                <circle cx="10" cy="10" r="9" fill="#f0fdf4" />
                                <path
                                    d="M6 10l3 3 5-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span
                                v-else
                                class="block w-5 h-0.5 bg-gray-200 mx-auto rounded-full"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
const features = [
    {
        name: "Home visits & caregivers",
        homecare: true,
        facility: false,
        hybrid: true,
    },
    {
        name: "Admissions & rooms",
        homecare: false,
        facility: true,
        hybrid: true,
    },
    { name: "eMAR & vitals", homecare: true, facility: true, hybrid: true },
    { name: "Family portal", homecare: true, facility: true, hybrid: true },
    { name: "VIP CCTV access", homecare: false, facility: true, hybrid: true },

    {
        name: "Billing & invoices",
        homecare: true,
        facility: true,
        hybrid: true,
    },
];
</script>
