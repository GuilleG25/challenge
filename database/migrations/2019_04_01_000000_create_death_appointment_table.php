<?php
    
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    
    class CreateDeathAppointmentTable extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::create('death_appointment', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date');
                $table->time('time');
                $table->string('name');
                $table->string('email')->unique();
    
                $table->unique(['date', 'time']);
            });
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::dropIfExists('death_appointment');
        }
    }
