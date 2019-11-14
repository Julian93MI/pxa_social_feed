<?php

namespace Pixelant\PxaSocialFeed\Feed\Source;

use Pixelant\PxaSocialFeed\Exception\BadResponseException;
use Pixelant\PxaSocialFeed\Exception\InvalidFeedSourceData;

/**
 * Class TwitterSource
 * @package Pixelant\PxaSocialFeed\Feed\Source
 */
class TwitterSource extends BaseSource
{
    /**
     * Twitter api
     */
    const API_URL = 'https://api.twitter.com/1.1/';

    /**
     * Load feed source
     *
     * @return array Feed items
     */
    public function load()
    {
        $endPointUrl = $this->generateEndPointUrl('statuses/user_timeline.json');
        $fields = $this->getFields();

        $authHeader = $this->getAuthHeader($endPointUrl, $fields);

        $response = $this->requestTwitterApi(
            $this->addFieldsAsGetParametersToUrl($endPointUrl, $fields),
            $authHeader
        );

        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        if (!is_array($data)) {
            // @codingStandardsIgnoreStart
            throw new InvalidFeedSourceData("Twitter response doesn't appear to be a valid json. Response return '$body'.", 1562910457024);
            // @codingStandardsIgnoreEnd
        }

        return $data;
    }

    /**
     * Request twitter api
     *
     * @param string $url
     * @param string $autHeader
     * @return \HTTP_Request2_Response
     * @throws BadResponseException
     */
    protected function requestTwitterApi($url, $autHeader)
    {
        $additionalOptions = [
            'headers' => [
                'Authorization' => $autHeader
            ]
        ];

        return $this->performApiGetRequest($url, $additionalOptions);
    }

    /**
     * Generate url for request
     *
     * @param string $endPoint
     * @return string
     */
    protected function generateEndPointUrl($endPoint)
    {
        return $this->getApiUrl() . $endPoint;
    }

    /**
     * Get API url
     *
     * @return string
     */
    protected function getApiUrl()
    {
        return self::API_URL;
    }

    /**
     * Query fields
     *
     * @return array
     */
    protected function getFields()
    {
        $configuration = $this->getConfiguration();

        // Important to pass field value as string, because it's encoded with rawurlencode
        $fields = [
            'screen_name' => $configuration->getSocialId(),
            'count' => (string)$configuration->getMaxItems(),
            'tweet_mode' => 'extended',
            'exclude_replies' => '1',
            'include_rts' => '1'
        ];

        list($fields) = $this->emitSignal('beforeReturnTwitterQueryFields', [$fields]);

        return $fields;
    }

    /**
     * Get Authorization header
     *
     * @param string $url
     * @param array $fields
     * @return string
     */
    protected function getAuthHeader($url, $fields)
    {
        $token = $this->getConfiguration()->getToken();
        $oauth = [
            'oauth_consumer_key' => $token->getApiKey(),
            'oauth_nonce' => md5((string)mt_rand()),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $token->getAccessToken(),
            'oauth_timestamp' => (string)time(),
            'oauth_version' => '1.0'
        ];

        $sigBase = $this->buildSigBase(array_merge($oauth, $fields), $url);
        $sigKey = rawurlencode($token->getApiSecretKey()) . '&' . rawurlencode($token->getAccessTokenSecret());

        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $sigBase, $sigKey, true));

        $header = 'OAuth ';
        $headerValues = [];

        foreach ($oauth as $key => $value) {
            $headerValues[] = $key . '="' . rawurlencode($value) . '"';
        }

        $header .= implode(', ', $headerValues);

        return $header;
    }

    /**
     * Generate the base string
     *
     * @param array $oauth
     * @param string $url
     * @return string Built base string
     */
    protected function buildSigBase($oauth, $url)
    {
        ksort($oauth);
        $urlParts = [];

        foreach ($oauth as $key => $value) {
            $urlParts[] = $key . '=' . rawurlencode($value);
        }

        return 'GET' . '&' . rawurlencode($url) . '&' . rawurlencode(implode('&', $urlParts));
    }
}
