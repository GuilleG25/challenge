<?php
    
    use Illuminate\Http\Request;
    
    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */
    
    Route::post('/', [
        'as'   => 'api.index',
        'uses' => 'Api\ApiController@index',
    ]);
    
    Route::group([
        'middleware' => [
            'api',
        ],
        'prefix'     => 'appointment',
    ], function () {
        
        Route::post('store', [
            'as'   => 'api.store',
            'uses' => 'Api\ApiController@store',
        ]);
        
        Route::get('date/{date}', [
            'as'   => 'api.date',
            'uses' => 'Api\ApiController@date',
        ]);
        
    });
    

    
    

