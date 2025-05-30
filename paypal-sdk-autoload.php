<?php
spl_autoload_register(function ($class) {
    $prefix = 'PayPalCheckoutSdk\\';
    $base_dir = __DIR__ . '/Checkout-PHP-SDK-1.0.2/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
