const mix = require('laravel-mix');

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

mix.js('public/components/base/script.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.styles([
    'public/components/base/base.css',
    "public/components/trcker-login.css",
], 'public/css/all.css');
