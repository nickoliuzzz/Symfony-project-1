var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('addquiz', './assets/js/addquiz.js')
    .addEntry('plugin', './assets/js/plugin.js')
    .addEntry('auth_js', './assets/js/auth_script.js')
    .addStyleEntry('addquizstyle', './assets/css/addquiz.css')
    .addStyleEntry('auth_style', './assets/css/auth_form.css')
    .createSharedEntry('logo','./assets/images/logo.png')

    // uncomment if you use Sass/SCSS files
    //.enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()

;

module.exports = Encore.getWebpackConfig();
