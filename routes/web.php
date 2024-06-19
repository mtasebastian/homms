<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', function(){
    if(Auth::user()){
        return view('dashboard');
    }
    return view('login');
})->name('login');

Route::post('/checklogin',  'App\Http\Controllers\Controller@checklogin')->name('checklogin');
Route::get('/logout',  'App\Http\Controllers\Controller@logout')->name('logout');

Route::get('/getqrcode',  'App\Http\Controllers\RequestsController@getqrcode')->name('getqrcode');

// Registration
Route::get('/register/index',  'App\Http\Controllers\RegisterController@index')->name('register');
Route::post('/register/save',  'App\Http\Controllers\RegisterController@register')->name('register.save');

Route::get('/province', 'App\Http\Controllers\SettingsController@loadcities')->name('load_cities');
Route::get('/city', 'App\Http\Controllers\SettingsController@loadbrgys')->name('load_brgys');
Route::get('/filter_residents', 'App\Http\Controllers\SettingsController@filterresidents')->name('filter_residents');

Route::group(['middleware' => ['auth']], function (){
    // Dashboard
    Route::get('/', 'App\Http\Controllers\IndexController@index')->name('dashboard');

    Route::prefix('/financials')->group(function(){
        Route::get('/', 'App\Http\Controllers\FinancialsController@index')->name('financials.index');
        Route::get('/searchresident', 'App\Http\Controllers\FinancialsController@searchresident')->name('financials.search_resident');
        Route::post('/generatebills', 'App\Http\Controllers\FinancialsController@generatebills')->name('financials.generate_bills');
        Route::post('/addpayment', 'App\Http\Controllers\FinancialsController@addpayment')->name('financials.add_payment');
        Route::get('/paymentlist', 'App\Http\Controllers\FinancialsController@paymentlist')->name('financials.payment_list');
    });

    Route::prefix('/requests')->group(function(){
        Route::get('/', 'App\Http\Controllers\RequestsController@index')->name('requests.index');
        Route::post('/add', 'App\Http\Controllers\RequestsController@addrequest')->name('requests.add_request');
        Route::post('/update', 'App\Http\Controllers\RequestsController@updaterequest')->name('requests.update_request');
        Route::post('/delete', 'App\Http\Controllers\RequestsController@deleterequest')->name('requests.delete_request');
    });

    Route::prefix('/residents')->group(function(){
        Route::get('/', 'App\Http\Controllers\ResidentsController@index')->name('residents.index');
        Route::post('/add', 'App\Http\Controllers\ResidentsController@addresident')->name('residents.add_resident');
        Route::post('/update', 'App\Http\Controllers\ResidentsController@updateresident')->name('residents.update_resident');
        Route::post('/delete', 'App\Http\Controllers\ResidentsController@deleteresident')->name('residents.delete_resident');
    });

    Route::prefix('/complaints')->group(function(){
        Route::get('/', 'App\Http\Controllers\ComplaintsController@index')->name('complaints.index');
        Route::post('/add', 'App\Http\Controllers\ComplaintsController@addcomplaint')->name('complaints.add_complaint');
        Route::post('/update', 'App\Http\Controllers\ComplaintsController@updatecomplaint')->name('complaints.update_complaint');
        Route::post('/delete', 'App\Http\Controllers\ComplaintsController@deletecomplaint')->name('complaints.delete_complaint');
    });

    Route::prefix('/visitors')->group(function(){
        Route::get('/', 'App\Http\Controllers\VisitorsController@index')->name('visitors.index');
        Route::post('/add', 'App\Http\Controllers\VisitorsController@addvisitor')->name('visitors.add_visitor');
        Route::post('/timeout', 'App\Http\Controllers\VisitorsController@timeoutvisitor')->name('visitors.timeout_visitor');
    });

    // Settings
    Route::prefix('/settings')->group(function(){
        Route::get('/users', 'App\Http\Controllers\SettingsController@users')->name('settings.users');
        Route::post('/users/add', 'App\Http\Controllers\SettingsController@adduser')->name('settings.add_user');
        Route::post('/users/update', 'App\Http\Controllers\SettingsController@updateuser')->name('settings.update_user');
        Route::post('/users/delete', 'App\Http\Controllers\SettingsController@deleteuser')->name('settings.delete_user');

        Route::get('/roles', 'App\Http\Controllers\SettingsController@roles')->name('settings.roles');
        Route::post('/roles/add', 'App\Http\Controllers\SettingsController@addrole')->name('settings.add_role');
        Route::post('/roles/update', 'App\Http\Controllers\SettingsController@updaterole')->name('settings.update_role');
        Route::post('/roles/delete', 'App\Http\Controllers\SettingsController@deleterole')->name('settings.delete_role');

        Route::get('/referentials', 'App\Http\Controllers\SettingsController@referentials')->name('settings.referentials');
        Route::post('/referentials/add', 'App\Http\Controllers\SettingsController@addreferential')->name('settings.add_referential');
        Route::post('/referentials/update', 'App\Http\Controllers\SettingsController@updatereferential')->name('settings.update_referential');
        Route::post('/referentials/delete', 'App\Http\Controllers\SettingsController@deletereferential')->name('settings.delete_referential');

        Route::get('/setup', 'App\Http\Controllers\SettingsController@setup')->name('settings.system_setup');
        Route::get('/setup/financials', 'App\Http\Controllers\SettingsController@financials')->name('settings.financials');
        Route::post('/setup/financials/save', 'App\Http\Controllers\SettingsController@savefinancial')->name('settings.save_financial');
        Route::post('/setup/financials/delete', 'App\Http\Controllers\SettingsController@deletefinancial')->name('settings.delete_financial');
        Route::post('/setup/saveref', 'App\Http\Controllers\SettingsController@saverefsetup')->name('settings.save_refential_setup');
    });
});
