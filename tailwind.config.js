/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue'
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#e6ebf4',
                    100: '#c0cde0',
                    200: '#99afcc',
                    300: '#7390b7',
                    400: '#4d72a3',
                    500: '#27548f',
                    600: '#1f4372',
                    700: '#173355',
                    800: '#0f2239',
                    900: '#001d52'
                },
                secondary: {
                    50: '#e6f2fb',
                    100: '#cce4f7',
                    200: '#99c9ef',
                    300: '#66ade7',
                    400: '#3392df',
                    500: '#0275d8',
                    600: '#025eac',
                    700: '#014680',
                    800: '#012f56',
                    900: '#00182b'
                },
                action: {
                    50: '#f1f8e6',
                    100: '#e4f1ce',
                    200: '#cae39d',
                    300: '#afd66d',
                    400: '#95c83c',
                    500: '#7bb929',
                    600: '#639421',
                    700: '#4a6f19',
                    800: '#324a10',
                    900: '#192508'
                },
                brand: {
                    50: '#e6f2fb',
                    100: '#cce4f7',
                    200: '#99c9ef',
                    300: '#66ade7',
                    400: '#3392df',
                    500: '#0275d8',
                    600: '#025eac',
                    700: '#014680',
                    800: '#012f56',
                    900: '#00182b'
                }
            }
        }
    },
    plugins: [require('@tailwindcss/forms')]
};
