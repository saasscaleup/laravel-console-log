![Main Window two](https://github.com/saasscaleup/laravel-stream-log/blob/master/lcl-saasscaleup.png?raw=true)

<h3 align="center">Easily stream your Laravel application logs to the browser console tab (console.log) in real-time using server-sent event (SSE)</h3>

<h4 align="center">
  <a href="https://youtube.com/@ScaleUpSaaS">Youtube</a>
  <span> · </span>
  <a href="https://twitter.com/ScaleUpSaaS">Twitter</a>
  <span> · </span>
  <a href="https://facebook.com/ScaleUpSaaS">Facebook</a>
  <span> · </span>
  <a href="https://buymeacoffee.com/scaleupsaas">By Me a Coffee</a>
</h4>

<p align="center">
   <a href="https://packagist.org/packages/saasscaleup/laravel-console-log">
      <img src="https://poser.pugx.org/saasscaleup/laravel-console-log/v/stable.png" alt="Latest Stable Version">
  </a>

  <a href="https://packagist.org/packages/saasscaleup/laravel-console-log">
      <img src="https://poser.pugx.org/saasscaleup/laravel-console-log/downloads.png" alt="Total Downloads">
  </a>

  <a href="https://packagist.org/packages/saasscaleup/laravel-console-log">
    <img src="https://poser.pugx.org/saasscaleup/laravel-console-log/license.png" alt="License">
  </a>
</p>

## ✨ Features

- **Easily stream your Backend events from your Controllers \ Events \ Models \ Etc...  to Frontend browser console tab(`console.log')(data)`).** 
- **Easily stream your Application Logs (`storage/logs/laravel.log`)to Frontend browser console tab (`console.log')(data)`).**

<br>

![banner](https://github.com/saasscaleup/laravel-console-log/blob/master/lcl-demo.gif?raw=true)
<br>


## Requirements

 - PHP >= 7
 - Laravel >= 5

## Installation

### Install composer package (dev)

Via Composer - Not recommended for production environment

``` bash
$ composer require --dev saasscaleup/laravel-console-log
```

#### For Laravel < 5.5

Add Service Provider to `config/app.php` in `providers` section
```php
Saasscaleup\LCL\LCLServiceProvider::class,
```

Add Facade to `config/app.php` in `aliases` section
```php
'LCL' => Saasscaleup\LCL\Facades\LCLFacade::class,
```


---

### Publish package's config, migration and view files


Publish package's config, migration and view files by running below command:

```bash
$ php artisan vendor:publish --provider="Saasscaleup\LCL\LCLServiceProvider"
```

### Run migration command

Run `php artisan migrate` to create `stream_console_logs` table.

```bash
$ php artisan migrate
```

## Setup Laravel Console Log -> LCL 

Aadd this in your main view/layout (usually `layout/app.blade.php`) file before </body>:

```php
@include('lcl::view')
```

```php
<body>
...
@include('lcl::view')
</body>
```

## Configuration

Configuration is done via environment variables or directly in the configuration file (`config/lcl.php`).

```
<?php

return [

    // enable or disable LCL
    'enabled' => env('LCL_ENABLED', true),

    // enable or disable laravel log listener 
    'log_enabled' => env('LCL_LOG_ENABLED', true),

    // log listener  for specific log type
    'log_type' => env('LCL_LOG_TYPE', 'info,error,warning,alert,critical,debug'), // Without space

    // log listener for specific word inside log messages
    'log_specific' => env('LCL_LOG_SPECIFIC', ''), // 'test' or 'foo' or 'bar' or leave empty '' to disable this feature

    // echo data loop in LCLController
    'interval' => env('LCL_INTERVAL', 1),

    // append logged user id in LCL response
    'append_user_id' => env('LCL_APPEND_USER_ID', true),

    // keep events log in database
    'keep_events_logs' => env('LCL_KEEP_EVENTS_LOGS', false),

    // Frontend pull invoke interval
    'server_event_retry' => env('LCL_SERVER_EVENT_RETRY', '2000'),

    // every 10 minutes cache expired, delete logs on next request
    'delete_log_interval' => env('LCL_DELETE_LOG_INTERVAL', 600), 

    /******* Frontend *******/

    // eanlbed console log on browser
    'js_console_log_enabled' => env('LCL_JS_CONSOLE_LOG_ENABLED', true),

];
```

## Usage

Syntax:

```php
/**
* @param string $message : notification message
* @param string $type : alert, success, error, warning, info, debug, critical, etc...
* @param string $event : Type of event such as "EmailSent", "UserLoggedIn", etc
 */
LCLFacade::notify($message, $type = 'info', $event = 'stream-console-log')
```

To show popup notifications on the screen, in your controllers/event classes, you can  do:

```php
use Saasscaleup\LCL\Facades\LCLFacade;

public function myFunction()
{

    LCLFacade::notify('Invoke stream log via Facade 1');

    // Some code here

    LCLFacade::notify('Invoke stream log via Facade 2');

    // Some code here

    LCLFacade::notify('Invoke stream log via Facade 3');
    
    // or via helper
    stream_log('Invoke stream log via helper 1');
    stream_log('Invoke stream log via helper 2');     
    stream_log('Invoke stream log via helper 3');


    // or using your application
    \Log::info('Invoke stream log via application log 1');
    \Log::error('Invoke stream log via application log 2');
    \Log::debug('Invoke stream log via application log 3');

}
```



## Customizing Notification Library

You can also, customize this by modifying code in `resources/views/vendor/lcl/view.blade.php` file.

## Customizing LCL Events

By default, package uses `stream-console-log` event type for streaming response:


```php
LCLFacade::notify($message, $type = 'info', $event = 'stream-console-log')
```

Notice `$event = 'stream-console-log'`. You can customize this, let's say you want to use `UserPurchase` as SSE event type:

```php
use Saasscaleup\LCL\Facades\LCLFacade;

public function myMethod()
{
    LCLFacade::notify('User purchase plan - step 1', 'info', 'UserPurchase');
    
    // or via helper
    stream_console_log('User purchase plan - step 1', 'info', 'UserPurchase');
}
```

Then you need to handle this in your view yourself like this:

```javascript
<script>
var es = new EventSource("{{route('lcl-stream-log')}}");

es.addEventListener("UserPurchase", function (e) {
    var data = JSON.parse(e.data);
    alert(data.message);
}, false);

</script>
```

## Inspired By

[open-source](https://github.com/saasscaleup/laravel-stream-log)

## License

Please see the [MIT](license.md) for more information.


## Support 🙏😃
  
 If you Like the tutorial and you want to support my channel so I will keep releasing amzing content that will turn you to a desirable Developer with Amazing Cloud skills... I will realy appricite if you:
 
 1. Subscribe to our [youtube](http://www.youtube.com/@ScaleUpSaaS?sub_confirmation=1)
 2. Buy me A [coffee ❤️](https://www.buymeacoffee.com/scaleupsaas)

Thanks for your support :)

<a href="https://www.buymeacoffee.com/scaleupsaas"><img src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=&slug=scaleupsaas&button_colour=FFDD00&font_colour=000000&font_family=Cookie&outline_colour=000000&coffee_colour=ffffff" /></a>