<?php
namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNamesToAmiTable extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_ami', function ($table) {
            $table->string('periode_name')->nullable();
            $table->string('prodi_name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_ami', function ($table) {
            $table->dropColumn('periode_name');
            $table->dropColumn('prodi_name');
        });
    }
}