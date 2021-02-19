const purgecss = require("@fullhuman/postcss-purgecss");
const mix = require("laravel-mix");

mix
  .copyDirectory("./assets/sound", "./public/assets/sound")
  .js("./assets/js/ticketfeed/feed.js", "./public/assets/js/ticketfeed")
  .vue()
  .postCss("./assets/css/style.css", "public/assets/css")
  .options(
    {
      postCss: [
        require('tailwindcss')
      ]
    }
  )



// mix.postCss("assets/css/style.css", "public/assets/css", [
//   require("postcss-import"),
//   require("tailwindcss")("./tailwind.config.js"),
//   require("autoprefixer"),
//   purgecss({
//     content: [
//       "./views/**/*.{twig,html}",
//       "./assets/**/*.{vue,js,ts,jsx,tsx}",
//     ],
//   }),
// ]);