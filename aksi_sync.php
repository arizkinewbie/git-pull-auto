<?php
date_default_timezone_set('Asia/Jakarta');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    $id = $_POST['id'];
    $data = json_decode(file_get_contents('data.json'), true);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Memeriksa status respon
    if ($http_code == 200) {
        echo "Sync berhasil!";
        $timestamp = date('Y-m-d H:i:s');
        foreach ($data as &$item) {
            if ($item['id'] == $id) {
                $item['sync'] = $timestamp;
                if ($item['status'] == 'inactive') {
                    $item['status'] = 'active';
                }
                break;
            }
        }
        file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

        // Membuat data sync untuk history
        $sync_data = [
            'datetime' => $timestamp,
            'url' => $url,
            'response' => $response
        ];
        $history = json_decode(file_get_contents('history.json'), true);
        $history[] = $sync_data;
        file_put_contents('history.json', json_encode($history, JSON_PRETTY_PRINT));
    } else {
        echo "Sync gagal!";
        // Jika respon gagal, ubah status ke inactive
        foreach ($data as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'inactive';
                break;
            }
        }
        file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
    }
}
?>
