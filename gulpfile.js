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
                 'js/bootstrap-ujs.js',
                 '/bower/bootstrap-submenu/dist/js/bootstrap-submenu.js'],
                './public/js', './resources/assets/');
    mix.scripts(['bower/jquery-ui/ui/core.js',
                 'bower/jquery-ui/ui/datepicker.js'],
                 './public/js/jquery-ui', './resources/assets/');
    mix.copy('./resources/assets/bower/jquery-ui/themes/overcast', './public/css/jquery-ui/overcast');
    mix.copy('./resources/assets/bower/globalize/dist/globalize.js', './public/js');
    mix.copy('./resources/assets/bower/globalize/dist/globalize/date.js', './public/js/globalize');
    mix.copy('./resources/assets/bower/globalize/dist/globalize/number.js', './public/js/globalize');
    mix.copy('./resources/assets/bower/cldrjs/dist/cldr.js', './public/js');
    mix.copy('./resources/assets/bower/cldrjs/dist/cldr/event.js', './public/js/cldr');
    mix.copy('./resources/assets/bower/cldrjs/dist/cldr/supplemental.js', './public/js/cldr');
    mix.copy('./resources/assets/bower/cldrjs/dist/cldr/unresolved.js', './public/js/cldr');
    mix.copy('./resources/assets/bower/cldr-data/main/en/', './public/js/cldr-data/main/en');
    mix.copy('./resources/assets/bower/cldr-data/supplemental/likelySubtags.json', './public/js/cldr-data/supplemental');
    mix.copy('./resources/assets/bower/requirejs/require.js', './public/js/requirejs');
    mix.copy('./resources/assets/bower/requirejs-json/json.js', './public/js/requirejs');
    mix.copy('./resources/assets/bower/requirejs-text/text.js', './public/js/requirejs');
});


//TinyMCE
elixir(function(mix) {
    mix.copy('./resources/assets/bower/tinymce/tinymce.min.js', './public/tinymce');
    mix.copy('./resources/assets/bower/tinymce/plugins', './public/tinymce/plugins');
    mix.copy('./resources/assets/bower/tinymce/skins', './public/tinymce/skins');
    mix.copy('./resources/assets/bower/tinymce/themes', './public/tinymce/themes');
    mix.copy('./resources/assets/bower/tinymce/skins/lightgray/fonts', './public/css/fonts');
});
