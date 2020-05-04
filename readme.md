## Simple Control Panel

Forked from [GitHub](https://github.com/kjjdion/laravel-admin-panel)

Laravel Admin Panel ("LAP") is a drop-in admin panel package for Laravel which promotes rapid scaffolding & development.

- [Demo](https://lap.kjjdion.com/admin)
- [Screenshots](https://imgur.com/a/12mGWNW)
- [Documentation](https://lap.kjjdion.com/docs)
- [GitHub](https://github.com/kjjdion/laravel-admin-panel)

Features:

- More enhancement

Packages used:

- [Laravel 5.7, 5.8 & Above](https://laravel.com/)
- [Laravel Datatables](https://github.com/yajra/laravel-datatables)
- [Laravel Nestedset](https://github.com/lazychaser/laravel-nestedset)
- [Parsedown](http://parsedown.org/)
- [SEOTools](https://github.com/artesaos/seotools)
- [Log Viewer](https://github.com/rap2hpoutre/laravel-log-viewer)
- [Fast Excel](https://github.com/rap2hpoutre/fast-excel)

Assets used:

- Custom admin panel layout (inspired by [Nova](https://nova.laravel.com))
- [Bootstrap 4](https://getbootstrap.com)
- [Datatables](https://datatables.net) (with some tweaks for a better UX)
- [FontAwesome 5](https://fontawesome.com)

### Installation

Require via composer:

    composer require wikichua/simplecontrolpanel master-dev

For Laravel 7.x

    composer require laravel/ui

Publish install files:

    php artisan vendor:publish --tag=lap.general

General install including:

- public
- lang
- layouts
- auth
- backend
- users

Publish advanced files (1 by 1):

    php artisan vendor:publish --tag=lap.config
    php artisan vendor:publish --tag=lap.seo.config
    php artisan vendor:publish --tag=lap.public
    php artisan vendor:publish --tag=lap.lang
    php artisan vendor:publish --tag=lap.layouts
    php artisan vendor:publish --tag=lap.auth.view
    php artisan vendor:publish --tag=lap.backend.view
    php artisan vendor:publish --tag=lap.users.view

Publish all migrations files:

    php artisan vendor:publish --tag=lap.migrations

Publish all stubs files:

    php artisan vendor:publish --tag=lap.stubs

Publish all views files:

    php artisan vendor:publish --tag=lap.all.view

Publish admin route files:

    php artisan vendor:publish --tag=lap.admin.route

Add the `AdminUser`, `DynamicFillable`, and `UserTimezone` traits to your `User` model:

    use Wikichua\Simplecontrolpanel\Traits\AdminUser;
    use Wikichua\Simplecontrolpanel\Traits\DynamicFillable;
    use Wikichua\Simplecontrolpanel\Traits\UserTimezone;
    
    class User extends Authenticatable
    {
        use Notifiable, AdminUser, DynamicFillable, UserTimezone;

Add this in your controller.php
    use \Wikichua\Simplecontrolpanel\Traits\Controller;

    class Controller extends BaseController
    {
        use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
        use \Wikichua\Simplecontrolpanel\Traits\Controller;

Run the migrations:

    php artisan migrate


### Alternative installation:

Install laravel:

    composer create-project laravel/laravel --prefer-dist appName

Create directories in terminal:

    cd appName; mkdir packages; cd packages; mkdir wikichua; cd wikichua; git clone https://github.com/wikichua/simplecontrolpanel.git

Add this in your composer.json under scripts section:

    "require-dev": {
        "wikichua/simplecontrolpanel": "*"
    },

    "repositories": {
        "wikichua/simplecontrolpanel": {
            "type": "path",
            "url": "/path/to/your/appName/packages/wikichua/simplecontrolpanel"
        }
    }

### Alternative installation 2:

Need packager to ease your work

    $ composer require jeroen-g/laravel-packager --dev

Import package from github

    $ php artisan packager:git git@github.com:wikichua/simplecontrolpanel.git

Add this in your composer.json under scripts section:

    "require-dev": {
        "wikichua/simplecontrolpanel": "*"
    },

Run composer update

### Logging In

Visit `(APP_URL)/admin` to access the admin panel.

The default admin login is:

    Email Address: admin@example.com
    Password: admin123