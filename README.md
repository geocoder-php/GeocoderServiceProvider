GeocoderServiceProvider [![Build Status](https://travis-ci.org/geocoder-php/GeocoderServiceProvider.png)](https://travis-ci.org/geocoder-php/GeocoderServiceProvider)
=======================

A [Geocoder](http://geocoder-php.org/Geocoder/) service provider for [Silex](http://silex.sensiolabs.org/).


## Installation

Install the GeocoderServiceProvider adding `geocoder-php/geocoder-service-provider` to your composer.json or from CLI:

```
$ php composer.phar require 'geocoder-php/geocoder-service-provider:@stable'
```

**Protip:** you should browse the
[`geocoder-php/geocoder-service-provider`](https://packagist.org/packages/geocoder-php/geocoder-service-provider)
page to choose a stable version to use, avoid the `@stable` meta constraint.


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

See [the Geocoder documentation](http://geocoder-php.org/Geocoder/) for a list
of available adapters and providers.


## Licence

This provider is released under the MIT license.
