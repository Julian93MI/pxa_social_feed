<?php

namespace Pixelant\PxaSocialFeed\Feed\Source;

use Facebook\FacebookResponse;
use Pixelant\PxaSocialFeed\Exception\InvalidFeedSourceData;

/**
 * Class BaseFacebookSource
 * @package Pixelant\PxaSocialFeed\Feed\Source
 */
abstract class BaseFacebookSource extends BaseSource
{
    /**
     * Generate facebook endpoint
     *
     * @param string $id
     * @param string $endPointEntry
     * @return string
     */
    protected function generateEndPoint($id, $endPointEntry)
    {
        $limit = $this->getConfiguration()->getMaxItems();

        $fields = $this->getEndPointFields();

        list($fields) = $this->emitSignal('facebookEndPointRequestFields', [$fields]);

        $url = $id . '/' . $endPointEntry;
        $queryParams = [
            'fields' => implode(',', $fields),
            'limit' => $limit
        ];

        $endPoint = $this->addFieldsAsGetParametersToUrl($url, $queryParams);

        list($endPoint) = $this->emitSignal('faceBookEndPoint', [$endPoint]);

        return $endPoint;
    }

    /**
     * Get data from facebook
     *
     * @param FacebookResponse $response
     * @return array
     */
    protected function getDataFromResponse(FacebookResponse $response)
    {
        $body = $response->getDecodedBody();

        if (!is_array($body) || !isset($body['data'])) {
            // @codingStandardsIgnoreStart
            throw new InvalidFeedSourceData("Invalid data received for configuration {$this->getConfiguration()->getName()}.", 1562842385128);
            // @codingStandardsIgnoreEnd
        }

        return $body['data'];
    }

    /**
     * Return fields for endpoint request
     *
     * @return array
     */
    abstract protected function getEndPointFields();
}
