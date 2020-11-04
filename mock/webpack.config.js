const path = require("path");
const nodeExternals = require("webpack-node-externals");

const { NODE_ENV = "production" } = process.env;

module.exports = {
  entry: {
    index: path.resolve(__dirname, "src/index.ts")
  },
  mode: NODE_ENV,
  target: "node",
  externals: [nodeExternals()],
  output: {
    path: path.resolve(__dirname, "build"),
    filename: "[name].js"
  },
  resolve: {
    extensions: [".ts", ".js"],
    modules: [path.resolve(__dirname, 'src'), 'node_modules'],
    alias: {
      src: path.resolve(__dirname, "src")
    }
  },
  module: {
    rules: [
      {
        test: /\.ts$/,
        use: ["ts-loader"],
        exclude: '/node_modules/'
      }
    ]
  }
};
