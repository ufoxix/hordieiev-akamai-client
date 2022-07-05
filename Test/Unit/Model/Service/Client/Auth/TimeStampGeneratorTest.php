<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Client\Auth;

use Hordieiev\AkamaiClient\Model\Service\Client\Auth\TimeStampGenerator as TargetClass;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeStampGeneratorTest
 */
class TimeStampGeneratorTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var TargetClass|MockInterface
     */
    private $subject;

    /**
     * @var TimezoneInterface|MockInterface
     */
    private $timezone;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->timezone = Mockery::mock(TimezoneInterface::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\TimeStampGenerator::generateTimeStampByPattern
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\TimeStampGenerator::__construct
     * @throws \Exception
     */
    public function testGenerateTimeStampByPattern(): void
    {
        $expected = new \DateTime('now', new \DateTimeZone('UTC'));
        $date = $this->timezone->shouldReceive('date')
            ->andReturn($expected);

        $date->shouldReceive('format')
            ->with(TargetClass::FORMAT)
            ->andReturn('');

        $actual = $this->subject->generateTimeStampByPattern();
        $this->assertEquals($expected->format(TargetClass::FORMAT), $actual);
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'timezone' => $this->timezone,
            ]
        );
    }

    /**
     * Tears down the fixture
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
