/** @type {import('tailwindcss').Config} */

import { colors, fontFamily, borderRadius } from "./tailwind.theme";
export default {
    content: [
        "./components/**/*.{vue,js,ts}",
        "./layouts/**/*.vue",
        "./pages/**/*.vue",
        "./pages/**/*.{vue,js,ts}",
        "./app.vue",
        "./plugins/**/*.{js,ts}",
        "./app/*.vue",
        "./nuxt.config.{js,ts}",
    ],
    theme: {
        extend: {
            colors,
            fontFamily,
            borderRadius,
        },
    },
    plugins: [],
};
