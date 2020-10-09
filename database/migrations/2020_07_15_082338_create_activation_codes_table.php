<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activation_codes', function (Blueprint $table) {
            $table->id();

            $table->unsignedbigInteger('user_id')->index();                     // laravel 7 ma yedi user table ko id ko type (i.e by default bigInteger hunxa)  ra  foreign key vako child table ko foreign key ko data type milena vani migration garda error aauxa.... so migration  garda foreign key contraints problem na aauna ko lagi ... both user table ko id ko data type and child table ko foreign key ko id ko data type same huna parcha
            $table->string('code');

            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activation_codes');
    }
}
