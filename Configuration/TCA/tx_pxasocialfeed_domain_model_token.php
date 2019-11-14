<?php
defined('TYPO3_MODE') or die();

return call_user_func(function () {
    $ll = 'LLL:EXT:pxa_social_feed/Resources/Private/Language/locallang_db.xlf:tx_pxasocialfeed_domain_model_tokens';

    return [
        'ctrl' => [
            'title' => $ll,
            'label' => 'uid',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'dividers2tabs' => true,
            'default_sortby' => 'crdate DESC',

            'delete' => 'deleted',
            'enablecolumns' => [
                'disabled' => 'hidden'
            ],
            'searchFields' => 'app_id, app_secret, access_token',

            'typeicon_classes' => [
                'default' => 'ext-pxasocialfeed-model-icon'
            ],

            'type' => 'type',
            'rootLevel' => 1
        ],
        'interface' => [
            'showRecordFieldList' => 'hidden, type, app_id,app_secret, starttime, endtime',
        ],
        'types' => [
            \Pixelant\PxaSocialFeed\Domain\Model\Token::FACEBOOK => ['showitem' => '--palette--;;paletteHidden, type, --palette--;;paletteGraphApi'],
            \Pixelant\PxaSocialFeed\Domain\Model\Token::INSTAGRAM => ['showitem' => '--palette--;;paletteHidden, type, --palette--;;paletteGraphApi'],
            \Pixelant\PxaSocialFeed\Domain\Model\Token::TWITTER => [
                'showitem' => '--palette--;;paletteHidden, type, --palette--;;paletteTwitterApi',
                'columnsOverrides' => [
                    'access_token' => [
                        'label' => $ll . '.access_token',
                        'config' => [
                            'eval' => 'trim,required'
                        ]
                    ]
                ]
            ],
            \Pixelant\PxaSocialFeed\Domain\Model\Token::YOUTUBE => [
                'showitem' => '--palette--;;paletteHidden, type, --palette--;;paletteYoutubeApi',
                'columnsOverrides' => [
                    'api_key' => [
                        'label' => $ll . '.youtube_api_key',
                    ]
                ]
            ],
        ],
        'palettes' => [
            'paletteHidden' => ['showitem' => 'hidden'],
            'paletteGraphApi' => ['showitem' => 'app_id, --linebreak--, app_secret, --linebreak--, access_token'],
            'paletteTwitterApi' => ['showitem' => 'api_key, --linebreak--, api_secret_key, --linebreak--, access_token, --linebreak--, access_token_secret'],
            'paletteYoutubeApi' => ['showitem' => 'api_key'],
        ],
        'columns' => [
            'hidden' => [
                'exclude' => true,
                'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
                'config' => [
                    'type' => 'check',
                    'default' => 0
                ],
            ],
            'type' => [
                'exclude' => true,
                'label' => $ll . '.type',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        [$ll . '.type.type.1', \Pixelant\PxaSocialFeed\Domain\Model\Token::FACEBOOK],
                        [$ll . '.type.type.2', \Pixelant\PxaSocialFeed\Domain\Model\Token::INSTAGRAM],
                        [$ll . '.type.type.3', \Pixelant\PxaSocialFeed\Domain\Model\Token::TWITTER],
                        [$ll . '.type.type.4', \Pixelant\PxaSocialFeed\Domain\Model\Token::YOUTUBE],
                    ]
                ]
            ],
            'app_id' => [
                'exclude' => true,
                'label' => $ll . '.app_id',
                'config' => [
                    'type' => 'input',
                    'eval' => 'required,trim'
                ]
            ],
            'app_secret' => [
                'exclude' => true,
                'label' => $ll . '.app_secret',
                'config' => [
                    'type' => 'input',
                    'eval' => 'required,trim'
                ]
            ],
            'access_token' => [
                'exclude' => true,
                'label' => $ll . '.access_token',
                'config' => [
                    'type' => 'input',
                    'eval' => 'trim'
                ]
            ],
            'api_key' => [
                'exclude' => true,
                'label' => $ll . '.api_key',
                'config' => [
                    'type' => 'input',
                    'eval' => 'trim,required'
                ]
            ],
            'api_secret_key' => [
                'exclude' => true,
                'label' => $ll . '.api_secret_key',
                'config' => [
                    'type' => 'input',
                    'eval' => 'trim,required'
                ]
            ],
            'access_token_secret' => [
                'exclude' => true,
                'label' => $ll . '.access_token_secret',
                'config' => [
                    'type' => 'input',
                    'eval' => 'trim,required'
                ]
            ],
        ]
    ];
});
