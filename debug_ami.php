<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Agus\Tabler\Classes\GoogleDriveReader;

// Flush output immediately
ob_implicit_flush(true);

$GAS_URL = GoogleDriveReader::WEB_APP_URL;
$prodiId = GoogleDriveReader::FOLDER_IDS['AMI_LEVEL_PRODI'] ?? null;

echo "=== AMI DEBUG START ===\n";
echo "Folder ID (PRODI): $prodiId\n";
flush();

// Step 1: Direct curl test (3s timeout) to check GAS is reachable
echo "\n[1] Testing GAS connection (raw curl, 3s timeout)...\n";
flush();
$url = $GAS_URL . '?action=listNested&folderId=' . urlencode($prodiId) . '&depth=1';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$t0 = microtime(true);
$result = curl_exec($ch);
$elapsed = round(microtime(true) - $t0, 2);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);
echo "HTTP: $httpCode  Time: {$elapsed}s\n";
if ($curlError) echo "cURL error: $curlError\n";
if ($result) {
    $decoded = json_decode($result, true);
    echo "success field: " . var_export($decoded['success'] ?? 'MISSING', true) . "\n";
    echo "structure count: " . count($decoded['structure'] ?? []) . "\n";
    $sample = array_slice($decoded['structure'] ?? [], 0, 2);
    foreach ($sample as $item) {
        echo "  folder: [{$item['name']}] children: " . count($item['children'] ?? []) . "\n";
    }
} else {
    echo "No response body!\n";
}
flush();

// Step 2: Check cached structured result
echo "\n[2] getCategoryNestedStructure (PRODI, with cache)...\n";
flush();
$t0 = microtime(true);
$structured = GoogleDriveReader::getCategoryNestedStructure($prodiId);
$elapsed = round(microtime(true) - $t0, 2);
echo "Time: {$elapsed}s\n";
echo "Periode count: " . count($structured) . "\n";
foreach ($structured as $periode => $prodis) {
    echo "  Periode: $periode — " . count($prodis) . " prodi(s)\n";
    foreach (array_slice($prodis, 0, 2, true) as $pk => $files) {
        echo "    Prodi: $pk — " . count($files) . " file(s)\n";
    }
}
if (empty($structured)) echo "  EMPTY!\n";
flush();

echo "\n=== DONE ===\n";

// keep original foreach for compat:
$data = ['PRODI_CHECK' => ['levels'=>2,'data'=>$structured]];
foreach ($data as $level => $meta) {
    $levelData = $meta['data'] ?? [];
    // noop - output already above
    if (empty($levelData)) {
        // no-op
        continue;
    }

    foreach (array_slice($levelData, 0, 3, true) as $k => $v) {
        echo "  periode: $k → " . count($v) . " prodi(s)\n";
        foreach (array_slice($v, 0, 3, true) as $pk => $files) {
            echo "    prodi: $pk → " . count($files) . " file(s)\n";
        }
    }
    echo "\n";
}
