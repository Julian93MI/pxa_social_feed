<?php

namespace Pixelant\PxaSocialFeed\Exception;

/**
 * Class FacebookObtainAccessTokenException
 * @package Pixelant\PxaSocialFeed\Exception
 */
class FacebookObtainAccessTokenException extends \Exception
{
    /**
     * @var int
     */
    protected $statusCode = 0;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
