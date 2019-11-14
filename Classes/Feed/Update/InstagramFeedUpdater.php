<?php

namespace Pixelant\PxaSocialFeed\Feed\Update;

use Pixelant\PxaSocialFeed\Domain\Model\Configuration;
use Pixelant\PxaSocialFeed\Domain\Model\Feed;
use Pixelant\PxaSocialFeed\Domain\Model\Token;
use Pixelant\PxaSocialFeed\Feed\Source\FeedSourceInterface;

/**
 * Class InstagramFeedUpdater
 * @package Pixelant\PxaSocialFeed\Feed\Update
 */
class InstagramFeedUpdater extends BaseUpdater
{
    /**
     * Create/Update feed items
     *
     * @param FeedSourceInterface $source
     */
    public function update(FeedSourceInterface $source)
    {
        $items = $source->load();

        // @TODO: is there a update date ? to update feed item if it was changed ?
        foreach ($items as $rawData) {
            $feedItem = $this->feedRepository->findOneByExternalIdentifier(
                $rawData['id'],
                $source->getConfiguration()->getStorage()
            );

            // Create new instagram feed
            if ($feedItem === null) {
                $feedItem = $this->createFeedItem($source->getConfiguration());
            }

            // Add/update instagram feed data gotten from facebook
            $this->populateGraphInstagramFeed($feedItem, $rawData);

            // Call hook
            $this->emitSignal('beforeUpdateInstagramFeed', [$feedItem, $rawData, $source->getConfiguration()]);

            // Add/update
            $this->addOrUpdateFeedItem($feedItem);
        }
    }

    /**
     * Update model with instagram data
     *
     * @param Feed $feedItem
     * @param array $data
     * @return void
     */
    public function populateGraphInstagramFeed(Feed $feedItem, array $data)
    {
        $isVideo = strtolower($data['media_type']) === 'video';

        $media = $isVideo
            ? ($data['thumbnail_url'] ?: $data['media_url'] ?: '') // Thumbnail or Media url for video
            : ($data['media_url'] ?: ''); // Media or empty string

        $feedItem->setImage($media);

        // Set media type
        $feedItem->setMediaType(
            $isVideo ? Feed::VIDEO : Feed::IMAGE
        );

        // Set message
        $feedItem->setMessage($this->encodeMessage($data['caption'] ?: ''));

        // Set url
        $feedItem->setPostUrl($data['permalink']);

        // Set time
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(strtotime($data['timestamp']));

        $feedItem->setPostDate($dateTime);

        // Set external identifier
        $feedItem->setExternalIdentifier($data['id']);

        // Set likes
        $feedItem->setLikes((int)$data['like_count']);
    }

    /**
     * Create feed item
     *
     * @param Configuration $configuration
     * @return Feed
     */
    protected function createFeedItem(Configuration $configuration)
    {
        /** @var Feed $feedItem */
        $feedItem = $this->objectManager->get(Feed::class);

        // Set configuration
        $feedItem->setConfiguration($configuration);
        $feedItem->setPid($configuration->getStorage());
        $feedItem->setType(Token::INSTAGRAM);

        return $feedItem;
    }
}
