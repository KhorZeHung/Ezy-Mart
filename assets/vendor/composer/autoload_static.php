<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdfebdf771f183b8a2fb27948a09d1aa4
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdfebdf771f183b8a2fb27948a09d1aa4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdfebdf771f183b8a2fb27948a09d1aa4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdfebdf771f183b8a2fb27948a09d1aa4::$classMap;

        }, null, ClassLoader::class);
    }
}
