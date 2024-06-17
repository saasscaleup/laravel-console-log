<?php

return [

    // enable or disable LCL
    'enabled' => env('LCL_ENABLED', true),

    // enable or disable laravel log listener 
    'log_enabled' => env('LCL_LOG_ENABLED', true),

    // log listener  for specific log type
    'log_type' => env('LCL_LOG_TYPE', 'info,error,warning,alert,critical,debug,success'), // Without space

    // log listener for specific word inside log messages
    'log_specific' => env('LCL_LOG_SPECIFIC', ''), // 'test' or 'foo' or 'bar' or leave empty '' to anyable any word

    // echo data loop in LCLController
    'interval' => env('LCL_INTERVAL', 1),

    // append logged user id in LCL response
    'append_user_id' => env('LCL_APPEND_USER_ID', true),

    // keep events log in database
    'keep_events_logs' => env('LCL_KEEP_EVENTS_LOGS', false),

    // Frontend pull invoke interval
    'server_event_retry' => env('LCL_SERVER_EVENT_RETRY', '2000'),

    // every 5 minutes cache expired, delete logs on next request
    'delete_log_interval' => env('LCL_DELETE_LOG_INTERVAL', 300), 

    /******* Frontend *******/

    // eanlbed console log on browser
    'js_console_log_enabled' => env('LCL_JS_CONSOLE_LOG_ENABLED', true),

];
