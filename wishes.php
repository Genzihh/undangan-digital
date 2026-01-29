<?php
// Izinkan akses dari mana saja
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$file = 'data_ucapan.json';

// 1. JIKA MINTA DATA (GET) - Untuk menampilkan list
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($file)) {
        echo file_get_contents($file);
    } else {
        echo "[]";
    }
    exit;
}

// 2. JIKA KIRIM DATA (POST) - Untuk simpan ucapan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirim JS
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['nama']) && isset($input['ucapan'])) {
        // Ambil data lama
        $current_data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($current_data)) $current_data = [];

        // Tambah data baru di paling atas (unshift)
        $new_entry = [
            'nama' => htmlspecialchars($input['nama']),
            'status' => htmlspecialchars($input['status']),
            'ucapan' => htmlspecialchars($input['ucapan']),
            'waktu' => date('d M, H:i') // Catat jam kirim
        ];
        
        array_unshift($current_data, $new_entry);

        // Simpan balik ke file
        file_put_contents($file, json_encode($current_data));

        echo json_encode(["status" => "success", "message" => "Tersimpan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    }
    exit;
}
?>