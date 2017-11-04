<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id');
            $table->integer('docket');
            $table->string('case_type',25);
            $table->string('probate_date',15);
            $table->string('death_date',15);
            $table->string('deceased_name',100);
            $table->string('deceased_address',150);
            $table->string('deceased_city',100);
            $table->string('deceased_state',2);
            $table->string('deceased_zip',10); // zip code maybe 12345-1234
            $table->string('probate_name',100);
            $table->string('probate_address',150);
            $table->string('probate_city',100);
            $table->string('probate_state',2);
            $table->string('proabte_zip',100);
            $table->date('input_date');
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
        Schema::dropIfExists('report');
    }
}


 /*QLSTATE[42000]: Syntax error or access violation: 1075 Incorrect table definition; there can be only one auto column and it must be defined as a key 
  (SQL: create table `report` (`id` int unsigned not null auto_increment primary key, `file_id` int not null 
auto_increment primary key,
`docket` int not null auto_increment primary key,
`case_type` varchar(25) not null,
`probate_date` date not null,
`death_date` date not null,
`deceased_name` varchar(100) not null,
`deceased_address` varchar(150) not null,
`deceased_city` varchar(100) not null,
`deceased_state,2` varchar(255) not null, 
`deceased_zip` varchar(10) not null,
`probate_name` varchar(100) not null,
`probate_address` varchar(150) not null,
`probate_city` varchar(100) not null,
`probate_state` varchar(255) not null,
`proabte_zip` varchar(100) not null,
`input_date` date not null,
`created_at` timestamp null,
`updated_at`  
   timestamp null) default character set utf8mb4 collate utf8mb4_unicode_ci)  */