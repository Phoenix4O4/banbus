const purgecss = require("@fullhuman/postcss-purgecss");
const cssnano = require("cssnano");

const purgeCSSOptions = {
  content: ["./views/**/*.twig", "./views/**/*.html", './assets/js/**/*.vue'],
  defaultExtractor: (content) => content.match(/[\w-/:]+(?<!:)/g) || [],
};
// module.exports = {
//   plugins: [
//     require("postcss-import"),
//     require("tailwindcss"),
//     process.env.NODE_ENV === "production" ? require("autoprefixer") : null,
//     process.env.NODE_ENV === "production"
//       ? cssnano({ preset: "default" })
//       : null,
//     process.env.NODE_ENV === "production"
//       ? purgecss(purgeCSSOptions)
//       : null,
//   ],
// };
