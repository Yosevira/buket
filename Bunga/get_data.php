<?php
include 'register.php'; // Include fungsi readCsv()

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

if ($type === 'regencies') {
    $data = readCsv('data/regencies.csv');
    $filtered = array_filter($data, function ($item) use ($id) {
        return $item['province_id'] == $id;
    });
    echo json_encode(array_values($filtered));
}

if ($type === 'districts') {
    $data = readCsv('data/districts.csv');
    $filtered = array_filter($data, function ($item) use ($id) {
        return $item['regency_id'] == $id;
    });
    echo json_encode(array_values($filtered));
}

if ($type === 'villages') {
    $data = readCsv('data/villages.csv');
    $filtered = array_filter($data, function ($item) use ($id) {
        return $item['district_id'] == $id;
    });
    echo json_encode(array_values($filtered));
}
?>
