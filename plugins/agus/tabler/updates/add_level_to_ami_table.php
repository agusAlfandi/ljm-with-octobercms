<?php
namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddLevelToAmiTable extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_ami', function ($table) {
            $table->string('level')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_ami', function ($table) {
            $table->dropColumn('level');
        });
    }
}
