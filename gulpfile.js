var gulp = require('gulp');
var elixir = require('laravel-elixir');


//从 bower 安装的包复制文件
gulp.task("copyfiles", function() {
    gulp.src("vendor/bower_dl/jquery/jquery.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("vendor/bower_dl/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("vendor/bower_dl/bootstrap/less/**")
        .pipe(gulp.dest("resources/assets/less/bootstrap"));

    gulp.src("vendor/bower_dl/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/build/assets/fonts"));

    gulp.src("vendor/bower_dl/bootswatch/journal/*.less")
        .pipe(gulp.dest("resources/assets/less"));

    gulp.src("vendor/bower_dl/fontawesome/less/**")
        .pipe(gulp.dest("resources/assets/less/fontawesome"));

    gulp.src("vendor/bower_dl/fontawesome/fonts/**")
        .pipe(gulp.dest("public/build/assets/fonts"));

    gulp.src("vendor/bower_dl/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/build/assets/fonts"));

    gulp.src("vendor/bower_dl/bootstrap3-typeahead/bootstrap3-typeahead.min.js")
        .pipe(gulp.dest("public/assets/js/"));

    gulp.src("vendor/bower_dl/jquery-simple-datetimepicker/jquery.simple-dtpicker.js")
        .pipe(gulp.dest("public/assets/js/"));

    gulp.src("vendor/bower_dl/jquery-simple-datetimepicker/jquery.simple-dtpicker.css")
        .pipe(gulp.dest("resources/assets/css/"));
});


elixir(function(mix) {
    // 编译 Less 
    mix.less('app.less', 'resources/assets/css/bootstrap-3.3.6.css');

    //合并 CSS
    mix.styles(['bootstrap-3.3.6.css', 'jquery.simple-dtpicker.css', 'style.css'],
        'public/assets/css/all.css'
    );

    // 合并 js
    mix.scripts(['jquery.js', 'bootstrap.js'],
        'public/assets/js/all.js'
    );

    //发布
    mix.version(['assets/css/all.css', 'assets/js/all.js']);
});


