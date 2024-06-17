<?php

Route::group(
    [
        'namespace' => 'Saasscaleup\LCL\Http\Controllers',
        'prefix' => 'lcl'
    ],
    static function () {

        Route::get('stream_console_log', 'LCLController@stream')->name('lcl-stream-log');
    }
);
