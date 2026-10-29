<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('tbl_employee',function(Blueprint $table){
   $table->bigInteger('id')->primary(); $table->integer('company_id')->nullable(); $table->string('emp_id',50)->nullable();
   $table->string('emp_name',100)->nullable(); $table->string('designation',100)->nullable(); $table->integer('emp_desig')->nullable();
   $table->string('gender',10)->nullable(); $table->date('date_of_join')->nullable(); $table->date('evaluation')->nullable();
   $table->string('remarks',100)->nullable(); $table->string('present_grade',10)->nullable(); $table->decimal('present_salary',10,2)->nullable();
   $table->string('card_id',50)->nullable(); $table->integer('pin')->nullable(); $table->integer('status')->nullable();
   $table->smallInteger('emp_type')->nullable(); $table->string('created_by',50)->nullable(); $table->integer('entry_by')->nullable(); $table->timestamps();
  });
  Schema::create('tbl_attendance_log',function(Blueprint $table){
   $table->bigInteger('id')->primary(); $table->string('company_id',100)->nullable(); $table->date('Date')->nullable();
   $table->string('rfid',100)->nullable(); $table->string('emp_id',50)->nullable()->index(); $table->timestamp('date_time')->nullable()->index();
   $table->string('terminal_id',100)->nullable(); $table->string('in_out',10)->nullable(); $table->string('attendance_type',100)->nullable();
   $table->integer('status')->nullable(); $table->timestamps(); $table->index(['Date','emp_id']);
  });
 }
 public function down(): void { Schema::dropIfExists('tbl_attendance_log'); Schema::dropIfExists('tbl_employee'); }
};
