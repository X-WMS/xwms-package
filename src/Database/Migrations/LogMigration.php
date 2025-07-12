<?php

namespace LaravelShared\Core\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class LogMigration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnUpdate()
            ->nullOnDelete();
            $table->string('level', 10);
            $table->string('category', 50);
            $table->text('message');
            $table->longText('context')->nullable();
            $table->longText('adress_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
