/** @type {import('tailwindcss').Config} */
import preset from './vendor/filament/support/tailwind.config.preset';

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

