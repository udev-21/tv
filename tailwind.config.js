/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./node_modules/flowbite/**/*.js",
    "./resources/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors:{
        active: {
          100: '#0e9f6e',
        },
      }
    },
  },
  plugins: [
    require('flowbite/plugin'),
  ],
}

