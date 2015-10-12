<?php

$basedir = realpath(__DIR__.'/../');

require $basedir.'/vendor/autoload.php';

spl_autoload_register(function($class) use ($basedir){
    $parts = explode('\\', $class);
    $classFile = array_pop($parts);
    $path = '';
    foreach ($parts as $part) {
        $path .= $part.DIRECTORY_SEPARATOR;
    }
    $path .= $classFile.'.php';
    $include = $basedir.'/src/'.$path;
    if (file_exists($include)) {
        include $include;
    }
});