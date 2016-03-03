# ilogme based on Laravel 5.2

[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://github.com/laravel/laravel/)

Log and show yourself.
Demo [ilogme.com](http://ilogme.com/laoyuan) - comming soon.

## Setup

```
git clone https://github.com/laoyuan/ilogme.git
cd ilogme
composer install

#In Mainland China, befor composer install
composer config -g repo.packagist composer http://packagist.phpcomposer.com

#MySQL Command-Line
CREATE DATABASE  `ilogme` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#edit DB_*
cp .env.example .env
vi .env

php artisan key:generate
php artisan migrate --seed

```

##DevLog

Follow [Tutorials - Intermediate Task List](https://laravel.com/docs/5.2/quickstart-intermediate) and [Halnex/laravel-reddit](https://github.com/Halnex/laravel-reddit) for reference.

```
cd ~/laravel
composer create-project laravel/laravel --prefer-dist ilogme

cd ilogme
git init
git remote add origin git@github.com:laoyuan/ilogme.git
git add .
git commit -m "first"
git push origin master -u

#edit .env for database, DB_CONNECTION...

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

composer require "overtrue/laravel-lang:dev-master"  #for Multi-language
cp -r vendor/caouecs/laravel4-lang/zh-CN resources/lang  #提示信息汉化

php artisan make:auth
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

download http://bootswatch.com/journal/bootstrap.min.css to `public/assets/css/bootstrap-3.3.6.min.css`
delete google font in first line, replace `"News Cycle"` with `Helvetica`.


Illuminate/Auth/SessionGuard.php
```
protected function createRecaller($value)
{
    return $this->getCookieJar()->make($this->getRecallerName(), $value, 43200);
}
```

Illuminate\Foundation\Auth\RegistersUsers.php
```
public function register(Request $request)
{
    if ($request->exists('name')) {
        $request->offsetSet('name', trim($request->input('name')));
    }
```

Illuminate\Foundation\Auth\AuthenticatesUsers.php
```
protected function validateLogin(Request $request)
{
    $this->validate($request, [
            $this->loginUsername() => 'required|exists:users', 'password' => 'required|min:8|max:50',
    ]);
}
```





