const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
     './views/**/*.twig',
   ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {
      fontFamily: {
        sans: ['Public Sans', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    // require('@tailwindcss/typography'),
  ],
}
