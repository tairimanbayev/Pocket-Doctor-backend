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

Route::get('faq', 'Api\HomeController@faq');

Route::middleware('auth:api')->group(function () {

    Route::resource('visit', 'Api\VisitController', ['only' => ['index', 'store', 'show']]);
    Route::post('visit/{visit}/make_illness', 'Api\VisitController@make_illness');
    Route::get('visit/{visit}/illnesses', 'Api\VisitController@illnesses');
    Route::post('visit/{visit}/accept', 'Api\VisitController@accept');
    Route::post('visit/{visit}/skip', 'Api\VisitController@skip');
    Route::post('visit/{visit}/finish', 'Api\VisitController@finish');
    Route::post('visit/{visit}/feedback', 'Api\VisitController@feedback');
    Route::get('visit/{visit}/doctor_location', 'Api\VisitController@doctor_location');
    Route::post('visit/questions', 'Api\VisitController@questions');
    Route::get('illnesses', 'Api\VisitController@all_illnesses');
    Route::post('illnesses/{illness}', 'Api\VisitController@update_illness');
    Route::delete('illnesses/{illness}', 'Api\VisitController@delete_illness');
    Route::any('price', 'Api\VisitController@price');

    Route::resource('medicine', 'Api\MedicineController');

    Route::resource('card', 'Api\CardController');
    Route::get('card/{card}/photo', 'Api\CardController@photo')->name('api.card.photo');
    Route::post('card/{card}/photo', 'Api\CardController@updatePhoto');
    Route::get('card/{card}/illnesses', 'Api\CardController@illnesses');

    Route::get('cabinet/profile', 'Api\CabinetController@profile');
    Route::put('cabinet/profile', 'Api\CabinetController@updateProfile');
    Route::post('cabinet/set_card/{card}', 'Api\CabinetController@setCard');
    Route::post('cabinet/unset_card', 'Api\CabinetController@unsetCard');
    Route::post('cabinet/update_location', 'Api\CabinetController@update_location');

    Route::get('chat', 'Api\ChatController@index');
    Route::post('chat', 'Api\ChatController@store');
});

Route::prefix('auth')->group(function() {
    Route::post('send_sms_token', 'Api\AuthController@send_sms_token');
    Route::post('login', 'Api\AuthController@login');
});
