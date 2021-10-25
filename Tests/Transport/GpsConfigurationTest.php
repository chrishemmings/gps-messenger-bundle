<?php

declare(strict_types=1);

namespace PetitPress\GpsMessengerBundle\Tests\Transport;

use PetitPress\GpsMessengerBundle\Transport\GpsConfiguration;
use PetitPress\GpsMessengerBundle\Transport\GpsConfigurationResolver;
use PetitPress\GpsMessengerBundle\Transport\GpsSender;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * @author Mickael Prévôt <mickael.prevot@ext.adeo.com>
 */
class GpsConfigurationTest extends TestCase
{
    public function testRequiredTopicName(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The required option "topic_name" is missing.');

        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $gpsConfigurationResolver->resolve('gps://', [
            'subscription_name' => 'random',
            'key_file_path' => 'random',
            'project_id' => 'random',
        ]);
    }

    public function testRequiredSubscriptionName(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The required option "subscription_name" is missing.');

        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $gpsConfigurationResolver->resolve('gps://', [
            'topic_name' => 'random',
            'key_file_path' => 'random',
            'project_id' => 'random',
        ]);
    }

    public function testRequiredKeyFilePath(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The required option "key_file_path" is missing.');

        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $gpsConfigurationResolver->resolve('gps://', [
            'topic_name' => 'random',
            'subscription_name' => 'random',
            'project_id' => 'random',
        ]);
    }

    public function testRequiredProjectId(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The required option "project_id" is missing.');

        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $gpsConfigurationResolver->resolve('gps://', [
            'topic_name' => 'random',
            'subscription_name' => 'random',
            'key_file_path' => 'random',
        ]);
    }

    public function testReturnsGpsConfiguration(): void
    {
        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $configuration = $gpsConfigurationResolver->resolve('gps://', [
            'topic_name' => 'random',
            'subscription_name' => 'random',
            'key_file_path' => 'random',
            'project_id' => 'random',
        ]);

        $this->assertInstanceOf(GpsConfiguration::class, $configuration);
    }

    public function testConfigurationGetters(): void
    {
        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $configuration = $gpsConfigurationResolver->resolve('gps://', [
            'topic_name' => 'random_topic_name',
            'subscription_name' => 'random_subscription_name',
            'key_file_path' => 'random_key_file_path',
            'project_id' => 'random_project_id',
            'max_messages_pull' => 20,
            'api_endpoint' => 'localhost:8085',
        ]);

        $this->assertEquals('random_topic_name', $configuration->getTopicName());
        $this->assertEquals('random_subscription_name', $configuration->getSubscriptionName());
        $this->assertEquals('random_key_file_path', $configuration->getKeyFilePath());
        $this->assertEquals('random_project_id', $configuration->getProjectId());
        $this->assertEquals(20, $configuration->getMaxMessagesPull());
        $this->assertEquals('localhost:8085', $configuration->getApiEndpoint());
    }

    public function testClientConfiguration(): void
    {
        $gpsConfigurationResolver = new GpsConfigurationResolver();
        $configuration = $gpsConfigurationResolver->resolve('gps://', [
            'topic_name' => 'random_topic_name',
            'subscription_name' => 'random_subscription_name',
            'key_file_path' => 'random_key_file_path',
            'project_id' => 'random_project_id',
            'api_endpoint' => 'localhost:8085',
        ]);

        $clientConfiguration = $configuration->getClientConfiguration();

        $this->assertIsArray($clientConfiguration);
        $this->assertEqualsCanonicalizing([
            'keyFilePath' => 'random_key_file_path',
            'projectId' => 'random_project_id',
            'apiEndpoint' => 'localhost:8085',
        ], $clientConfiguration);
    }
}
