<?php

namespace LaravelShared\Core\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UserMigration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('country')->nullable();

            // $table->string('username_hash')->nullable();
            // $table->string('email_hash')->unique()->nullable();

            $table->timestamp('online_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
