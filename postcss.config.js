const isProductionMode = process.env.NODE_ENV === "production";

module.exports = {
  plugins: [
    require("postcss-import"),
    require("tailwindcss"),
    require("autoprefixer"),
    isProductionMode ? 
    require("cssnano")({
      preset: "default",
    }) : null,
  ],
};
