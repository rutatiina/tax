<?php

Route::group(['middleware' => ['web', 'auth', 'tenant', 'service.accounting']], function() {

    Route::get('taxes/select-options', 'Rutatiina\Tax\Http\Controllers\TaxController@selectOptions');
    Route::resource('taxes', 'Rutatiina\Tax\Http\Controllers\TaxController');

});
