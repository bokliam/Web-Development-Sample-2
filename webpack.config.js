const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
    entry: './src/index.js',
    output: {
        filename: 'main.js',
        path: path.resolve(__dirname, 'dist'),
        publicPath: 'dist'
    },
    devtool: 'source-map',
    plugins: [
        new CopyWebpackPlugin([
            {
                from: 'src/img/*',
                to: 'img',
                flatten: true
            }
        ]),
        new CopyWebpackPlugin([
            {
                from: 'src/audio/*',
                to: 'audio',
                flatten: true
            }
        ])
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    'style-loader',
                    'css-loader',
                    'resolve-url-loader',
                    'sass-loader?sourceMap'
                ]
            },
            {
                test: /\.(jpe?g|png|gif)$/i,
                loader:"url-loader",
                query:{
                    limit: 15000,
                    name:'[name].[ext]',
                    outputPath:'/img/'
                }
            }
        ]
    }
};
