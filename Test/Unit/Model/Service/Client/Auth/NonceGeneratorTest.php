<?php

declare(strict_types=1);

namespace Hordieiev\AkamaiClient\Test\Unit\Model\Service\Client\Auth;

use Hordieiev\AkamaiClient\Model\Service\Client\Auth\NonceGenerator as TargetClass;
use Magento\Framework\Oauth\NonceGeneratorInterface as CoreNonceGenerator;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class NonceGeneratorTest
 */
class NonceGeneratorTest extends TestCase
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
     * @var CoreNonceGenerator|MockInterface
     */
    private $coreNonceGenerator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->coreNonceGenerator = Mockery::mock(CoreNonceGenerator::class);
        $this->initTargetClass();
    }

    /**
     * @return void
     *
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\NonceGenerator::generate
     * @covers \Hordieiev\AkamaiClient\Model\Service\Client\Auth\NonceGenerator::__construct
     */
    public function testGenerateTimeStampByPattern(): void
    {
        $expected = '';
        $this->coreNonceGenerator->shouldReceive('generateNonce')
            ->andReturn($expected);

        $actual = $this->subject->generate();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    private function initTargetClass(): void
    {
        $this->subject = $this->objectManager->getObject(
            TargetClass::class,
            [
                'coreNonceGenerator' => $this->coreNonceGenerator,
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
