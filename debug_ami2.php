<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Agus\Tabler\Classes\GoogleDriveReader;

\Cache::flush();

$prodiId = GoogleDriveReader::FOLDER_IDS['AMI_LEVEL_PRODI'];
echo "AMI_LEVEL_PRODI folder ID: $prodiId\n";

// Test raw API call for Prodi with depth=2
$url = GoogleDriveReader::WEB_APP_URL . '?action=listNested&folderId=' . urlencode($prodiId) . '&depth=2';
echo "Calling: $url\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$raw = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP $code\n";
$data = json_decode($raw, true);

if (!$data || !($data['success'] ?? false)) {
    echo "ERROR: " . ($data['error'] ?? 'Unknown') . "\n";
    exit(1);
}

$structure = $data['structure'] ?? [];
echo "Top-level items from AMI_LEVEL_PRODI: " . count($structure) . "\n";
foreach ($structure as $item) {
    echo "  [{$item['mimeType']}] {$item['name']} — " . count($item['children'] ?? []) . " children\n";
    foreach (array_slice($item['children'] ?? [], 0, 3) as $child) {
        echo "    [{$child['mimeType']}] {$child['name']} — " . count($child['children'] ?? []) . " children\n";
    }
}
