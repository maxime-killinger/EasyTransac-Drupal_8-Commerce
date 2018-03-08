<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf06df9f8a91d20ecba44ac8b079688cb
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EasyTransac\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EasyTransac\\' => 
        array (
            0 => __DIR__ . '/..' . '/easytransac/easytransac-sdk-php/sdk/EasyTransac',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf06df9f8a91d20ecba44ac8b079688cb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf06df9f8a91d20ecba44ac8b079688cb::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
