/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
    "./error.vue"
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          dark: '#10414F',       // Mã 1: Deep forest teal/slate
          primary: '#286874',    // Mã 2: Primary brand teal
          secondary: 'rgb(248, 240, 232)', // Mã 2.5: Secondary beige
          accent: '#A77E52',     // Mã 3: Gold/Bronze accent
          light: '#FEE3CE',      // Mã 4: Warm cream/peach light color
        }
      }
    }
  },
  plugins: []
}
