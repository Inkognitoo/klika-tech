var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: {
        tracks_collection: './react/tracks_collection.js'
    },
    output: {
        path: './web/js',
        filename: '[name].js'
    },
    module: {
        loaders: [
            {
                test: /.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['es2015', 'stage-2', 'react']
                }
            }
        ]
    },
    devtool: 'source-map'
};