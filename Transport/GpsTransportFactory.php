<?php

declare(strict_types=1);

namespace PetitPress\GpsMessengerBundle\Transport;

use Google\Cloud\PubSub\PubSubClient;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * @author Ronald Marfoldi <ronald.marfoldi@petitpress.sk>
 */
final class GpsTransportFactory implements TransportFactoryInterface
{
    private GpsConfigurationResolverInterface $gpsConfigurationResolver;

    public function __construct(GpsConfigurationResolverInterface $gpsConfigurationResolver)
    {
        $this->gpsConfigurationResolver = $gpsConfigurationResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        $gpsConfiguration = $this->gpsConfigurationResolver->resolve($dsn, $options);

        $GpsClient = new PubSubClient($gpsConfiguration->getClientConfiguration());

        return new GpsTransport(
            $GpsClient,
            $gpsConfiguration,
            $serializer
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'gps://');
    }
}
