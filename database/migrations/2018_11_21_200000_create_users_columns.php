<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateUsersColumns extends Migration
{
    public function up()
    {
        // create columns
        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
            $table->string('api_token')->nullable();
            $table->string('timezone')->default(config('app.timezone'))->index();
        });

        // create default admin user
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'api_token' => Str::uuid(),
        ]);

        // give default admin user default admin role
        $user->roles()->attach(app(config('lap.models.role'))->where('admin', true)->first()->id);

        // create permissions
        app(config('lap.models.permission'))->createGroup('Users', ['Create Users', 'Read Users', 'Update Users', 'Delete Users']);

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        // drop columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin');
            $table->dropColumn('timezone');
        });

        // delete permissions
        app(config('lap.models.permission'))->where('group', 'Users')->delete();

        Schema::drop('password_resets');
    }
}