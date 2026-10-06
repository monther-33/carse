<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/*
| Owner's decision: users sign in with a username; the email is removed. Existing users get
| the part of their email before "@" (admin@cars.local → admin), made unique if needed.
| Password resets by email go too: the admin sets a new password from the Users screen.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->after('name');
        });

        $taken = [];
        foreach (DB::table('users')->orderBy('id')->get(['id', 'email']) as $user) {
            $base = Str::of((string) Str::before((string) $user->email, '@'))->lower()->replaceMatches('/[^a-z0-9._-]/', '')->limit(40, '')->value() ?: 'user';
            $username = $base;
            for ($i = 2; isset($taken[$username]); $i++) {
                $username = $base.$i;
            }
            $taken[$username] = true;
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable(false)->change();
            $table->unique('username');
            $table->dropUnique(['email']);
            $table->dropColumn(['email', 'email_verified_at']);
        });

        Schema::dropIfExists('password_reset_tokens');
    }

    public function down(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        DB::table('users')->update(['email' => DB::raw("CONCAT(username, '@cars.local')")]);

        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
