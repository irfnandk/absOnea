<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing PHP berjalan<br>";

// Coba autoloader
spl_autoload_register(function ($class) {
    echo "Mencoba autoload: " . $class . "<br>";
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $path = strtolower(str_replace('\\', '/', $relative_class));
        $file = $base_dir . $path . '.php';
        echo "Mencari file: " . $file . "<br>";
        if (file_exists($file)) {
            echo "File ditemukan!<br>";
            require $file;
            echo "File berhasil di-load<br>";
            return;
        }
        echo "File tidak ditemukan!<br>";
    }
});

echo "Mencoba load Database...<br>";
use App\config\Database;
echo "Database class loaded!<br>";

$db = Database::getConnection();
echo "Koneksi berhasil!<br>";