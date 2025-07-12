<?php

namespace LaravelShared\Core\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class VisitorMigration
{
    public function up($fromXwms = false)
    {
        Schema::create('visitors', function (Blueprint $table) use ($fromXwms) {
            $table->id(); // Unieke ID
            $table->unsignedBigInteger('xwms_id')->nullable();

            if ($fromXwms === false){
                $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnUpdate()
                ->nullOnDelete();
            }

            $table->string('session_id')->nullable();
            $table->string('ip_address');
            $table->string('first_page')->nullable();
            $table->string('app')->nullable();
            $table->string('refer_url')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->json('location')->nullable();
            $table->unsignedInteger('pages_visited')->default(0);
            $table->unsignedInteger('session_duration')->default(0);
            $table->boolean('is_new_visitor')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['ip_address', 'session_id', 'session_duration', 'xwms_id']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
