<?php

namespace Pixelant\PxaSocialFeed\Feed;

use Pixelant\PxaSocialFeed\Domain\Model\Configuration;
use Pixelant\PxaSocialFeed\Feed\Source\FeedSourceInterface;
use Pixelant\PxaSocialFeed\Feed\Source\YoutubeSource;
use Pixelant\PxaSocialFeed\Feed\Update\FeedUpdaterInterface;
use Pixelant\PxaSocialFeed\Feed\Update\YoutubeFeedUpdater;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class YoutubeFactory
 * @package Pixelant\PxaSocialFeed\Feed
 */
class YoutubeFactory implements FeedFactoryInterface
{

    /**
     * Feed source allow to fetch feed items
     *
     * @param Configuration $configuration
     * @return FeedSourceInterface
     */
    public function getFeedSource(Configuration $configuration)
    {
        return GeneralUtility::makeInstance(YoutubeSource::class, $configuration);
    }

    /**
     * Feed updater. Will create/update feed items
     *
     * @return FeedUpdaterInterface
     */
    public function getFeedUpdater()
    {
        return GeneralUtility::makeInstance(YoutubeFeedUpdater::class);
    }
}
