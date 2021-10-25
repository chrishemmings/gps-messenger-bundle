<?php

declare(strict_types=1);

namespace PetitPress\GpsMessengerBundle\Transport;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Ronald Marfoldi <ronald.marfoldi@petitpress.sk>
 */
final class GpsConfigurationResolver implements GpsConfigurationResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(string $dsn, array $options): GpsConfigurationInterface
    {
        // not relevant options for transport itself
        unset($options['transport_name'], $options['serializer']);

        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setRequired('topic_name')
            ->setAllowedTypes('topic_name', 'string')
            ->setRequired('subscription_name')
            ->setAllowedTypes('subscription_name', 'string')
            ->setRequired('key_file_path')
            ->setAllowedTypes('key_file_path', 'string')
            ->setRequired('project_id')
            ->setAllowedTypes('project_id', 'string')
            ->setDefault('max_messages_pull', self::DEFAULT_MAX_MESSAGES_PULL)
            ->setNormalizer('max_messages_pull', static function (Options $options, $value): ?int {
                return ((int) filter_var($value, FILTER_SANITIZE_NUMBER_INT)) ?: null;
            })
            ->setAllowedTypes('max_messages_pull', ['int', 'string'])
            ->setDefault('api_endpoint', null)
            ->setAllowedTypes('api_endpoint', ['null', 'string']);
        ;

        $resolvedOptions = $optionsResolver->resolve($options);

        return new GpsConfiguration($resolvedOptions);
    }
}
