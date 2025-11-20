<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerAkreditasiProgramStudi extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_akreditasi_program_studi', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('program_studi', 225)->nullable();
            $table->string('status_peringkat', 225)->nullable();
            $table->string('nomor_sk', 225)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->date('kadaluarsa_sk')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_akreditasi_program_studi');
    }
}
