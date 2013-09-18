<?php

use Geocoder\Provider\GeocoderServiceProvider;
use Silex\Application;

class GeocoderServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterCreatesServices()
    {
        $serviceProvider = new GeocoderServiceProvider();
        $app = new Application();

        $app->register($serviceProvider);

        $this->assertInstanceOf('\Geocoder\Geocoder', $app['geocoder']);
        $this->assertInstanceOf('\Geocoder\Provider\FreeGeoIpProvider', $app['geocoder.provider']);
        $this->assertInstanceOf('\Geocoder\HttpAdapter\CurlHttpAdapter', $app['geocoder.adapter']);
        $this->assertSame(array('free_geo_ip' => $app['geocoder.provider']), $app['geocoder']->getProviders(), 'The geocoder is initialized with the provider');

        // if the profiler is not defined, the following services are absent
        foreach (array('data_collector.templates', 'data_collectors', 'geocoder.templates_path') as $service) {
            $this->assertFalse(isset($app[$service]));
        }
    }

    public function testProfilerIsUpdatedIfFound()
    {
        $serviceProvider = new GeocoderServiceProvider();
        $app = new Application();

        $app['profiler'] = 'foo';
        $app['data_collector.templates'] = array();
        $app['data_collectors'] = array();

        $loaderMock = $this->getMock('\Twig_Loader_Filesystem');
        $loaderMock->expects($this->once())
            ->method('addPath')
            ->with(
                $this->callback(function($path) use ($app) {
                    return $path === $app['geocoder.templates_path'];
                }),
                $this->equalTo('Geocoder')
            );
        $app['twig.loader.filesystem'] = $app->share(function() use ($loaderMock) {
            return $loaderMock;
        });

        $app->register($serviceProvider);

        $this->assertInstanceOf('\Geocoder\Geocoder', $app['geocoder']);
        $this->assertInstanceOf('\Geocoder\LoggableGeocoder', $app['geocoder']);
        $this->assertInstanceOf('\Geocoder\Provider\FreeGeoIpProvider', $app['geocoder.provider']);
        $this->assertInstanceOf('\Geocoder\HttpAdapter\CurlHttpAdapter', $app['geocoder.adapter']);
        $this->assertSame(array('free_geo_ip' => $app['geocoder.provider']), $app['geocoder']->getProviders(), 'The geocoder is initialized with the provider');
        $this->assertArrayHasKey('geocoder', $app['data_collectors']);

        // if the profiler is defined, the following services are present
        foreach (array('data_collector.templates', 'data_collectors', 'geocoder.templates_path') as $service) {
            $this->assertTrue(isset($app[$service]));
        }

        // trigger the service's creation in order to run the mock assertions
        $app['twig.loader.filesystem'];
    }
}
