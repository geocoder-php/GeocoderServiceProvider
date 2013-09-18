<?php

namespace Geocoder\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * GeocoderLogger
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class GeocoderLogger
{
    protected $logger;
    protected $requests = array();

    /**
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param string $value         value to geocode
     * @param float  $duration
     * @param string $providerClass Geocoder provider class
     * @param mixed  $result
     */
    public function logRequest($value, $duration, $providerClass, $result)
    {
        $this->requests[] = array(
            'value' => $value,
            'duration' => $duration,
            'providerClass' => $providerClass,
            'result' => $result
        );

        if (null !== $this->logger) {
            $message = sprintf("%s %0.2f ms (%s)", $value, $duration, $providerClass);
            $this->logger->info($message);
        }
    }

    /**
     * Returns an array of the logged requests.
     *
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }
}
