const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const ImageminPlugin = require("imagemin-webpack-plugin").default;
const glob = require("glob");

module.exports = {
  mode: "development",

  entry: {
    index: { import: "./src/index/index.ts", filename: "index.bundle.js" },
    style: "./src/style.css",
  },
  output: {
    clean: true,
    path: path.resolve(__dirname, "assets/dist"),
  },
  resolve: {
    extensions: ["*", ".js", ".jsx", ".ts", ".tsx"],
  },
  plugins: [
    new ImageminPlugin({
      externalImages: {
        context: ".",
        sources: glob.sync("./src/images/**/*.{png,jpg,jpeg,gif,svg}"),
        destination: "assets/dist/images",
        fileName: "[name].[ext]",
      },
    }),
    new MiniCssExtractPlugin(),
  ],
  module: {
    rules: [
      {
        test: /\.(js|jsx|ts|tsx)$/,
        use: "babel-loader",
        exclude: /node_modules/,
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, "css-loader", "postcss-loader"],
      },
    ],
  },
};
