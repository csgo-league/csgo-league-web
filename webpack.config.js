const path = require('path');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader"
        }
      }
    ]
  },
  mode: 'production',
  entry: './index.js',
  output: {
    filename: 'scripts.min.js',
    path: path.resolve(__dirname, './build/scripts')
  },
  context: path.resolve(__dirname, './assets/scripts'),
  optimization: {
    minimizer: [
      new UglifyJsPlugin({
        uglifyOptions: {
          output: {
            comments: false
          }
        }
      })
    ]
  }
};