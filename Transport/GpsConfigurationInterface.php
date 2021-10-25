<?php

declare(strict_types=1);

namespace PetitPress\GpsMessengerBundle\Transport;

/**
 * @author Ronald Marfoldi <ronald.marfoldi@petitpress.sk>
 */
interface GpsConfigurationInterface
{
    public function getTopicName() : ?string;

    public function getSubscriptionName(): ?string;

    public function getKeyFilePath(): ?string;

    public function getProjectId(): ?string;

    public function getMaxMessagesPull(): int;

    public function getApiEndpoint(): ?string;

    public function getClientConfiguration(): array;
}
