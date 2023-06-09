<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3852a4e5424e496cd94cba6c0a23c7e8
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RobRichards\\XMLSecLibs\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RobRichards\\XMLSecLibs\\' => 
        array (
            0 => __DIR__ . '/..' . '/robrichards/xmlseclibs/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3852a4e5424e496cd94cba6c0a23c7e8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3852a4e5424e496cd94cba6c0a23c7e8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3852a4e5424e496cd94cba6c0a23c7e8::$classMap;

        }, null, ClassLoader::class);
    }
}
