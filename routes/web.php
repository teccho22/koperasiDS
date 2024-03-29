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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});



// Route::group(['middleware' => ['guest']], function() {
    Route::get('/login', '\App\Http\Controllers\LoginController@index')->name('login');
    Route::get('/logout', '\App\Http\Controllers\LoginController@logout')->name('logout');
    Route::post('/login/checklogin', '\App\Http\Controllers\LoginController@checklogin')->name('checkLogin');

    Route::get('/customer', '\App\Http\Controllers\Customer\CustomerController@index')->name('customer');
    Route::post('/customer/addCustomer', '\App\Http\Controllers\Customer\CustomerController@addCustomer');
    Route::post('/customer/editCustomer', '\App\Http\Controllers\Customer\CustomerController@editCustomer');
    Route::post('/customer/getEditData', '\App\Http\Controllers\Customer\CustomerController@getEditData');
    Route::get('/customer/deleteCustomer', '\App\Http\Controllers\Customer\CustomerController@deleteCustomer')->name('deleteCustomer');
    Route::post('/searchCustomer', '\App\Http\Controllers\Customer\CustomerController@searchCustomer');
    Route::post('/customer/paginate', '\App\Http\Controllers\Customer\CustomerController@paginate');

    Route::get('/loan/{id}', '\App\Http\Controllers\Loan\LoanController@index')->name('loan');
    Route::post('/addLoan', '\App\Http\Controllers\Loan\LoanController@addLoan');
    Route::post('/editLoan', '\App\Http\Controllers\Loan\LoanController@editLoan');
    Route::post('/payLoan', '\App\Http\Controllers\Loan\LoanController@payLoan');
    Route::post('/searchByLoanId','\App\Http\Controllers\Loan\LoanController@searchByLoanId');
    Route::get('/deleteLoan','\App\Http\Controllers\Loan\LoanController@deleteLoan')->name('deleteLoan');
    Route::post('/blacklist','\App\Http\Controllers\Loan\LoanController@blacklist');
    Route::post('/unblacklist','\App\Http\Controllers\Loan\LoanController@unblacklist');
    Route::post('/generateSp','\App\Http\Controllers\Loan\LoanController@generateSp');
    Route::post('/generateAggrementLetter','\App\Http\Controllers\Loan\LoanController@generateAggrementLetter');

    // transaction
    Route::get('/incoming', '\App\Http\Controllers\Transaction\IncomingController@index')->name('incoming');
    Route::post('/addIncoming', '\App\Http\Controllers\Transaction\IncomingController@addIncoming');
    Route::post('/editIncoming', '\App\Http\Controllers\Transaction\IncomingController@editIncoming');
    Route::post('/deleteIncoming', '\App\Http\Controllers\Transaction\IncomingController@deleteIncoming');
    Route::post('/searchIncoming', '\App\Http\Controllers\Transaction\IncomingController@searchIncoming');

    Route::get('/outgoing', '\App\Http\Controllers\Transaction\OutgoingController@index')->name('outgoing');
    Route::post('/addOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@addOutgoing');
    Route::post('/editOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@editOutgoing');
    Route::post('/deleteOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@deleteOutgoing');
    Route::post('/searchOutgoing', '\App\Http\Controllers\Transaction\OutgoingController@searchOutgoing');

    Route::get('/transaction', '\App\Http\Controllers\Transaction\TransactionController@index')->name('transaction');
    Route::post('/addTransaction', '\App\Http\Controllers\Transaction\TransactionController@addTransaction');
    Route::post('/editTransaction', '\App\Http\Controllers\Transaction\TransactionController@editTransaction');
    Route::post('/deleteTransaction', '\App\Http\Controllers\Transaction\TransactionController@deleteTransaction');
    Route::post('/searchTransaction', '\App\Http\Controllers\Transaction\TransactionController@searchTransaction');

    // report
    Route::get('/disbursement', '\App\Http\Controllers\Report\DisbursementController@index')->name('disbursement');
    Route::get('/searchDisbursement', '\App\Http\Controllers\Report\DisbursementController@searchDisbursement')->name('searchDisbursement');
    Route::get('/generateDisbursementExcel', '\App\Http\Controllers\Report\DisbursementController@generateDisbursementExcel')->name('generateDisbursementExcel');

    Route::get('/npl', '\App\Http\Controllers\Report\NplController@index')->name('npl');
    Route::get('/searchNpl', '\App\Http\Controllers\Report\NplController@searchNpl')->name('searchNpl');

    Route::get('/businessGrowth', '\App\Http\Controllers\Report\BussinesGrowthController@index')->name('businessGrowth');
    Route::get('/chart', '\App\Http\Controllers\Report\BussinesGrowthController@chart')->name('chart');
    Route::get('/generateChart', '\App\Http\Controllers\Report\BussinesGrowthController@generateChart')->name('generateChart');
// });
// Auth::routes();
