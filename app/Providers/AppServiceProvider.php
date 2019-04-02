<?php
    
    namespace App\Providers;
    
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\ServiceProvider;
    
    class AppServiceProvider extends ServiceProvider {
        /**
         * Register any application services.
         *
         * @return void
         */
        public function register() {
        
        }
        
        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot() {
            Validator::extend('unique_custom', function ($attribute, $value, $parameters) {
                $result = DB::table($parameters[0])->where($attribute, $value)->where($parameters[1], $parameters[2])->count();
        
                return $result === 0;
            });
        }
    }
