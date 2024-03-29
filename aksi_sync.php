<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    // Melakukan permintaan cURL ke URL yang diberikan
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Memeriksa status respon
    if ($http_code == 200) {
        // Mengembalikan pesan "Sync berhasil!"
        echo "Sync berhasil!";
        // Mendapatkan waktu dan tanggal saat ini
        $timestamp = date('Y-m-d H:i:s');

        // Membuat data sync
        $sync_data = [
            'datetime' => $timestamp,
            'url' => $url,
            'response' => $response
        ];

        // Membaca data history dari file history.json
        $history = json_decode(file_get_contents('history.json'), true);

        // Menambahkan data sync ke history
        $history[] = $sync_data;

        // Menuliskan kembali data history ke file history.json
        file_put_contents('history.json', json_encode($history, JSON_PRETTY_PRINT));
    } else {
        // Mengembalikan pesan "Sync gagal!"
        echo "Sync gagal!";
    }
}
?>
