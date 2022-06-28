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

Route::get('/login', '\App\Http\Controllers\LoginController@index')->name('login');
Route::post('/login/checklogin', '\App\Http\Controllers\LoginController@checklogin')->name('checkLogin');

Route::get('/customer', '\App\Http\Controllers\Customer\CustomerController@index')->name('customer');
Route::post('/customer/addCustomer', '\App\Http\Controllers\Customer\CustomerController@addCustomer');
Route::post('/customer/editCustomer', '\App\Http\Controllers\Customer\CustomerController@editCustomer');
Route::post('/customer/getEditData', '\App\Http\Controllers\Customer\CustomerController@getEditData');
Route::post('/searchCustomer', '\App\Http\Controllers\Customer\CustomerController@searchCustomer');

Route::get('/loan/{id}', '\App\Http\Controllers\Loan\LoanController@index')->name('loan');
Route::post('/addLoan', '\App\Http\Controllers\Loan\LoanController@addLoan');
Route::post('/editLoan', '\App\Http\Controllers\Loan\LoanController@editLoan');
Route::post('/payLoan', '\App\Http\Controllers\Loan\LoanController@payLoan');
Route::post('/searchByLoanId','\App\Http\Controllers\Loan\LoanController@searchByLoanId');
Route::post('/blacklist','\App\Http\Controllers\Loan\LoanController@blacklist');
Route::post('/unblacklist','\App\Http\Controllers\Loan\LoanController@unblacklist');

// transaction
Route::get('/incoming', '\App\Http\Controllers\Transaction\IncomingController@index')->name('incoming');
Route::post('/addIncoming', '\App\Http\Controllers\Transaction\IncomingController@addIncoming');
Route::post('/editIncoming', '\App\Http\Controllers\Transaction\IncomingController@editIncoming');
Route::post('/deleteIncoming', '\App\Http\Controllers\Transaction\IncomingController@deleteIncoming');

Route::get('/outgoing', '\App\Http\Controllers\Transaction\OutgoingController@index')->name('outgoing');
Route::post('/addOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@addOutgoing');
Route::post('/editOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@editOutgoing');
Route::post('/deleteOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@deleteOutgoing');

Route::get('/transaction', '\App\Http\Controllers\Transaction\TransactionController@index')->name('transaction');
Route::post('/addTransaction', '\App\Http\Controllers\Transaction\TransactionController@addTransaction');
Route::post('/editTransaction', '\App\Http\Controllers\Transaction\TransactionController@editTransaction');
Route::post('/deleteTransaction', '\App\Http\Controllers\Transaction\TransactionController@deleteTransaction');

Route::get('/disbursement', '\App\Http\Controllers\Report\DisbursementController@index')->name('disbursement');
Route::get('/searchDisbursement', '\App\Http\Controllers\Report\DisbursementController@searchDisbursement')->name('searchDisbursement');

Route::get('/npl', '\App\Http\Controllers\Report\NplController@index')->name('npl');
Route::get('/searchNpl', '\App\Http\Controllers\Report\NplController@searchNpl')->name('searchNpl');

