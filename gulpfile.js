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
    mix.less(['all.less'],'./resources/assets/css').styles(['/css/all.css', '/bower/jquery-ui/themes/ui-darkness/jquery-ui.css', '/bower/jquery-ui/themes/ui-darkness/theme.css', '/bower/bootstrap-combobox/css/bootstrap-combobox.css'], './public/css', './resources/assets/');
});

//Dashboard css for admin section
elixir(function(mix) {
    mix.styles('/resources/assets/css/dashboard.css', "public/css/dashboard.css");
});

elixir(function(mix) {
    mix.scripts(['bower/bootstrap/dist/js/bootstrap.js', 'bower/bootstrap-combobox/js/bootstrap-combobox.js', 'bower/jquery-ujs/src/rails.js'],
         './public/js', './resources/assets/');
})