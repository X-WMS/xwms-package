<?php

namespace XWMS\Package\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CodeVerificationsTableV2
{
    public function up()
    {
        Schema::create('code_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xwms_id')->index()->nullable();
            $table->text('ip')->nullable();
            $table->text('category');
            $table->text('code');
            
            $table->text('status');
            $table->text('email')->nullable();
            $table->integer('attempt')->default(1);

            $table->string('ip_hash')->nullable();
            $table->string('email_hash')->nullable();
            $table->string('status_hash')->nullable();
            $table->string('category_hash')->nullable();
            $table->string('code_hash')->nullable();

            $table->timestamp('last_attempt')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('code_verifications');
    }
}
