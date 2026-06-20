<?php
return [
    'POST /auth/login' => ['Controllers\AuthController', 'login'],
    'POST /auth/register' => ['Controllers\AuthController', 'register'],
    'GET /absensi/jadwal/today' => ['Controllers\AbsensiController', 'getJadwalToday'],
    'POST /absensi' => ['Controllers\AbsensiController', 'store'],
    'GET /absensi/history' => ['Controllers\AbsensiController', 'history'],
];