<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConverstionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('converstions', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('api_message_id', 100);
            $table->string('name_space', 255);
            $table->string('auth_name', 100);
            $table->text('comment_text', 100);
            $table->string('tags', 255);
            $table->integer('up_vote_count');
            $table->integer('down_vote_count');
            $table->integer('abuse_vote_count');
            $table->integer('reply_count');
            $table->json('replies_text');
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
        Schema::dropIfExists('converstions');
    }
}
