<?php

namespace Pixelant\PxaSocialFeed\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Feeds
 */
class Feed extends AbstractEntity
{

    /**
     * pid
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * updateDate
     *
     * @var int
     */
    protected $updateDate = null;

    /**
     * externalIdentifier
     *
     * @var string
     */
    protected $externalIdentifier = '';

    /**
     * date
     *
     * @var \DateTime
     */
    protected $postDate = null;

    /**
     * postUrl
     *
     * @var string
     */
    protected $postUrl = '';

    /**
     * message
     *
     * @var string
     */
    protected $message = '';

    /**
     * image
     *
     * @var string
     */
    protected $image = '';

    /**
     * likes
     *
     * @var int
     */
    protected $likes = 0;

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * type
     *
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $additionalImages = '';

    /**
     * token
     *
     * @var \Pixelant\PxaSocialFeed\Domain\Model\Configuration
     */
    protected $configuration = null;

    /**
     * Returns the date
     *
     * @return \DateTime $date
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Sets the date
     *
     * @param \DateTime $postDate
     * @return void
     */
    public function setPostDate(\DateTime $postDate)
    {
        $this->postDate = $postDate;
    }

    /**
     * Returns the postUrl
     *
     * @return string $postUrl
     */
    public function getPostUrl()
    {
        return $this->postUrl;
    }

    /**
     * Sets the postUrl
     *
     * @param string $postUrl
     * @return void
     */
    public function setPostUrl($postUrl)
    {
        $this->postUrl = $postUrl;
    }

    /**
     * Returns the message
     *
     * @return string $message
     */
    public function getMessage()
    {
        return $this->type == Token::FACEBOOK
            ? $this->getDecodedMessage()
            : $this->message;
    }

    /**
     * Returns the message decoded
     *
     * @return string $message
     */
    public function getDecodedMessage()
    {
        return json_decode(
            sprintf(
                '"%s"',
                $this->message
            )
        );
    }

    /**
     * Sets the message
     *
     * @param string $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Returns the image
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param string $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the config
     *
     * @return \Pixelant\PxaSocialFeed\Domain\Model\Configuration $configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Sets the token
     *
     * @param \Pixelant\PxaSocialFeed\Domain\Model\Configuration $configuration
     * @return void
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getExternalIdentifier()
    {
        return $this->externalIdentifier;
    }

    /**
     * @param string $externalIdentifier
     */
    public function setExternalIdentifier($externalIdentifier)
    {
        $this->externalIdentifier = $externalIdentifier;
    }

    /**
     * @return int
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @param int $updateDate
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Additional images as array
     *
     * @return array
     */
    public function getAdditionalImagesArray()
    {
        if (!empty($this->getAdditionalImages())) {
            return GeneralUtility::trimExplode(',', $this->getAdditionalImages(), true);
        }

        return [];
    }

    /**
     * @return string
     */
    public function getAdditionalImages()
    {
        return $this->additionalImages;
    }

    /**
     * @param string $additionalImages
     */
    public function setAdditionalImages($additionalImages)
    {
        $this->additionalImages = $additionalImages;
    }
}
