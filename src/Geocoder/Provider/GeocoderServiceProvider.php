<?php

namespace Geocoder\Provider;

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
        $app['geocoder'] = $app->share(function($app) {
            $geocoder = new \Geocoder\Geocoder();
            $geocoder->registerProvider($app['geocoder.provider']);

            return $geocoder;
        });

        $app['geocoder.provider'] = $app->share(function($app) {
            return new \Geocoder\Provider\FreeGeoIpProvider($app['geocoder.adapter']);
        });

        $app['geocoder.adapter'] = $app->share(function($app) {
            return new \Geocoder\HttpAdapter\CurlHttpAdapter();
        });
    }
}
