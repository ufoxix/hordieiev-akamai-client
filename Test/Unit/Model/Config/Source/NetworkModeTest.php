<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Config\Source;

use Hordieiev\AkamaiClient\Model\Config\Source\NetworkMode as TargetClass;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class NetworkModeTest
 */
class NetworkModeTest extends TestCase
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
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Config\Source\NetworkMode::toOptionArray
     */
    public function testToOptionArray(): void
    {
        $expected = [
            ['value' => 'staging', 'label' => __('Staging')],
            ['value' => 'production', 'label' => __('Production')],
        ];
        $actual = $this->subject->toOptionArray();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class
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
