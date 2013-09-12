<?php

use Geocoder\Provider\GeocoderServiceProvider;
use Silex\Application;

class GeocoderServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterCreatesServices()
    {
        $serviceProvider = new GeocoderServiceProvider();
        $application = new Application();

        $application->register($serviceProvider);

        $this->assertInstanceOf('\Geocoder\Geocoder', $application['geocoder']);
        $this->assertInstanceOf('\Geocoder\Provider\FreeGeoIpProvider', $application['geocoder.provider']);
        $this->assertInstanceOf('\Geocoder\HttpAdapter\CurlHttpAdapter', $application['geocoder.adapter']);
        $this->assertSame(array('free_geo_ip' => $application['geocoder.provider']), $application['geocoder']->getProviders(), 'The geocoder is initialized with the provider');
    }
}
