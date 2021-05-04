<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_mentions', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->text('text');
            $table->unsignedBigInteger('users_oldest_id')->default(0);
            $table->unsignedBigInteger('users_newest_id')->default(0);
            $table->unsignedBigInteger('username_appearance_count')->default(0);
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
        Schema::dropIfExists('user_mentions');
    }
}
