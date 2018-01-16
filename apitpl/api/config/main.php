<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',

    'language' => 'zh-CN',       //全局设置为中文--推荐

    'bootstrap' => ['log'],
    'modules' => [
        'v2' => [
            'class' => 'api\modules\v2\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\Adminuser',
            'enableAutoLogin' => true,
            'enableSession'=>false,
          //  'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        // 'session' => [
        //     // this is the name of the session cookie used for login on the backend
        //     'name' => 'advanced-backend',
        // ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
			      'enableStrictParsing'=> true,
            'showScriptName' => false,
            'rules' => [
		             	['class'=>'yii\rest\UrlRule','controller'=>'article'
                ,
                'extraPatterns'=>['POST search'=>'search'],
              ]
           ,
           ['class'=>'yii\rest\UrlRule','controller'=>'top10',
           'pluralize'=>false,
         ],
         ['class'=>'yii\rest\UrlRule','controller'=>'adminuser',
         'pluralize'=>false,
           'extraPatterns'=>['POST login'=>'login',
           'POST signup'=>'signup',
         ],
       ],
            ],

        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
    ],
    'params' => $params,
];
