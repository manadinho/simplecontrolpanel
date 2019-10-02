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

Assets used:

- Custom admin panel layout (inspired by [Nova](https://nova.laravel.com))
- [Bootstrap 4](https://getbootstrap.com)
- [Datatables](https://datatables.net) (with some tweaks for a better UX)
- [FontAwesome 5](https://fontawesome.com)

### Installation

Require via composer:

    composer require wikichua/simplecontrolpanel

Publish install files:

    php artisan vendor:publish --tag="lap.install"

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


Alternative

- composer create-project laravel/laravel --prefer-dist appName
- cd appName; mkdir packages; cd packages; mkdir wikichua; cd wikichua; git clone https://github.com/wikichua/simplecontrolpanel.git
- add this in composer.json under scripts

    "repositories": {
        "wikichua-simplecontrolpanel": {
            "type": "path",
            "url": "/path/to/your/appName/packages/wikichua/simplecontrolpanel"
        }
    }


### Logging In

Visit `(APP_URL)/admin` to access the admin panel.

The default admin login is:

    Email Address: admin@example.com
    Password: admin123