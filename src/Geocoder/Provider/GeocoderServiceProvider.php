<?php

namespace Geocoder\Provider;

use Geocoder\Collector\GeocoderDataCollector;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * A Geocoder service provider for Silex.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class GeocoderServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $this->injectServices($app);

        if (isset($app['profiler'])) {
            $this->injectDataCollector($app);
        }
    }

    /**
     * Injects Geocoder related services in the application.
     */
    protected function injectServices(Container $app)
    {
        if (isset($app['profiler'])) {
            $app['geocoder.logger'] = function($app) {
                return new \Geocoder\Logger\GeocoderLogger();
            };

            $app['geocoder'] = function($app) {
                $geocoder = new \Geocoder\LoggableGeocoder();
                $geocoder->setLogger($app['geocoder.logger']);
                $geocoder->registerProvider($app['geocoder.provider']);

                return $geocoder;
            };
        } else {
            $app['geocoder'] = function($app) {
                $geocoder = new \Geocoder\Geocoder();
                $geocoder->registerProvider($app['geocoder.provider']);

                return $geocoder;
            };
        }

        $app['geocoder.provider'] = function($app) {
            return new \Geocoder\Provider\FreeGeoIpProvider($app['geocoder.adapter']);
        };

        $app['geocoder.adapter'] = function($app) {
            return new \Geocoder\HttpAdapter\CurlHttpAdapter();
        };
    }

    /**
     * Injects Geocoder's data collector in the profiler
     */
    protected function injectDataCollector(Container $app)
    {
        $app['data_collector.templates'] = $app->extend('data_collector.templates', function ($templates) {
            $templates[] = ['geocoder', '@Geocoder/Collector/geocoder.html.twig'];

            return $templates;
        });

        $app['data_collectors'] = $app->extend('data_collectors', function ($dataCollectors) {
            $dataCollectors['geocoder'] = function ($app) { return new GeocoderDataCollector($app['geocoder.logger']); };

            return $dataCollectors;
        });

        $app['twig.loader.filesystem'] = $app->extend('twig.loader.filesystem', function ($loader, $app) {
            $loader->addPath($app['geocoder.templates_path'], 'Geocoder');

            return $loader;
        });

        $app['geocoder.templates_path'] = function () {
            $r = new \ReflectionClass('Geocoder\Provider\GeocoderServiceProvider');

            return dirname(dirname($r->getFileName())).'/../../views';
        };
    }
}
