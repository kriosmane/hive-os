# HIVEOS API
Package for interacting with Hive OS api

## Installation

[PHP](https://php.net) 7.1+ and [Composer](https://getcomposer.org) are required.

To get the latest version of HiveOs api, simply run the code below in your project.

```
composer require krios-mane/hive-os
```
## Initial Setup

#### Standalone 
The first​ step is to initialize the library. Once you do that, You'll get access to all the available API Methods to make requests to HiveOs.
```php
use KriosMane\HiveOs\HiveOs;

$hiveOs = new HiveOs('ACCESS_TOKEN');

```
#### Laravel
Once Laravel HiveOs is installed, You need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
    ...
    KriosMane\HiveOs\Providers\HiveOsServiceProvider::class,
    ...
]
```

Also, register the Facade like so:

```php
'aliases' => [
    ...
    'HiveOs' => KriosMane\HiveOs\Facades\HiveOs::class,
    ...
]
```
You can publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="KriosMane\HiveOs\Providers\HiveOsServiceProvider"
```

A configuration-file named `hiveos.php` with default settings will be placed in your `config` directory:

You can visit this link to get your HiveOS api

```
https://the.hiveos.farm/login
```

Open your .env file and add the following in this format. Ensure you must have gotten your api key:

```php
HIVEOS_LOGIN=****
HIVEOS_PASSWORD=*****
HIVEOS_ACCESS_TOKEN=***********************************************
```

Add the following line to your controller

```php
use \HiveOs;


return HiveOs::coins();


return HiveOs::miners();

```

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## How can I thank you?

Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter or HackerNews? Spread the word!


Thanks!
Krios Mane

## License

Please see [License File](LICENSE.md) for more information.

