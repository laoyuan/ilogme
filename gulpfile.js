var gulp = require('gulp');
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var elixir = require('laravel-elixir');

/**
 * 拷贝任何需要的文件
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

    gulp.src("vendor/bower_dl/jquery/jquery.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("vendor/bower_dl/bootstrap/less/**")
        .pipe(gulp.dest("resources/assets/less/bootstrap"));

    gulp.src("vendor/bower_dl/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("vendor/bower_dl/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/build/assets/fonts"));

});


elixir(function(mix) {
    // 编译 Less 
    mix.less('bootstrap/bootstrap.less', 'resources/assets/css/bootstrap-3.3.6.css');

    //合并 CSS
    mix.styles(['style.css', 'bootstrap-3.3.6.css'],
        'public/assets/css/all.css'
    );

    // 合并 js
    mix.scripts(['jquery.js','bootstrap.js'],
        'public/assets/js/all.js'
    );

    //发布
    mix.version(['assets/css/all.css', 'assets/js/all.js']);
});


