<?php declare(strict_types=1);

namespace Transport;

use Google\Cloud\Core\Exception\GoogleException;
use PetitPress\GpsMessengerBundle\Transport\GpsConfigurationInterface;
use PetitPress\GpsMessengerBundle\Transport\GpsConfigurationResolverInterface;
use PetitPress\GpsMessengerBundle\Transport\GpsTransport;
use PetitPress\GpsMessengerBundle\Transport\GpsTransportFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class GpsTransportFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $serializerProphecy;
    private GpsTransportFactory $gpsTransportFactory;
    private GpsTransportFactory $gpsTransportFactoryNoConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $gpsConfigurtionProphecy = $this->prophesize(GpsConfigurationInterface::class);
        $gpsConfigurtionProphecy->getProjectId()->willReturn('random');
        $gpsConfigurtionProphecy->getKeyFilePath()->willReturn(__DIR__ . '/../Resources/credentials.json');
        $gpsConfigurtionProphecy->getClientConfiguration()->willReturn([
            'projectId' => 'random',
            'keyFilePath' => __DIR__ . '/../Resources/credentials.json',
        ]);

        $gpsConfigurtionEmptyProphecy = $this->prophesize(GpsConfigurationInterface::class);
        $gpsConfigurtionEmptyProphecy->getClientConfiguration()->willReturn([]);

        $gpsCongigurationResolverProphecy = $this->prophesize(GpsConfigurationResolverInterface::class);
        $this->serializerProphecy = $this->prophesize(SerializerInterface::class);

        $gpsCongigurationResolverProphecy->resolve(Argument::any(), Argument::any())->willReturn($gpsConfigurtionProphecy->reveal());

        $gpsCongigurationResolverEmptyProphecy = $this->prophesize(GpsConfigurationResolverInterface::class);
        $gpsCongigurationResolverEmptyProphecy->resolve(Argument::any(), Argument::any())->willReturn($gpsConfigurtionEmptyProphecy->reveal());

        $this->gpsTransportFactory = new GpsTransportFactory($gpsCongigurationResolverProphecy->reveal());
        $this->gpsTransportFactoryNoConfig = new GpsTransportFactory($gpsCongigurationResolverEmptyProphecy->reveal());
    }

    public function testCreateTransportFailsWithoutProjectId()
    {
        $dsn = 'gps://';
        $options = [];

        $this->expectException(GoogleException::class);
        $this->gpsTransportFactoryNoConfig->createTransport($dsn, $options, $this->serializerProphecy->reveal());
    }

    public function testCreateTransportWithProjectIdFromEnvironmentVar()
    {
        $dsn = 'gps://';
        $options = [];

        $transport = $this->gpsTransportFactory->createTransport($dsn, $options, $this->serializerProphecy->reveal());

        static::assertInstanceOf(GpsTransport::class, $transport);
    }

    public function testCreateTransportWithProjectIdFromEnvironmentVarAndConfiguration()
    {
        $dsn = 'gps://';
        $options = ['projectId' => 'specfic'];

        $transport = $this->gpsTransportFactory->createTransport($dsn, $options, $this->serializerProphecy->reveal());

        static::assertInstanceOf(GpsTransport::class, $transport);
    }

    public function testCreateTransportWithEmulator()
    {
        $dsn = 'gps://';
        $options = [
            'projectId' => 'random',
            'emulatorHost' => 'address://emulator/host',
            'keyFilePath' => __DIR__ . '/../Resources/credentials.json',
        ];

        $transport = $this->gpsTransportFactory->createTransport($dsn, $options, $this->serializerProphecy->reveal());

        static::assertInstanceOf(GpsTransport::class, $transport);
    }
}
