<?php

namespace Geocoder\Provider;

use Geocoder\Collector\GeocoderDataCollector;
use Silex\Application;
use Silex\ServiceProviderInterface;

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
    public function register(Application $app)
    {
        $this->injectServices($app);

        if (isset($app['profiler'])) {
            $this->injectDataCollector($app);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }

    /**
     * Injects Geocoder related services in the application.
     *
     * @param Application $app
     */
    protected function injectServices(Application $app)
    {
        if (isset($app['profiler'])) {
            $app['geocoder.logger'] = $app->share(function($app) {
                return new \Geocoder\Logger\GeocoderLogger();
            });

            $app['geocoder'] = $app->share(function($app) {
                $geocoder = new \Geocoder\LoggableGeocoder();
                $geocoder->setLogger($app['geocoder.logger']);
                $geocoder->registerProvider($app['geocoder.provider']);

                return $geocoder;
            });
        } else {
            $app['geocoder'] = $app->share(function($app) {
                $geocoder = new \Geocoder\Geocoder();
                $geocoder->registerProvider($app['geocoder.provider']);

                return $geocoder;
            });
        }

        $app['geocoder.provider'] = $app->share(function($app) {
            return new \Geocoder\Provider\FreeGeoIpProvider($app['geocoder.adapter']);
        });

        $app['geocoder.adapter'] = $app->share(function($app) {
            return new \Geocoder\HttpAdapter\CurlHttpAdapter();
        });
    }

    /**
     * Injects Geocoder's data collector in the profiler
     *
     * @param Application $app
     */
    protected function injectDataCollector(Application $app)
    {
        $app['data_collector.templates'] = array_merge($app['data_collector.templates'], array(
            array('geocoder', '@Geocoder/Collector/geocoder.html.twig'),
        ));

        $app['data_collectors'] = array_merge($app['data_collectors'], array(
            'geocoder' => $app->share(function ($app) { return new GeocoderDataCollector($app['geocoder.logger']); }),
        ));

        $app['twig.loader.filesystem'] = $app->share($app->extend('twig.loader.filesystem', function ($loader, $app) {
            $loader->addPath($app['geocoder.templates_path'], 'Geocoder');

            return $loader;
        }));

        $app['geocoder.templates_path'] = function () {
            $r = new \ReflectionClass('Geocoder\Provider\GeocoderServiceProvider');

            return dirname(dirname($r->getFileName())).'/../../views';
        };
    }
}
