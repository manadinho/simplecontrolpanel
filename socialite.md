### Setup socialite login for frontpage

add this section in config > auth.php in guard

    'social' => [
        'driver' => 'session',
        'provider' => 'socials',
    ],

add this section in config > auth.php in provider

    'socials' => [
        'driver' => 'eloquent',
        'model' => App\Socialite::class,
    ],

add this section in config > services.php

    'google' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => env('APP_URL') . '/oauth/google/callback',
    ],

    'facebook' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => env('APP_URL') . '/oauth/facebook/callback',
    ],

    'twitter' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => env('APP_URL') . '/oauth/twitter/callback',
    ],

    'linkedin' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => env('APP_URL') . '/oauth/linkedin/callback',
    ],

In your class Authenticate extends Middleware 

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('social.login');
        }
    }

#### Explain

If you don't wish to use socialite within your project,

You will have to publish this vendor --tag=lap.admin.route --force

Then remove this section in your routes.php

    Route::get('login', config('lap.controllers.auth.socialite') . '@loginForm')->name('social.login');
    Route::get('logout', config('lap.controllers.auth.socialite') . '@logout')->name('social.logout');
    Route::get('oauth/{driver}', config('lap.controllers.auth.socialite') . '@redirectToProvider')->name('social.oauth');
    Route::get('oauth/{driver}/callback', config('lap.controllers.auth.socialite') . '@handleProviderCallback')->name('social.callback');

    Route::group(['middleware' => ['auth:social','https_protocol']], function () {
        Route::get('/', config('lap.controllers.frontend') . '@index')->name('home');
    });

However, if you are happy with this, and wish to modify FrontendController to yours own name e.g. HomeController

You may extend your HomeController to \Wikichua\Simplecontrolpanel\Controllers\FrontendController

#### Why Socialite?

I have been thinking, some of us will seperating public user entity from backend, but instead of using normal login, won't you think that social login is very convenient?