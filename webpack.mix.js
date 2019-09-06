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
    // Have to be first, because of sass paths and chunk names
    .js('resources/js/dummy.js', 'public/js')

    .js('resources/js/login.js', 'public/js')
    .sass('resources/sass/vendor/bootstrap-login.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')

    .js('resources/js/pages/product.js', 'public/js/pages')

    // Have to be last js, because of vendor and manifest path
    .js('resources/js/common.js', 'public/js')
    .sass('resources/sass/vendor/bootstrap.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')

mix.extract([])
// mix.extract([
//     'axios',
//     'lodash',
//     'jquery',
//     'popper.js',
//     'bootstrap',
//     'bootbox',
// ])

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

mix.dump()
