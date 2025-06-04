<?php

// Temporary autoloader for testing without Composer
// This is a minimal setup to get the application running

// Basic PSR-4 autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    // Check if the class uses the App namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Include Laravel framework manually for basic functionality
// This is a minimal setup - in production you should use proper Composer
if (!class_exists('Illuminate\\Foundation\\Application')) {
    // Create minimal stubs for testing
    class_alias('stdClass', 'Illuminate\\Foundation\\Application');
    class_alias('stdClass', 'Illuminate\\Http\\Request');
    class_alias('stdClass', 'Illuminate\\Routing\\Router');
}

// Load environment helper functions
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        $configs = [
            'app.name' => 'First Love Church CMS',
            'app.church.name' => 'First Love Church',
            'app.church.location' => 'Foxdale, Lusaka',
            'app.church.email' => 'info@firstlove.church',
            'app.church.phone' => '+260-XXX-XXXXXX',
        ];
        return $configs[$key] ?? $default;
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('route')) {
    function route($name, $parameters = []) {
        $routes = [
            'login' => 'login.html',
            'register' => 'register.html',
            'dashboard' => 'dashboard.html',
            'logout' => 'logout.html',
        ];
        return $routes[$name] ?? '#';
    }
}

// Global variable to simulate authenticated user
$GLOBALS['auth_user'] = null;

if (!function_exists('auth')) {
    function auth() {
        return new class {
            public function check() {
                return isset($GLOBALS['auth_user']);
            }
            public function user() {
                return $GLOBALS['auth_user'];
            }
        };
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="_token" value="dummy_token">';
    }
}

if (!function_exists('date')) {
    // Override to ensure date function works
}

return true; 