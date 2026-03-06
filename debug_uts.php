<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle($request = Illuminate\Http\Request::capture());

use Agus\Tabler\Classes\GoogleDriveReader;

$folderId = GoogleDriveReader::FOLDER_IDS['Monev UTS UAS -- RPS'];

// Clear cache
\Cache::forget('gdrive_structure_' . md5($folderId));
\Cache::forget('gdrive_folder_' . md5($folderId));
echo "Cache cleared. Folder ID: $folderId\n";

// Fetch raw nested structure (fresh, no cache)
$raw = GoogleDriveReader::getNestedStructure($folderId, 2);
echo "Raw item count: " . count($raw) . "\n\n";

foreach ($raw as $item) {
    $mimeShort = str_replace('application/vnd.google-apps.', '', $item['mimeType']);
    echo "PERIOD: " . $item['name'] . " [$mimeShort]\n";
    $children = $item['children'] ?? [];
    echo "  children count: " . count($children) . "\n";
    foreach ($children as $child) {
        $childMime = str_replace('application/vnd.google-apps.', '', $child['mimeType']);
        echo "  CHILD: " . $child['name'] . " [$childMime]\n";
        $gc = $child['children'] ?? [];
        echo "    grandchildren count: " . count($gc) . "\n";
        foreach ($gc as $f) {
            $gcMime = str_replace('application/vnd.google-apps.', '', $f['mimeType']);
            echo "    FILE: " . $f['name'] . " [$gcMime]\n";
        }
    }
    echo "\n";
}

// Also test via getCategoryNestedStructure (which uses cache)
echo "\n=== formatNestedToGrouped result ===\n";
$grouped = GoogleDriveReader::getCategoryNestedStructure($folderId);
echo "Period count: " . count($grouped) . "\n";
foreach ($grouped as $period => $prodiGroups) {
    echo "PERIOD: $period\n";
    echo "  prodi count: " . count($prodiGroups) . "\n";
    foreach ($prodiGroups as $prodi => $files) {
        echo "  PRODI: $prodi - files: " . count($files) . "\n";
    }
}
