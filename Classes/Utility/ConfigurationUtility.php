<?php
declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Read plugin configuration
 *
 * @package Pixelant\PxaSocialFeed\Utility
 */
class ConfigurationUtility
{
    /**
     * Get extension configuration
     *
     * @return array
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public static function getExtensionConfiguration(): array
    {
        $configuration = [];
        if (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 9000000) {
            $configVariables = (array)$GLOBALS['TYPO3_CONF_VARS'];
            $possibleConfig = unserialize((string)$configVariables['EXT']['extConf']['pxa_social_feed']);
            if (!empty($possibleConfig) && is_array($possibleConfig)) {
                $configuration = $possibleConfig;
            }
        } else {
            $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('pxa_social_feed');
        }
        return $configuration;
    }

    /**
     * Check if feature is enabled in configuration
     *
     * @param string $feature
     * @return bool
     */
    public static function isFeatureEnabled(string $feature): bool
    {
        return boolval(static::getExtensionConfiguration()[$feature] ?? false);
    }
}
