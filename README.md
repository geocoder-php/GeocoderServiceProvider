GeocoderServiceProvider
====================

A [Geocoder](http://geocoder-php.org/Geocoder/) service provider for [Silex](http://silex.sensiolabs.org/).


## Installation

Install the GeocoderServiceProvider adding `geocoder-php/geocoder-service-provider` to your composer.json or from CLI:

```
$ php composer.phar require 'geocoder-php/geocoder-service-provider:~1.0'
```


## Usage

Initialize it using `register`.
```php
<?php

use Geocoder\Provider\GeocoderServiceProvider;

$app->register(new GeocoderServiceProvider());
```

From PHP:
```php
<?php

$app->get('/hello', function() use ($app) {
    $geocoder = $app['geocoder'];

    // do your stuff
});
```


## Configuration

The service provider creates the following services:

  * `geocoder`: the Geocoder instance ;
  * `geocoder.provider`: the provider used by Geocoder ;
  * `geocoder.adapter`: the HTTP adapter used to get data from remotes APIs.

By default, the `geocoder.provider` service uses FreeGeoIP and the
`geocoder.adapter` service uses the cURL adapter. Override these services to use
the adapter/provider you want.


## Licence

This provider is released under the MIT license.
