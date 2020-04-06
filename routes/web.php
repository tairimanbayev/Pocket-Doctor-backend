<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('admin')->name('admin.')->group(function() {

    Route::get('/', 'Admin\DashboardController@index')->name('dashboard');

    Route::resource('user', 'Admin\UserController');
    Route::resource('card', 'Admin\CardController');
    Route::resource('clinic', 'Admin\ClinicController');
    Route::resource('doctor', 'Admin\DoctorController');
    Route::resource('address', 'Admin\AddressController');
    Route::resource('faq', 'Admin\FaqController');
    Route::resource('payment_card', 'Admin\PaymentCardController');
    Route::resource('question', 'Admin\QuestionController');
    Route::resource('price', 'Admin\PriceController');
    Route::resource('chat', 'Admin\ChatController');
    Route::resource('visit', 'Admin\VisitController');
    Route::post('chat/set_admin_token', 'Admin\ChatController@set_token')->name('admin.set_token');

});
