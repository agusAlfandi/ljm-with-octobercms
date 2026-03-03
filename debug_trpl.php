<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle($request = Illuminate\Http\Request::capture());

use Agus\Tabler\Classes\GoogleDriveReader;

echo "=== NESTED METHOD (listNested depth=2) ===" . PHP_EOL;
$data = GoogleDriveReader::getNestedStructure('1wsIgC4NLLi1YrqeTfVR8BycKYWFLtzab', 2);
foreach ($data as $period) {
    if ($period['name'] === 'Periode 2024-2025') {
        echo "Period: " . $period['name'] . PHP_EOL;
        foreach ($period['children'] ?? [] as $prodi) {
            $childCount = count($prodi['children'] ?? []);
            if (stripos($prodi['name'], 'rekayasa Perangkat') !== false) {
                echo "  >> Prodi: " . $prodi['name'] . " ID=" . $prodi['id'] . PHP_EOL;
                echo "  >> Children count: " . $childCount . PHP_EOL;
                foreach ($prodi['children'] ?? [] as $f) {
                    echo "    - " . $f['name'] . " (" . $f['mimeType'] . ")" . PHP_EOL;
                }
            }
        }
    }
}

echo PHP_EOL . "=== OLD METHOD (list per folder) ===" . PHP_EOL;
// Clear cache first to get fresh data
\Cache::forget('gdrive_folder_' . md5('1wsIgC4NLLi1YrqeTfVR8BycKYWFLtzab'));

$oldFiles = GoogleDriveReader::getFilesFromFolder('1wsIgC4NLLi1YrqeTfVR8BycKYWFLtzab');
foreach ($oldFiles as $p) {
    if ($p['name'] === 'Periode 2024-2025') {
        echo "Period: " . $p['name'] . " ID=" . $p['id'] . PHP_EOL;
        $prodiItems = GoogleDriveReader::getFilesFromFolder($p['id']);
        foreach ($prodiItems as $pr) {
            if (stripos($pr['name'], 'rekayasa Perangkat') !== false) {
                echo "  >> Prodi: " . $pr['name'] . " ID=" . $pr['id'] . PHP_EOL;
                $files = GoogleDriveReader::getFilesFromFolder($pr['id']);
                echo "  >> Files count: " . count($files) . PHP_EOL;
                foreach ($files as $f) {
                    echo "    - " . $f['name'] . " (" . $f['mimeType'] . ")" . PHP_EOL;
                }
            }
        }
    }
}
