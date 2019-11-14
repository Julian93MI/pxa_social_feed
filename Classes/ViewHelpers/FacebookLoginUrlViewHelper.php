<?php

namespace Pixelant\PxaSocialFeed\ViewHelpers;

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

use Pixelant\PxaSocialFeed\Controller\EidController;
use Pixelant\PxaSocialFeed\Domain\Model\Token;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class TokenGenerationUrlViewHelper
 * @package Pixelant\PxaSocialFeed\ViewHelpers
 */
class FacebookLoginUrlViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * Default request permissions
     *
     * @var string
     */
    protected $defaultPermissions = 'public_profile, user_posts';

    /**
     * Initialize
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('token', Token::class, 'Token', true);
        $this->registerArgument('loginUrlAs', 'string', 'Render as', true);
        $this->registerArgument('redirectUrlAs', 'string', 'Redirect uri', true);
        $this->registerArgument('permissions', 'string', 'List of permissions', false, $this->defaultPermissions);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        /** @var Token $token */
        $token = $arguments['token'];
        $loginUrlAs = $arguments['loginUrlAs'];
        $redirectUrlAs = $arguments['redirectUrlAs'];
        $permissions = GeneralUtility::trimExplode(',', $arguments['permissions']);

        $redirectUrl = static::buildRedirectUrl($token->getUid());
        try {
            $url = $token->getFacebookLoginUrl($redirectUrl, $permissions);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $variableProvider = $renderingContext->getTemplateVariableContainer();

        static::removeVariables($variableProvider, [$loginUrlAs, $redirectUrlAs]);

        $variableProvider->add($redirectUrlAs, $redirectUrl);
        $variableProvider->add($loginUrlAs, $url);
        $content = $renderChildrenClosure();

        static::removeVariables($variableProvider, [$loginUrlAs, $redirectUrlAs]);

        return $content;
    }

    /**
     * Clean template variables
     *
     * @param TemplateVariableContainer $variableProvider
     * @param array $vars
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected static function removeVariables(TemplateVariableContainer $variableProvider, array $vars)
    {
        foreach ($vars as $var) {
            if ($variableProvider->exists($var)) {
                $variableProvider->remove($var);
            }
        }
    }

    /**
     * Redirect url
     *
     * @param int $tokenUid
     * @return string
     */
    protected static function buildRedirectUrl($tokenUid)
    {
        return sprintf(
            '%s://%s%s?eID=%s&token=%d',
            GeneralUtility::getIndpEnv('TYPO3_SSL') ? 'https' : 'http', // Http is not supported by facebook anyway
            GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'),
            GeneralUtility::getIndpEnv('TYPO3_PORT') ? (':' . GeneralUtility::getIndpEnv('TYPO3_PORT')) : '',
            EidController::IDENTIFIER,
            $tokenUid
        );
    }
}
