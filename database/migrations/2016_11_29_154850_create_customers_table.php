<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $oTable) {

            $oTable->increments('id');

            $oTable->string('first_name');
            $oTable->string('last_name');
            $oTable->date('dob')->index();
            $oTable->string('phone')->nullable();
            $oTable->string('email_address')->index();
            $oTable->text('remarks')->nullable();

            $oTable->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
