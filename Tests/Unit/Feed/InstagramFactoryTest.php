<?php

namespace Pixelant\PxaSocialFeed\Tests\Unit\Feed;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaSocialFeed\Domain\Model\Configuration;
use Pixelant\PxaSocialFeed\Feed\InstagramFactory;
use Pixelant\PxaSocialFeed\Feed\Source\InstagramSource;
use Pixelant\PxaSocialFeed\Feed\Update\InstagramFeedUpdater;
use Pixelant\PxaSocialFeed\Tests\Unit\CreateMock;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class FacebookFeedFactoryTest
 * @package Pixelant\PxaSocialFeed\Tests\Unit\Feed
 */
class InstagramFactoryTest extends UnitTestCase
{
    use CreateMock;

    /**
     * @var InstagramFactory
     */
    protected $subject= null;

    protected function setUp()
    {
        $this->subject = new InstagramFactory();

        $reflection = new \ReflectionProperty(GeneralUtility::class, 'singletonInstances');
        $reflection->setAccessible(true);
        $singletonInstances = $reflection->getValue();
        $singletonInstances[ObjectManager::class] = $this->createMockTrait(ObjectManager::class);
        $reflection->setValue(null, $singletonInstances);
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getFeedSourceReturnInstagramSource()
    {
        $configuration = new Configuration();

        $this->assertTrue($this->subject->getFeedSource($configuration) instanceof InstagramSource);
    }

    /**
     * @test
     */
    public function getFeedUpdaterReturnInstagramUpdater()
    {
        $this->assertTrue($this->subject->getFeedUpdater() instanceof InstagramFeedUpdater);
    }
}
