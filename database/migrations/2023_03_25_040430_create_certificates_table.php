<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWpCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->string('name');
            $table->string('lastname');
            $table->string('document_type');
            $table->string('document_number')->unique();
            $table->string('phone');
            $table->string('email');
            $table->string('photo_url');
            $table->string('certificate_number')->unique();
            $table->string('certificate_url');
            $table->timestamp('certificate_date');
            $table->timestamp('certificate_expiration_date');
            $table->string('certificate_status');
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
        Schema::dropIfExists('certificates');
    }
}
