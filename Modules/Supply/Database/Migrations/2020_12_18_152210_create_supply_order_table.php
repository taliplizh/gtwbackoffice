<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSupplyOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'supply_order';
        Schema::create($tableName, function (Blueprint $table) {
            $table->id('ID')->comment('รหัส');
            $table->string('CODE')->comment('รหัส');
            $table->string('NOTE')->comment('เหตุผลขอเบิก');
            $table->integer('DEPARTMENT_ID')->comment('หน่วยงาน');
            $table->integer('PERSON_ID')->comment('เจ้าหน้าที่');
            $table->integer('STATUS_ID')->comment('สถานะ');
            $table->timestamps();
            
        });
        DB::statement("ALTER TABLE `$tableName` comment 'รายการขอเบิก'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_order');
    }
}
