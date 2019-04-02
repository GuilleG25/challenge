<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    
    class DeathAppointment extends Model {
        /**
         * Not used timestemps, set false.
         */
        
        public $timestamps = false;
        public $table = 'death_appointment';
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'date',
            'time',
            'name',
            'email',
        ];
    }
