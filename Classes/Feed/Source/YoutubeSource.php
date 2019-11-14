<?php

namespace Pixelant\PxaSocialFeed\Feed\Source;

use Pixelant\PxaSocialFeed\Domain\Model\Configuration;
use Pixelant\PxaSocialFeed\Exception\BadResponseException;
use Pixelant\PxaSocialFeed\Exception\InvalidFeedSourceData;
use Psr\Http\Message\ResponseInterface;

/**
 * Class YoutubeSource
 * @package Pixelant\PxaSocialFeed\Feed\Source
 */
class YoutubeSource extends BaseSource
{
    const API_URL = 'https://www.googleapis.com/youtube/v3/';

    /**
     * Load feed source
     *
     * @return array Feed items
     */
    public function load()
    {
        $fields = $this->getFields($this->getConfiguration());

        $endPointUrl = $this->addFieldsAsGetParametersToUrl(
            $this->generateEndPointUrl('search'),
            $fields
        );

        $response = $this->requestYoutubeApi($endPointUrl);

        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        if (!is_array($data) || !isset($data['items'])) {
            // @codingStandardsIgnoreStart
            throw new InvalidFeedSourceData("Youtube response doesn't appear to be a valid json. Items are missing. Response return '$body'.", 1562910457024);
            // @codingStandardsIgnoreEnd
        }

        return $data['items'];
    }

    /**
     * Request youtube api
     *
     * @param string $url
     * @return \HTTP_Request2_Response
     * @throws BadResponseException
     */
    protected function requestYoutubeApi($url)
    {
        return $this->performApiGetRequest($url);
    }

    /**
     * Youtube api endpoint url
     *
     * @param string $endPoint
     * @return string
     */
    protected function generateEndPointUrl($endPoint)
    {
        return $this->getUrl() . $endPoint;
    }

    /**
     * Get api url
     *
     * @return string
     */
    protected function getUrl()
    {
        return self::API_URL;
    }

    /**
     * Get youtube fields for request
     *
     * @param Configuration $configuration
     * @return array
     */
    protected function getFields(Configuration $configuration)
    {
        $fields = [
            'order' => 'date',
            'part' => 'snippet',
            'type' => 'video',
            'maxResults' => $configuration->getMaxItems(),
            'channelId' => $configuration->getSocialId(),
            'key' => $configuration->getToken()->getApiKey()
        ];

        list($fields) = $this->emitSignal('youtubeEndPointRequestFields', [$fields]);

        return $fields;
    }
}
