<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $path = str_replace('\\', '/', $relative_class);
        $segments = explode('/', $path);
        $filename = strtolower(array_pop($segments));
        $folder = strtolower(implode('/', $segments));
        $file = $base_dir . $folder . '/' . $filename . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
        $file = $base_dir . $path . '.php';
        if (file_exists($file)) require $file;
    }
});

if (session_status() === PHP_SESSION_NONE) session_start();

use App\config\database;
$db = database::getConnection();

// Cari user dengan role mahasiswa
$stmt = $db->query("SELECT u.id FROM users u JOIN mahasiswa m ON u.id = m.user_id WHERE u.role = 'mahasiswa' LIMIT 1");
$user = $stmt->fetch();

if (!$user) {
    die("Tidak ada user dengan role mahasiswa. Buat dulu akun mahasiswa.");
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = 'mahasiswa';

echo "<h2>Test API Absensi</h2>";
echo "User ID: " . $_SESSION['user_id'] . "<br>";

$api = new App\controllers\apicontroller();
ob_start();
$api->doAbsensi();
$output = ob_get_clean();
echo "<h3>Response API:</h3>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

$stmt = $db->query("SELECT * FROM absensi ORDER BY tanggal DESC, jam_absen DESC LIMIT 5");
$absensi = $stmt->fetchAll();
echo "<h3>5 Absensi Terakhir:</h3>";
echo "<pre>";
print_r($absensi);
echo "</pre>";