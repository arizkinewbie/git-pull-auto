<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $id = $_POST['id'];
    $web_link = $_POST['web_link'];
    $status = $_POST['status'];

    // Membaca data dari file JSON
    $data = json_decode(file_get_contents('data.json'), true);

    // Jika ID tidak kosong, itu adalah operasi edit
    if (!empty($id)) {
        // Mencari data yang sesuai dengan ID
        foreach ($data as &$item) {
            if ($item['id'] == $id) {
                // Mengupdate data yang ditemukan
                $item['web_link'] = $web_link;
                $item['status'] = $status;
                break;
            }
        }
    } else {
        // Jika ID kosong, itu adalah operasi tambah
        // Membuat ID unik
        $id = uniqid();
        // Menambahkan data baru ke array
        $data[] = array(
            'id' => $id,
            'web_link' => $web_link,
            'status' => $status
        );
    }

    // Menulis kembali data ke file JSON
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
}
?>
