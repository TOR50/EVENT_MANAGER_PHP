/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./event_management/**/*.php",
        "./event_management/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                background: '#111111',
                surface: '#18181B',
                primary: '#2DD4BF', // Teal-400
                secondary: '#00FFFF', // Cyan
                text: {
                    main: '#F4F4F5', // Zinc-100
                    muted: '#A1A1AA', // Zinc-400
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            backgroundImage: {
                'futuristic-gradient': 'linear-gradient(to right, #111111, #18181B)',
            }
        },
    },
    plugins: [],
}
