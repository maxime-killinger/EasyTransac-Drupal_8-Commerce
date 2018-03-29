<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfecae8dfbb990b053803b722f5f00e6a
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitfecae8dfbb990b053803b722f5f00e6a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfecae8dfbb990b053803b722f5f00e6a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
