---
layout: project
title: GeocoderServiceProvider
project_name: GeocoderServiceProvider
---

GeocoderServiceProvider
=======================

[Geocoder](http://geocoder-php.org/Geocoder/) service provider for
[Silex](http://silex.sensiolabs.org/).

[![Build
Status](https://travis-ci.org/geocoder-php/GeocoderServiceProvider.png)](https://travis-ci.org/geocoder-php/GeocoderServiceProvider)


## Usage

Initialize the service provider using `register()` method:

```php
<?php

use Geocoder\Provider\GeocoderServiceProvider;

$app->register(new GeocoderServiceProvider());
```

**N.B.:** be careful to register this provider __after__ the
`WebProfilerServiceProvider` if you want Geocoder to be integrated in it.

Then use it in your controllers:

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


## Installation

The recommended way to install GeocoderServiceProvider is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "geocoder-php/geocoder-service-provider": "@stable"
    }
}
```

**Protip:** you should browse the
[`geocoder-php/geocoder-service-provider`](https://packagist.org/packages/geocoder-php/geocoder-service-provider)
page to choose a stable version to use, avoid the `@stable` meta constraint.


## Licence

GeocoderServiceProvider is released under the MIT License. See the bundled
LICENSE file for details.
