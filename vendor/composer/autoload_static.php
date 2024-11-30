<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb73aacacad27f4711be1d095bada10ff
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'M521\\ForumVaudois\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'M521\\ForumVaudois\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
            1 => __DIR__ . '/../..' . '/src/CRUDManager',
            2 => __DIR__ . '/../..' . '/src/Entity',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb73aacacad27f4711be1d095bada10ff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb73aacacad27f4711be1d095bada10ff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb73aacacad27f4711be1d095bada10ff::$classMap;

        }, null, ClassLoader::class);
    }
}
