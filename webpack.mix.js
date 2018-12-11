let mix = require('laravel-mix');

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

mix.react('resources/assets/js/app.js', 'public/assets/dist/bundle.min.js');
//.sass('resources/assets/sass/app.scss', 'public/css');

mix.combine([
    'public/assets/vendor/jquery.js',
    'public/assets/vendor/jquery-ui.min.js',
    'public/assets/vendor/bootstrap/js/bootstrap.js',
    'public/assets/vendor/contextmenu/bootstrap-contextmenu.js',
    'public/assets/vendor/colorpicker/jquery.simplecolorpicker.js',
    'public/assets/vendor/toastr/toastr.js',

    'public/assets/vendor/select2/select2.js',
    'public/assets/vendor/select2/zh-CN.js',

    'public/assets/vendor/bootstrap-table/bootstrap-table.js',
    'public/assets/vendor/bootstrap-table/bootstrap-table-zh-CN.js',
    'public/assets/vendor/jqgrid/js/jquery.jqGrid.js',
    'public/assets/vendor/jqgrid/js/grid.locale-cn.js',
    'public/assets/vendor/pcas.js',
    'public/assets/vendor/template.min.js',
    'public/assets/js/jqgrid/jqgrid.js',
    'public/assets/js/jqgrid/editing/dropdown.js',
    'public/assets/js/jqgrid/form.js',
    'public/assets/js/select2.js',
    'public/assets/js/search.js',
    'public/assets/js/dialog.js',
    'public/assets/js/listview.js',
    'public/assets/js/model.js',
    'public/assets/js/support.js',

],'public/assets/dist/app.min.js');

mix.combine([
    'public/assets/vendor/bootstrap/css/font-awesome.min.css',
    'public/assets/vendor/bootstrap/css/animate.css',
    'public/assets/vendor/bootstrap/css/bootstrap.css',
    'public/assets/vendor/bootstrap/css/glyphicon.css',
    'public/assets/vendor/toastr/toastr.css',
    'public/assets/vendor/colorpicker/jquery.simplecolorpicker.css',

    'public/assets/vendor/select2/select2.css',

    'public/assets/vendor/bootstrap-table/bootstrap-table.css',
    'public/assets/vendor/jqgrid/css/ui.jqgrid-bootstrap.css',

    'public/assets/css/reset.css',
    'public/assets/css/aike.css',
    'public/assets/css/jqgrid.css',

],'public/assets/dist/app.min.css')

mix.combine([
    'public/assets/libs/modernizr.min.js',
    'public/assets/vendor/jquery.js',
    'public/assets/vendor/jquery-ui.min.js',
    'public/assets/vendor/bootstrap/js/bootstrap.js',
    'public/assets/vendor/toastr/toastr.js',
    'public/assets/js/dialog.js',
    'public/assets/js/menu.js',
    'public/assets/js/support.js',

],'public/assets/dist/index.min.js')

mix.combine([
    'public/assets/vendor/bootstrap/css/font-awesome.min.css',
    'public/assets/vendor/bootstrap/css/animate.css',
    'public/assets/vendor/bootstrap/css/bootstrap.css',
    'public/assets/vendor/bootstrap/css/glyphicon.css',
    'public/assets/vendor/toastr/toastr.css',

    'public/assets/css/reset.css',
    'public/assets/css/aike.css',
    'public/assets/css/menu.css',

],'public/assets/dist/index.min.css');

if (mix.inProduction()) {
    mix.version();
}
