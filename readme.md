# iLogme based on Laravel 5.2

[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://github.com/laravel/laravel/)

Log and show yourself.Demo [iLogme.com](http://ilogme.com/laoyuan).

记录每天要干些什么（ Todo ），打算立刻去干什么（时段）、到底干了什么（截屏）

## client to upload screencapture
https://github.com/laoyuan/ilogme-client

## Setup

#MySQL Command-Line
CREATE DATABASE `ilogme` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

cd /home
git clone https://github.com/laoyuan/ilogme.git
cd ilogme
composer install

#In Mainland China, befor composer install
composer config -g repo.packagist composer http://packagist.phpcomposer.com

#edit DB_*
cp .env.example .env
vi .env

php artisan key:generate
php artisan migrate --seed

chown -R nginx:nginx bootstrap/cache
chown -R nginx:nginx storage


#edit nginx.conf
``
    server {
        listen       80;
        server_name  ilogme.com www.ilogme.com;
        root         /home/ilogme/public;
        index        index.php index.html;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass   unix:/run/php-fpm/php70-php-fpm.sock;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
    }
``


## hack for "Remember me", reduce to 30 days

Illuminate/Auth/SessionGuard.php
```
    protected function createRecaller($value)
    {
        #return $this->getCookieJar()->forever($this->getRecallerName(), $value);
        return $this->getCookieJar()->make($this->getRecallerName(), $value, 43200);
    }
```




## DevLog


```
cd ~/laravel
composer create-project laravel/laravel --prefer-dist ilogme

cd ilogme

composer require "overtrue/laravel-lang:dev-master"  #for Multi-language
cp -r vendor/caouecs/laravel4-lang/zh-CN resources/lang
composer remove overtrue/laravel-lang --update-with-dependencies

npm update -g
npm install -g coffee-script marked jshint leasot node-gyp gulp bower
npm install gulp laravel-elixir

vi .bowerrc

{
    "directory": "vendor/bower_dl"
}

sudo npm install bower

vi bower.json
{
    "name": "ilogme",
    "description": "log yourself",
    "ignore": [
        "**/.*",
        "node_modules",
        "vendor/bower_dl",
        "test",
        "tests"
    ],
    "dependencies": {
        "jquery": "1.10.2",
        "bootstrap": "3.3.6"
    }
}

bower update

vi gulpfile.js

var gulp = require('gulp');
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
        .pipe(gulp.dest("public/assets/fonts"));

});





resources/assets/less
wget http://bootswatch.com/journal/bootswatch.less
mv bootswatch.less resources/assets/less/bootswatch.less
wget http://bootswatch.com/journal/variables.less
mv variables.less resources/assets/less/variables.less








git init
git remote add origin git@github.com:laoyuan/ilogme.git
git add .
git commit -m "first"
git push origin master -u


php artisan make:auth

php artisan make:migration create_types_table --create=types
php artisan make:migration create_type_user_table --create=type_user
php artisan make:migration create_spans_table --create=spans
php artisan make:migration create_todos_table --create=todos
php artisan make:migration create_notes_table --create=notes
php artisan make:migration create_pics_table --create=pics
php artisan make:migration create_comments_table --create=comments

php artisan make:seeder TypeTableSeeder

php artisan migrate --seed

git commit -m "migration"



php artisan make:model Span
php artisan make:model Type
php artisan make:model Todo
php artisan make:model Note
php artisan make:model Pic


php artisan make:controller UserController
php artisan make:controller SpanController --resource
php artisan make:controller TypeController --resource
php artisan make:controller TodoController --resource
php artisan make:controller NoteController --resource
php artisan make:controller PicController --resource
```

```
wget http://bootswatch.com/journal/bootstrap.min.css
mv bootstrap.min.css public/assets/css/bootstrap-3.3.6.min.css
```
delete google font in first line, replace `"News Cycle"` with `Helvetica`.


wget --no-check-certificate https://github.com/jieyou/lazyload/raw/master/lazyload.min.js
mv lazyload.min.js public/assets/js/lazyload.min.js



