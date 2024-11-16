/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
        extend: {
            gridColumn: {
                "span-0.5": "span 0.5 / span 0.5",
            },
            fontFamily: {
                engagement: ["Engagement", "cursive"],
            },
        },
    },
    plugins: [],
};
