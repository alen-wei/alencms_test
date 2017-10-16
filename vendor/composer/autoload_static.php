<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit58b041154e65a7711e2c6302b2b413ae
{
    public static $files = array (
        '1cfd2761b63b0a29ed23657ea394cb2d' => __DIR__ . '/..' . '/topthink/think-captcha/src/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\mongo\\' => 12,
            'think\\composer\\' => 15,
            'think\\captcha\\' => 14,
            'think\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\mongo\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-mongo/src',
        ),
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\captcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-captcha/src',
        ),
        'think\\' => 
        array (
            0 => __DIR__ . '/../..' . '/thinkphp/library/think',
            1 => __DIR__ . '/..' . '/topthink/think-image/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit58b041154e65a7711e2c6302b2b413ae::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit58b041154e65a7711e2c6302b2b413ae::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
