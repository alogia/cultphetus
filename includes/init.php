<?php

include_once __APPLICATION . 'controller_base.class.php';
include_once __APPLICATION . 'registry.class.php';
include_once __APPLICATION . 'router.class.php';
include_once __APPLICATION . 'template.class.php';
include_once __APPLICATION . 'storage.class.php';

spl_autoload_register(
    function ($class) {
        $filename = strtolower($class) . '.class.php';
        $file = __SITE_PATH . '/model/' . $filename;
        if ( !file_exists($file) ) {
            return false;
        }
        include($file);
    }
);

$registry = new Registry();

# $registry->storage = Storage::getInstance();

?>
