<?php

use App\Models\SocialLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('description')->nullable;
            $table->timestamps();
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });
        Schema::create('sectors',function (Blueprint $table){
            $table->tinyIncrements('id');
            $table->string('name');
            $table->string('key')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('sector_id');
            $table->unsignedBigInteger('author_id');
            $table->boolean('post_author')->default(false);
            $table->boolean('guest_user')->default(false);
            $table->string('title');
            $table->string('description');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('sector_id')->references('id')->on('sectors');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('social_logs',function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('social_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('action',[SocialLog::ACTION_CREATE,SocialLog::ACTION_UPDATE,SocialLog::ACTION_DESTROY]);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('social_id')->references('id')->on('social')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_logs');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('sectors');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('roles');
    }
}
