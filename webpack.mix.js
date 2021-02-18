const purgecss = require("@fullhuman/postcss-purgecss");
let mix = require("laravel-mix");

mix.setPublicPath("public/assets");
mix.postCss("assets/css/style.css", "public/assets/css", [
  require("postcss-import"),
  require("tailwindcss")("./tailwind.config.js"),
  require("autoprefixer"),
  purgecss({
    content: ["./views/**/*.twig", "./views/**/*.html"],
    defaultExtractor: (content) => content.match(/[\w-/:]+(?<!:)/g) || [],
  }),
]);
