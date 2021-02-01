const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
    './views/**/*.twig',
    './views/**/*.html',
   ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {
      fontFamily: {
        sans: ['Public Sans', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [
    // require('@tailwindcss/typography'),
  ],
}
