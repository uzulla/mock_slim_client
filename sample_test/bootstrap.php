<?php
// auto loader
set_include_path(
    "../lib".PATH_SEPARATOR.
    "../sample_app/lib");
spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    $parts[] = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));
    $path = implode(DIRECTORY_SEPARATOR, $parts);
    $file = stream_resolve_include_path($path.'.php');
    if($file !== false) {
        require $file;
    }
});

session_start();
