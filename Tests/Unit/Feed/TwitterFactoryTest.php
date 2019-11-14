<?php

namespace Pixelant\PxaSocialFeed\Tests\Unit\Feed;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaSocialFeed\Domain\Model\Configuration;
use Pixelant\PxaSocialFeed\Feed\Source\TwitterSource;
use Pixelant\PxaSocialFeed\Feed\TwitterFactory;
use Pixelant\PxaSocialFeed\Feed\Update\TwitterFeedUpdater;
use Pixelant\PxaSocialFeed\Tests\Unit\CreateMock;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class FacebookFeedFactoryTest
 * @package Pixelant\PxaSocialFeed\Tests\Unit\Feed
 */
class TwitterFactoryTest extends UnitTestCase
{
    use CreateMock;

    /**
     * @var TwitterFactory
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new TwitterFactory();

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
    public function getFeedSourceReturnTwitterSource()
    {
        $configuration = new Configuration();

        $this->assertTrue($this->subject->getFeedSource($configuration) instanceof TwitterSource);
    }

    /**
     * @test
     */
    public function getFeedUpdaterReturnTwitterUpdater()
    {
        $this->assertTrue($this->subject->getFeedUpdater() instanceof TwitterFeedUpdater);
    }
}
