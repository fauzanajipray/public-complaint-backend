<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_verify', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token');
            $table->timestamps();
        });
   
        // Schema::table('users', function (Blueprint $table) {
        //     $table->boolean('is_email_verified')->default(0);
        // });
        
        // Schema::table('users', function (Blueprint $table) {
        //     $table->tinyInteger('is_email_verified')->default(2);
        // });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifies');
    }
}
