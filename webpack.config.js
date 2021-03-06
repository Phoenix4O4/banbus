const path = require("path");
const { VueLoaderPlugin } = require("vue-loader");
module.exports = {
  entry: {
    test: "./vue/main.js",
    ticketFeed: "./vue/ticketfeed/feed.js",
    stats: "./vue/stats/main.js",
  },
  output: {
    path: path.resolve(__dirname, "public/assets/js"),
    filename: "[name].js",
  },
  plugins: [new VueLoaderPlugin()],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
    },
    extensions: ["*", ".js", ".vue", ".json"],
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        },
      },
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
      {
        test: /\.css$/,
        oneOf: [
          {
            resourceQuery: /module/,
            use: [
              "vue-style-loader",
              {
                loader: "css-loader",
                options: {
                  modules: true,
                  localIdentName: "[local]_[hash:base64:8]",
                },
              },
            ],
          },
          {
            use: ["vue-style-loader", "css-loader"],
          },
        ],
      },
    ],
  },
};
