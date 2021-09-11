<?php
 //use kartik\mpdf\Pdf;
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'RCK - ESS',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => env('SMTP_HOST'),
                'username' => env('SMTP_USERNAME'),
                'password' => env('SMPTP_PWD'),
                'port' => env('SMTP_PORT'),
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => true,
                        'verify_peer_name' => true,
                    ],
                ],
            ],
            
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:server=DB-Server-01;database=RCK',
            'username' => 'sa', //'ess',
            'password' => 'Dyn@mics@2021', //'ess123',
            'charset' => 'utf8',
        ],
        'nav' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:server=DB-Server-01;database=RCK',
            'username' => 'sa',
            'password' => 'Dyn@mics@2021',
            'charset' => 'utf8',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => ['/plugins/jquery/jquery.js'],
                ]
            ],
            'appendTimestamp' => true,
        ],
        'navision' => [
            'class' => 'common\Library\Navision',
        ],
        'navhelper' => [
            'class' => 'common\Library\Navhelper',
        ],
        'fms' => [
            'class' => 'common\Library\FMShelper',
        ],
        'recruitment' => [
            'class' => 'common\Library\Recruitment'
        ],
        'dashboard' => [
            'class' => 'common\Library\Dashboard'
        ],
        
    ],

];
