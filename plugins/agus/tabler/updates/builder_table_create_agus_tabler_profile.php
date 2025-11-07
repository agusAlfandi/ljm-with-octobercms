<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerProfile extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_profile', function($table)
        {
            $table->increments('id')->unsigned();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('sasaran')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_profile');
    }
}
