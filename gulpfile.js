var elixir = require('laravel-elixir');
var gulp = require('gulp');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less(['all.less'],'./resources/assets/css/all.css');
    mix.styles(['/css/all.css',
                '/bower/jquery-ui/themes/ui-darkness/jquery-ui.css',
                '/bower/jquery-ui/themes/ui-darkness/theme.css',
                '/bower/bootstrap-combobox/css/bootstrap-combobox.css',
                '/bower/bootstrap-submenu/dist/css/bootstrap-submenu.min.css',
                '/bower/tinymce/skins/lightgray/skin.min.css'],
               './public/css', './resources/assets/');
});

//Dashboard css for admin section
elixir(function(mix) {
    mix.styles('dashboard.css', "public/css/dashboard.css");
});

elixir(function(mix) {
    mix.scripts(['bower/bootstrap/dist/js/bootstrap.js',
                 'bower/bootstrap-combobox/js/bootstrap-combobox.js',
                 'bower/jquery-ujs/src/rails.js',
                 '/bower/bootstrap-submenu/dist/js/bootstrap-submenu.js',],
                './public/js', './resources/assets/');
})


//TinyMCE
elixir(function(mix) {
    mix.copy('./resources/assets/bower/tinymce/tinymce.min.js', './public/tinymce');
    mix.copy('./resources/assets/bower/tinymce/plugins', './public/tinymce/plugins');
    mix.copy('./resources/assets/bower/tinymce/skins', './public/tinymce/skins');
    mix.copy('./resources/assets/bower/tinymce/themes', './public/tinymce/themes');
    mix.copy('./resources/assets/bower/tinymce/skins/lightgray/fonts', './public/css/fonts');
});
