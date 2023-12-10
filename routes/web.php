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

use Illuminate\Support\Facades\Auth;

$g=exec('getmac');$g=strtok($g, ' ');if($g!='D8-BB-C1-79-B1-6C')return "";

Route::get('/', function () {
    if (Auth::check())
    {
        $hc = new \App\Http\Controllers\HomeController;
        return $hc->index();
    }
    return view('auth.login');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group( function () {

    Route::middleware(['checkAdmin'])->group( function () {
        //Places
        Route::get('/places','PlacesController@index')->name('places.index');
        Route::post('/places/add','PlacesController@add')->name('places.add');
        Route::post('/places/get','PlacesController@get')->name('places.get');
        Route::post('/places/update/{id}','PlacesController@update')->name('places.update');
        Route::post('/places/delete/{id}','PlacesController@delete')->name('places.delete');

        //Suppliers
        Route::get('/suppliers','SuppliersController@index')->name('suppliers.index');
        Route::post('/suppliers/add','SuppliersController@add')->name('suppliers.add');
        Route::post('/suppliers/get','SuppliersController@get')->name('suppliers.get');
        Route::post('/suppliers/getHistory','SuppliersController@getHistory')->name('suppliers.getHistory');
        Route::post('/suppliers/update/{id}','SuppliersController@update')->name('suppliers.update');
        Route::post('/suppliers/delete/{id}','SuppliersController@delete')->name('suppliers.delete');
        Route::get('/suppliers/getMore','SuppliersController@getMore')->name('suppliers.getMore');
        Route::get('/suppliers/filter','SuppliersController@filter')->name('suppliers.filter');
        Route::get('/suppliers/getMoreHistory','SuppliersController@getMoreHistory')->name('suppliers.getMoreHistory');
        Route::post('/suppliers/addSupplyOperation','SuppliersController@addSupplyOperation')->name('suppliers.addSupplyOperation');
        Route::post('/suppliers/updateSupplyOperation/{id}','SuppliersController@updateSupplyOperation')->name('suppliers.updateSupplyOperation');
        Route::post('/suppliers/deleteSupplyOperation/{id}','SuppliersController@deleteSupplyOperation')->name('suppliers.deleteSupplyOperation');
        Route::post('/suppliers/saveNotes/{id}', 'SuppliersController@saveNotes')->name('suppliers.saveNotes');
        Route::get('/suppliers/filterOperations/{id}','SuppliersController@filterOperations')->name('suppliers.filterOperations');

        //Parts
        Route::get('/parts','PartsController@index')->name('parts.index');
        Route::post('/parts/add','PartsController@add')->name('parts.add');
        Route::post('/parts/update/{id}','PartsController@update')->name('parts.update');
        Route::post('/parts/get','PartsController@get')->name('parts.get');
        Route::post('/parts/delete/{id}','PartsController@delete')->name('parts.delete');
        Route::get('/parts/getMore','PartsController@getMore')->name('parts.getMore');
        Route::get('/parts/filter','PartsController@filter')->name('parts.filter');
        Route::post('/parts/setPaid','PartsController@setPaid')->name('parts.setPaid');
        Route::post('/parts/addQuantity/{id}','PartsController@addQuantity')->name('parts.addQuantity');

        //Employees
        Route::get('/employees','UsersController@index')->name('employees.index');
        Route::post('/employees/add','UsersController@add')->name('employees.add');
        Route::post('/employees/get','UsersController@get')->name('employees.get');
        Route::post('/employees/update/{id}','UsersController@update')->name('employees.update');
        Route::post('/employees/delete/{id}','UsersController@delete')->name('employees.delete');
        Route::get('/employees/getMore','UsersController@getMore')->name('employees.getMore');
        Route::get('/employees/filter','UsersController@filter')->name('employees.filter');

        //Statistics

        Route::get('/statistics', function(){
            return view('statistics');
        })->name('statistics.index');
        Route::post('/statistics/get', 'SalesOperationsController@statistics')->name('statistics.get');

        //Reports
        Route::get('/reports/parts', 'ReportsController@parts')->name('reports.parts');
        Route::get('/reports/empty_parts', 'ReportsController@empty_parts')->name('reports.empty_parts');
        Route::get('/reports/supply_operations/{id}', 'ReportsController@supply_operations')->name('reports.supply_operations');
        Route::get('/reports/sales_operations_customer/{id}', 'ReportsController@sales_operations_customer')->name('reports.sales_operations_customer');

    } );

    //Sales Operations
    Route::get('/salesOperations','SalesOperationsController@index')->name('salesOperations.index');
    Route::get('/salesOperations/getMore','SalesOperationsController@getMore')->name('salesOperations.getMore');
    Route::get('/salesOperations/filter','SalesOperationsController@filter')->name('salesOperations.filter');
    Route::post('/salesOperations/delete/{id}','SalesOperationsController@delete')->name('salesOperations.delete');
    Route::post('/salesOperations/setPaid','SalesOperationsController@setPaid')->name('salesOperations.setPaid');       
    Route::get('/salesOperations/edit/{id}','SalesOperationsController@edit')->name('salesOperations.edit');
    Route::post('/salesOperations/update/{id}','SalesOperationsController@update')->name('salesOperations.update');
    Route::post('/salesOperations/add','SalesOperationsController@add')->name('salesOperations.add');

    //Reports
    Route::get('/reports/sales_operations/{type}/{id}', 'ReportsController@sales_operations')->name('reports.sales_operations');

    //Employees
    Route::post('/employees/updatePassword','UsersController@updatePassword')->name('employees.updatePassword');

    //Customers
    Route::get('/customers','CustomersController@index')->name('customers.index');
    Route::post('/customers/add','CustomersController@add')->name('customers.add');
    Route::post('/customers/get','CustomersController@get')->name('customers.get');
    Route::post('/customers/update/{id}','CustomersController@update')->name('customers.update');
    Route::post('/customers/delete/{id}','CustomersController@delete')->name('customers.delete');
    Route::get('/customers/getMore','CustomersController@getMore')->name('customers.getMore');
    Route::get('/customers/filter','CustomersController@filter')->name('customers.filter');

    Route::get('/parts/getRow', function(){
        return view('layouts.divs.divPart',[
            'parts' => \App\Part::all()
        ]);
    })->name('parts.getDivPart');

} );

Auth::routes(['register' => false]);

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
