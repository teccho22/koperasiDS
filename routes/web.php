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

// use Illuminate\Routing\Route;
use App\Models\Test;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login/checklogin', 'LoginController@checklogin');

Route::get('/customer', 'Customer\CustomerController@index')->name('customer');
Route::post('/customer/addCustomer', 'Customer\CustomerController@addCustomer');
Route::post('/customer/editCustomer', 'Customer\CustomerController@editCustomer');
Route::post('/customer/getEditData', 'Customer\CustomerController@getEditData');

Route::get('/loan/{id}', 'Loan\LoanController@index')->name('loan');
Route::post('/addLoan', 'Loan\LoanController@addLoan');
Route::post('/editLoan', 'Loan\LoanController@editLoan');
Route::post('/payLoan', 'Loan\LoanController@payLoan');
Route::post('/searchByLoanId','Loan\LoanController@searchByLoanId');

Route::get('/incoming', 'Transaction\IncomingController@index')->name('incoming');
