<?php

declare(strict_types=1);

namespace PetitPress\GpsMessengerBundle\Transport;

/**
 * @author Ronald Marfoldi <ronald.marfoldi@petitpress.sk>
 */
final class GpsConfiguration implements GpsConfigurationInterface
{
    private string $topicName;
    private string $subscriptionName;
    private string $keyFilePath;
    private string $projectId;
    private int $maxMessagesPull = 10;
    private string $apiEndpoint;

    public function __construct(array $options)
    {
        foreach ($options as $option => $value) {
            $option = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $option))));
            if (property_exists($this, $option) && $value !== null) {
                $this->$option = $value;
            }
        }
    }

    /**
     * Return GPS topic name
     *
     */
    public function getTopicName() : ?string
    {
        return $this->topicName;
    }

    /**
     * Return GPS subscription name
     *
     */
    public function getSubscriptionName() : ?string
    {
        return $this->subscriptionName;
    }

    /**
     * Return GPS JSON key file path
     *
     */
    public function getKeyFilePath() : ?string
    {
        return $this->keyFilePath;
    }

    /**
     * Return GPS project id
     *
     */
    public function getProjectId() : ?string
    {
        return $this->projectId;
    }

    /**
     * Return max messages to pull
     *
     * @return integer
     */
    public function getMaxMessagesPull() : int
    {
        return $this->maxMessagesPull;
    }

    /**
     * Return max messages to pull
     *
     */
    public function getApiEndpoint(): ?string
    {
        return $this->apiEndpoint;
    }

    /**
     * Return GPC Client configuration array
     *
     */
    public function getClientConfiguration() : array
    {
        $clientConfiguration = [
            'projectId' => $this->getProjectId(),
            'keyFilePath' => $this->getKeyFilePath(),
        ];

        if ($this->getApiEndpoint() !== null) {
            $clientConfiguration['apiEndpoint'] = $this->getApiEndpoint();
        }

        return $clientConfiguration;
    }
}
