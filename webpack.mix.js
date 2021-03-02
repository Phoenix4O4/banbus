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