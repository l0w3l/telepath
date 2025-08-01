<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('telepath_stored_updates', function (Blueprint $table) {
            $table->id();

            $table->json('instance');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('telepath_stored_updates');
    }
};
