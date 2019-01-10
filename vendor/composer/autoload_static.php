<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit34a5d0c5e4727b779bb21bfee1f8fa44
{
    public static $files = array (
        'c07dbcc0781c0a6941930f16b3d0da7a' => __DIR__ . '/../..' . '/framework/Helpers/constants.php',
        '156c2cd9e814b1220d8a8630d007d65a' => __DIR__ . '/../..' . '/framework/Helpers/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MicroPay\\' => 9,
            'MPEngine\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MicroPay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'MPEngine\\' => 
        array (
            0 => __DIR__ . '/../..' . '/framework/Engine',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit34a5d0c5e4727b779bb21bfee1f8fa44::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit34a5d0c5e4727b779bb21bfee1f8fa44::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}