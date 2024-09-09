<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

function getInstrumentNames($directory) {
    $instruments = [];
    $files = glob($directory . '/*.txt');
    foreach ($files as $file) {
        $filename = basename($file, '.txt');
        $instruments[] = $filename;
    }
    return $instruments;
}

$group = $_GET['group'] ?? '';

if ($group === 'Forex') {
    $directory = __DIR__ . '/instruments/forex';
} elseif ($group === 'Metals') {
    $directory = __DIR__ . '/instruments/metals';
} else {
    $directory = '';
}

if ($directory && is_dir($directory)) {
    echo json_encode(getInstrumentNames($directory));
} else {
    echo json_encode([]);
}
?>
