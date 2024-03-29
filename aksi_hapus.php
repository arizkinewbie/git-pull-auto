<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan ID yang akan dihapus
    $id = $_POST['id'];

    // Membaca data dari file JSON
    $data = json_decode(file_get_contents('data.json'), true);

    // Menghapus data yang sesuai dengan ID
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            unset($data[$key]);
            break;
        }
    }

    // Menulis kembali data ke file JSON
    file_put_contents('data.json', json_encode(array_values($data), JSON_PRETTY_PRINT));
}
?>
