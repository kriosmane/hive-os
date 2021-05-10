# HIVE OS API
PHP Package for interacting with Hive OS a cryptocurrency operating system

More details about [Hive Os](https://hiveos.farm/)

## Installation

[PHP](https://php.net) 7.3+ and [Composer](https://getcomposer.org) are required.

To get the latest version of HiveOs api, simply run the code below in your project.

```
composer require kriosmane/hive-os
```
## Initial Setup

Before proceding visit this [link](https://the.hiveos.farm/login) to get your HiveOS Api access token

#### Standalone 
The first​ step is to initialize the library. Once you do that, You'll get access to all the available API Methods to make requests to HiveOs.
```php
use KriosMane\HiveOs\HiveOs;

$access_token = '**********************';
$hiveOs = new HiveOs($access_token);

```
#### Laravel
First You need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

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
php artisan vendor:publish --provider="KriosMane\HiveOs\Providers\HiveOs\ServiceProvider"
```

A configuration-file named `hiveos.php` with default settings will be placed in your `config` directory

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
```

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## How can I thank you?
As a programmer i need coffee to be productive, don't let my [cup](https://www.buymeacoffee.com/kriosmane) get emtpy

Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter or HackerNews? Spread the word!


Thanks!
Krios Mane

## License

Please see [License File](LICENSE.md) for more information.

