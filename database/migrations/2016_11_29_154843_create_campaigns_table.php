<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $oTable) {

            $oTable->increments('id');

            $oTable->string('title');
            $oTable->string('description')->nullable();
            $oTable->decimal('budget', 9, 2);

            $oTable->dateTime('start_dt')->nullable();
            $oTable->dateTime('end_dt')->nullable();
            $oTable->boolean('reminder')->nullable();

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
        Schema::dropIfExists('campaigns');
    }
}
