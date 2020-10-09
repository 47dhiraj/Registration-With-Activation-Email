<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nickname', 20);
            $table->string('name', 20);
            $table->timestamps();
        });

        // we are using query builder to insert role manually... while creating our roles table
        DB::table('roles')->insert(['nickname' => 'admin', 'name' => 'Admin']);
        DB::table('roles')->insert(['nickname' => 'user', 'name' => 'User']);
    
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}


//Note : create_roles_table.php  i.e yo file ko timestamp aru sabbai file ko vanda earlier garauna parcha.... natra migration garda error aauxa