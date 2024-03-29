<?php
$data = json_decode(file_get_contents('data.json'), true);
foreach ($data as $key => $value) {
    if ($value['status'] == 'active') {
        $url = $value['web_link'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    }
    if ($http_code == 200) {
        $result = "Sync success!";
    } else {
        $result = "Sync failed!";
    }
    echo "Data Ke-" . $key + 1 . " ($value[web_link]): <b>$result</b><br>";
}
