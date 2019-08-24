const mix = require('laravel-mix')
// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/app.js', 'public/js')

mix
    .sass('resources/sass/bootstrap.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')

mix.extract([
    'axios',
    'lodash',
    'jquery',
    'popper.js',
    'bootstrap',
])

mix.sourceMaps(true, 'cheap-module-source-map')

// Production Settings
if (mix.inProduction())
    // Versioning / Cache Busting
    // Disabled Notifications
    mix
        .version()
        .disableNotifications()
        .options({
            // Don't clear screen in prod. env
            clearConsole: false,
        })

// Create Webpack plugins Array
// const plugins = []
//
// if (!mix.inProduction()) {
//     plugins.push(
//         new BundleAnalyzerPlugin({
//             // analyzerMode: 'static',
//             analyzerHost: 'localhost',
//             analyzerPort: 8081,
//         })
//     )
// }
//
// mix
//     .webpackConfig({
//         plugins,
//     })
