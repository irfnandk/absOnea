<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
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
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) session_start();
use App\config\database;
$db = database::getConnection();
$action = $_GET['action'] ?? 'home';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            header('Location: ?action=dashboard');
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    } else {
        $error = 'Harap isi email dan password.';
    }
    include __DIR__ . '/../app/views/auth/login.php';
    exit;
}
if ($action === 'logout') {
    session_destroy();
    header('Location: ?action=home');
    exit;
}
$protected = ['dashboard', 'admin', 'dosen', 'mahasiswa', 'api'];
if (in_array($action, $protected) && !isset($_SESSION['user_id'])) {
    header('Location: ?action=login');
    exit;
}
switch ($action) {
    case 'home':
        include __DIR__ . '/../app/views/landing.php';
        break;
    case 'login':
        include __DIR__ . '/../app/views/auth/login.php';
        break;
    case 'dashboard':
        $role = $_SESSION['role'] ?? 'mahasiswa';
        if ($role === 'admin') {
            $controller = new App\controllers\admincontroller();
            $controller->dashboard();
        } elseif ($role === 'dosen') {
            $controller = new App\controllers\dosencontroller();
            $controller->dashboard();
        } else {
            $controller = new App\controllers\mahasiswacontroller();
            $controller->dashboard();
        }
        break;
    case 'admin':
        $controller = new App\controllers\admincontroller();
        $sub = $_GET['sub'] ?? 'dashboard';
        if ($sub === 'dashboard') $controller->dashboard();
        elseif ($sub === 'mahasiswa') $controller->mahasiswaIndex();
        elseif ($sub === 'mahasiswa-create') $controller->mahasiswaCreate();
        elseif ($sub === 'mahasiswa-edit') $controller->mahasiswaEdit($_GET['id'] ?? null);
        elseif ($sub === 'mahasiswa-delete') $controller->mahasiswaDelete($_GET['id'] ?? null);
        elseif ($sub === 'dosen') $controller->dosenIndex();
        elseif ($sub === 'dosen-create') $controller->dosenCreate();
        elseif ($sub === 'dosen-edit') $controller->dosenEdit($_GET['id'] ?? null);
        elseif ($sub === 'dosen-delete') $controller->dosenDelete($_GET['id'] ?? null);
        elseif ($sub === 'matakuliah') $controller->matakuliahIndex();
        elseif ($sub === 'matakuliah-create') $controller->matakuliahCreate();
        elseif ($sub === 'matakuliah-edit') $controller->matakuliahEdit($_GET['id'] ?? null);
        elseif ($sub === 'matakuliah-delete') $controller->matakuliahDelete($_GET['id'] ?? null);
        elseif ($sub === 'jadwal') $controller->jadwalIndex();
        elseif ($sub === 'jadwal-create') $controller->jadwalCreate();
        elseif ($sub === 'jadwal-edit') $controller->jadwalEdit($_GET['id'] ?? null);
        elseif ($sub === 'jadwal-delete') $controller->jadwalDelete($_GET['id'] ?? null);
        elseif ($sub === 'registrasi-wajah') $controller->registrasiWajah();
        elseif ($sub === 'simpan-wajah') {
            $controller->simpanWajah();
            exit;
        }
        elseif ($sub === 'cek-wajah') {
            $controller->cekWajah();
            exit;
        }
        elseif ($sub === 'get-terdaftar') {
            $controller->getTerdaftar();
            exit;
        }
        elseif ($sub === 'hapus-wajah') {
            $controller->hapusWajah();
            exit;
        }
        elseif ($sub === 'detect-wajah') {
            $controller->detectWajah();
            exit;
        }
        elseif ($sub === 'laporan') $controller->laporan();
        elseif ($sub === 'export-excel') $controller->exportExcel();
        else $controller->dashboard();
        break;
    case 'dosen':
        $controller = new App\controllers\dosencontroller();
        $sub = $_GET['sub'] ?? 'dashboard';
        if ($sub === 'dashboard') $controller->dashboard();
        elseif ($sub === 'laporan') $controller->laporan();
        elseif ($sub === 'export-excel') $controller->exportExcel();
        else $controller->dashboard();
        break;
    case 'mahasiswa':
        $controller = new App\controllers\mahasiswacontroller();
        $sub = $_GET['sub'] ?? 'dashboard';
        if ($sub === 'dashboard') $controller->dashboard();
        elseif ($sub === 'absensi') $controller->absensi();
        elseif ($sub === 'riwayat') $controller->riwayat();
        elseif ($sub === 'profil') $controller->profil();
        else $controller->dashboard();
        break;
    case 'api':
        header('Content-Type: application/json');
        try {
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
                exit;
            }
            $api = new App\controllers\apicontroller();
            $method = $_GET['method'] ?? '';
            if ($method === 'absen') {
                $api->doAbsensi();
            } elseif ($method === 'statistik') {
                $api->getStatistik();
            } else {
                echo json_encode(['error' => 'Method not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    default:
        include __DIR__ . '/../app/views/landing.php';
        break;
}