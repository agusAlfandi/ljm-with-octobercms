<?php
namespace Agus\Tabler\Classes;

class GoogleDriveReader
{
    /**
     * Google Apps Script Web App URL
     */
    const WEB_APP_URL = 'https://script.google.com/macros/s/AKfycbyNqaf-liLRmnudEWieYeOsvhRTjpUVi1b9WbIvVIglIEVKWIqwbdhWoCcw4jCIoJ4A/exec';

    /**
     * Google Drive Folder IDs per category
     * Update dengan Folder ID yang berbeda untuk setiap kategori
     */
    const FOLDER_IDS = [
        'Beban Belajar Mahasiswa' => '153Lr6DnNtdu1oO3H5vDTyv_5-AJAG95e',
        'Monev Dosen' => '1n97kyLYK0xzoaI4qfqfbGJLb6UEMMp6W',
        'Monev Kehadiran Mahasiswa' => '1XctG6rx24UQBHJxmvY8GnrqB1o3K0NSb',
        'Monev Materi dengan RPS' => '1rmfChud543iSWdwqwq0dHWUZbQpd-0QL',
        'Monev Nilai' => '115Bz4SlJ5HF4T_NqRnQJxiUfDLB2eCLO',
        'Monev UTS UAS -- RPS' => '156b9htrSnUxPNx47E5DQmXfslRjVHJvu',
        'Keterbukaan Informasi Publik' => '1JaFINzQpwGDEJMeLkGWpdzox4G6cXUst',

        // Root Category Folders (Opsi A: Automated)
        'ROOT_AMI' => '1wsIgC4NLLi1YrqeTfVR8BycKYWFLtzab',
        'ROOT_MONEV' => '1QUyXBU-v1Rpej3c11KHKtfzQEeOU7HXB',
        'ROOT_RTM' => '14geE5fwAKq-WccSByexh5y4io_NhKly0',
        'ROOT_SURVEY_KEPUASAN' => '1c3zdOeRZ0oHiOgIwP6fySjHqGLF7Gwht',
        'ROOT_GRAFIK_KEPUASAN' => '1r9HT0OU_qFel9Opxag-Udu-BG_5_KtBC',
        'ROOT_SURVEI_KEPUASAN' => '1x62AJbGPXo66gCpAxp73LMW5r9WxdDfN',
        'ROOT_MONEV_SURVEI_KEPUASAN' => '17zBtOcyFoilnmojiSRvwwy_B5AxYqoK8',
        'ROOT_RTM_KEPUASAN' => '1BEROZa-ZPzCGqR3iTY5DkGxRIGbF3Gcb',

        'Standar SPMI' => '1ahg6lJJU1c9FLFNe6gi20QOOkra-Erdz',
        'Formulir SPMI' => '1NQ2Ae_wv9LEVZv2tHEbHrO3i5CYqox2D',
        'Manual Mutu' => '15E5pSNy06zIG0xoewZ7CL7_MfOq_xKw2',
        'SOP SPMI' => '1EyxyDgxrtWWm7cnJQ7xFLEtRYw2LP5Ao',
        'Kebijakan SPMI' => '1Tt-cmBl6Q13QMxvHuXnBJS5RXNFzIJyg',
        'Standar Lampauan' => '128nHq_j6eLQbUlMh9J65aeQyM32-5pv0',
        'Akreditasi Perguruan Tinggi' => '1bMxC6V99_S5z-4WSXQkQ5pojaX1PK_UC',
        'Akreditasi International ISO' => '1clIlDo2leiZJM-TKbPmSO-AiXHl41zDW',
        'UDINUS' => '1BcqxXUVIg0y861qJhDDJhPN4YecR-akr',
        'Huachiew Chalermprakiet' => '1DDkmH20CA4esGgwUSJe044YJGE2TfSN-',
        'TGBC Thailand' => '1DnHsDBJ9RfdlSNT_weSjp2e37gRFs9fW',
        'In House Training ISO' => '1bR1-LlZ5x4zP4A_vw_fD0muNBK4ufijb',
        'Workshop Akreditasi AUN-QA' => '1jw48Dknz1ttoV7rpS6s_k9Vo0Zze1oyT',
        'Workshop Pelatihan AMI' => '1r0rY6y_14GbBsrKrxeg-CoANMMr7qO6_',
        'Workshop Peningkatan Penjamin Mutu' => '18k8f1CKEvDItuZV7v5CCd92THGDgNwCW',
        'Pemenang Hibah SPMI Tahun 2021' => '1CcB3pr1JKCiBo0wXHC10OtIKUKv9TWLQ',
        '2019' => '1NCiDXiF37r-mmcAWhpsuBigslUCeWccG',
        '2021' => '1l2ZYnOPGqVqeGHgLZbmOwMALaxYis13M',
        '2023' => '16aT6KOZRRF_WYvi4Sg1v7oYBqEGa_9DB',
        'ISO International 2021' => '1w3Dzn2dwZSnh_Cw_q1F3UkkxqK7Oxpzh',
        'ISO International 2024' => '1k1IoQzHBxZ5WE2NqWmCVTBCl1HCZUxLa',
    ];

    /**
     * Get category enum mapping (same as in Monev model)
     */
    const CATEGORY_ENUM = [
        1 => 'Beban Belajar Mahasiswa',
        2 => 'Monev Dosen',
        3 => 'Monev Kehadiran Mahasiswa',
        4 => 'Monev Materi dengan RPS',
        5 => 'Monev Nilai',
        6 => 'Monev UTS UAS -- RPS',
        7 => 'Keterbukaan Informasi Publik',
        8 => 'Periode 2021/2022 ami',
        9 => 'Periode 2022/2023 ami',
        10 => 'Periode 2023/2024 ami',
        11 => 'Periode 2024/2025 ami',
        12 => 'Periode 2021/2022 rtm',
        13 => 'Periode 2022/2023 rtm',
        14 => 'Periode 2023/2024 rtm',
        15 => 'Periode 2024/2025 rtm',
        16 => 'Periode 2021/2022 grafik kepuasan',
        17 => 'Periode 2022/2023 grafik kepuasan',
        18 => 'Periode 2023/2024 grafik kepuasan',
        19 => 'Periode 2024/2025 grafik kepuasan',
        20 => 'Periode 2021/2022 survei kepuasan',
        21 => 'Periode 2022/2023 survei kepuasan',
        22 => 'Periode 2023/2024 survei kepuasan',
        23 => 'Periode 2024/2025 survei kepuasan',
        24 => 'Periode 2021/2022 monev survei kepuasan',
        25 => 'Periode 2022/2023 monev survei kepuasan',
        26 => 'Periode 2023/2024 monev survei kepuasan',
        27 => 'Periode 2024/2025 monev survei kepuasan',
        28 => 'Periode 2021/2022 rtm kepuasan',
        29 => 'Periode 2022/2023 rtm kepuasan',
        30 => 'Periode 2023/2024 rtm kepuasan',
        31 => 'Periode 2024/2025 rtm kepuasan',
        32 => 'Standar SPMI',
        33 => 'Formulir SPMI',
        34 => 'Manual Mutu',
        35 => 'SOP SPMI',
        36 => 'Kebijakan SPMI',
        37 => 'Standar Lampauan',
        38 => 'Akreditasi Perguruan Tinggi',
        39 => 'Akreditasi International ISO',
        40 => 'UDINUS',
        41 => 'Huachiew Chalermprakiet',
        42 => 'TGBC Thailand',
        43 => 'In House Training ISO',
        44 => 'Workshop Akreditasi AUN-QA',
        45 => 'Workshop Pelatihan AMI',
        46 => 'Workshop Peningkatan Penjamin Mutu',
        47 => 'Pemenang Hibah SPMI Tahun 2021',
        48 => '2019',
        49 => '2021',
        50 => '2023',
        51 => 'ISO International 2021',
        52 => 'ISO International 2024',
    ];

    /**
     * Cache TTL in minutes
     */
    // default value stored in configuration (see config/gdrive.php)
    // this constant is kept for backwards compatibility but the actual
    // TTL is read at runtime so it can be changed without editing code.
    const CACHE_TTL = 60; // fallback if config missing (minutes)


    /**
     * Clear all Google Drive cache entries
     */
    public static function clearCache()
    {
        // Must use the same md5-based keys that getCategoryNestedStructure() stores
        $rootKeys = ['ROOT_AMI', 'ROOT_MONEV', 'ROOT_RTM', 'ROOT_SURVEY_KEPUASAN', 'ROOT_GRAFIK_KEPUASAN', 'ROOT_SURVEI_KEPUASAN', 'ROOT_MONEV_SURVEI_KEPUASAN', 'ROOT_RTM_KEPUASAN'];
        foreach ($rootKeys as $key) {
            if (!empty(self::FOLDER_IDS[$key])) {
                \Cache::forget('gdrive_structure_' . md5(self::FOLDER_IDS[$key]));
            }
        }

        // Also clear individual folder-level caches
        foreach (self::FOLDER_IDS as $folderId) {
            \Cache::forget('gdrive_folder_' . md5($folderId));
        }
    }

    /**
     * Returns the cache TTL (in minutes) used for Drive API results.  The
     * value can be overridden in `config/gdrive.php` or via the
     * GDRIVE_CACHE_TTL environment variable.
     */
    protected static function getCacheTtl()
    {
        // OctoberCMS plugin config files are accessed with the double-colon
        // syntax (author.plugin::file.key).  keep a fallback to the constant for
        // backwards compatibility.
        $ttl = (int)\Config::get('agus.tabler::gdrive.cache_ttl', self::CACHE_TTL);
        return $ttl > 0 ? $ttl : self::CACHE_TTL;
    }

    /**
     * Preload commonly used folder structures so that the first web request
     * doesn't pay for the Google Apps Script roundtrip.  You can call this
     * from a scheduled task or a console command.
     */
    public static function warmCache()
    {
        $roots = [
            self::FOLDER_IDS['ROOT_AMI'] ?? null,
            self::FOLDER_IDS['ROOT_MONEV'] ?? null,
            self::FOLDER_IDS['ROOT_RTM'] ?? null,
            self::FOLDER_IDS['ROOT_SURVEY_KEPUASAN'] ?? null,
            self::FOLDER_IDS['ROOT_GRAFIK_KEPUASAN'] ?? null,
            self::FOLDER_IDS['ROOT_SURVEI_KEPUASAN'] ?? null,
            self::FOLDER_IDS['ROOT_MONEV_SURVEI_KEPUASAN'] ?? null,
            self::FOLDER_IDS['ROOT_RTM_KEPUASAN'] ?? null,
        ];

        foreach ($roots as $rootId) {
            if ($rootId) {
                self::getCategoryNestedStructure($rootId);
            }
        }
    }

    /**
     * Get entire nested folder structure in ONE API call (much faster!)
     * This eliminates multiple round-trips to Google Apps Script
     *
     * @param string $folderId Root folder ID
     * @param int $depth How many levels deep to traverse
     * @return array Nested structure
     */
    public static function getNestedStructure($folderId, $depth = 2)
    {
        $url = self::WEB_APP_URL . '?action=listNested&folderId=' . urlencode($folderId) . '&depth=' . $depth;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Longer timeout for nested fetch

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            \Log::warning('getNestedStructure failed', ['httpCode' => $httpCode]);
            return [];
        }

        $data = json_decode($result, true);

        if (!$data || !isset($data['success']) || !$data['success']) {
            \Log::warning('getNestedStructure invalid response', ['data' => $data]);
            return [];
        }

        return $data['structure'] ?? [];
    }

    /**
     * Convert nested structure from GAS to the format expected by frontend
     * Structure: Period -> Prodi -> Files
     *
     * @param array $items Nested items from getNestedStructure
     * @return array Formatted structure
     */
    private static function formatNestedToGrouped($items)
    {
        $result = [];

        foreach ($items as $item) {
            // Each top-level folder is a Period
            if (isset($item['mimeType']) && $item['mimeType'] === 'application/vnd.google-apps.folder') {
                $periodName = $item['name'];
                $prodiGroups = [];

                // Children are Prodi folders or files
                if (isset($item['children']) && is_array($item['children'])) {
                    foreach ($item['children'] as $child) {
                        if (isset($child['mimeType']) && $child['mimeType'] === 'application/vnd.google-apps.folder') {
                            // This is a Prodi folder
                            $prodiName = $child['name'];
                            $files = [];

                            // Get PDF files from Prodi folder
                            if (isset($child['children']) && is_array($child['children'])) {
                                foreach ($child['children'] as $file) {
                                    if (isset($file['mimeType']) && $file['mimeType'] === 'application/pdf') {
                                        $files[] = [
                                            'fileId' => $file['id'],
                                            'title' => pathinfo($file['name'], PATHINFO_FILENAME),
                                            'fileName' => $file['name'],
                                        ];
                                    }
                                }
                            }

                            $prodiGroups[$prodiName] = $files;
                        } elseif (isset($child['mimeType']) && $child['mimeType'] === 'application/pdf') {
                            // PDF file directly in Period folder (no Prodi subfolder)
                            if (!isset($prodiGroups['Umum'])) {
                                $prodiGroups['Umum'] = [];
                            }
                            $prodiGroups['Umum'][] = [
                                'fileId' => $child['id'],
                                'title' => pathinfo($child['name'], PATHINFO_FILENAME),
                                'fileName' => $child['name'],
                            ];
                        }
                    }
                }

                $result[$periodName] = $prodiGroups;
            }
        }

        // Sort by Period Name descending (newest first)
        krsort($result);

        return $result;
    }

    /**
     * Get files from Google Drive folder (cached)
     *
     * @param string $folderId Google Drive folder ID
     * @return array List of files
     */
    public static function getFilesFromFolder($folderId)
    {
        $cacheKey = 'gdrive_folder_' . md5($folderId);

        $ttl = self::getCacheTtl();
        return \Cache::remember($cacheKey, $ttl, function () use ($folderId) {
            $url = self::WEB_APP_URL . '?action=list&folderId=' . urlencode($folderId);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return [];
            }

            $data = json_decode($result, true);

            if (!$data || !isset($data['success']) || !$data['success']) {
                return [];
            }

            return $data['files'] ?? [];
        });
    }

    /**
     * Find a subfolder by name (case-insensitive partial match)
     * @param string $parentId
     * @param string $name
     * @return array|null
     */
    public static function findSubfolderByName($parentId, $name)
    {
        $files = self::getFilesFromFolder($parentId);
        $nameLower = strtolower(trim($name));

        foreach ($files as $file) {
            if (isset($file['mimeType']) && $file['mimeType'] === 'application/vnd.google-apps.folder') {
                $fileNameLower = strtolower(trim($file['name']));
                if (strpos($fileNameLower, $nameLower) !== false) {
                    return $file;
                }
            }
        }
        return null;
    }

    /**
     * Find a file by name
     * @param string $folderId
     * @param string $fileName
     * @return array|null
     */
    public static function findFileByName($folderId, $fileName)
    {
        $files = self::getFilesFromFolder($folderId);
        $fileNameLower = strtolower(trim($fileName));

        foreach ($files as $file) {
            if (strtolower(trim($file['name'])) === $fileNameLower) {
                return $file;
            }
        }
        return null;
    }

    /**
     * Helper to format PDF files
     *
     * @param array $files
     * @return array
     */
    private static function formatPdfFiles($files)
    {
        return array_map(function ($file) {
            $cleanName = $file['name'];
            $cleanName = preg_replace('/^[a-f0-9]{20,}\./', '', $cleanName);
            $cleanName = preg_replace('/^[a-f0-9]{20,}_/', '', $cleanName);
            $displayName = preg_replace('/\.pdf$/i', '', $cleanName);
            return [
                'title' => $displayName,
                'fileId' => $file['id'],
                'url' => $file['url'],
                'size' => $file['size'] ?? 0,
                'createdDate' => $file['createdDate'] ?? null,
                'modifiedDate' => $file['modifiedDate'] ?? null,
            ];
        }, array_values($files));
    }

    /**
     * Get files grouped by subfolder (Prodi)
     *
     * @param string $parentFolderId
     * @return array
     */
    public static function getFilesGroupedBySubfolder($parentFolderId)
    {
        $items = self::getFilesFromFolder($parentFolderId);
        $result = [];

        // Identify folders and files
        $folders = [];
        $rootPdfFiles = [];

        foreach ($items as $item) {
            if (isset($item['mimeType']) && $item['mimeType'] === 'application/vnd.google-apps.folder') {
                $folders[] = $item;
            }
            elseif (isset($item['mimeType']) && $item['mimeType'] === 'application/pdf') {
                $rootPdfFiles[] = $item;
            }
        }

        // Group files by subfolder
        foreach ($folders as $folder) {
            $subItems = self::getFilesFromFolder($folder['id']);
            $pdfFiles = array_filter($subItems, function ($f) {
                return isset($f['mimeType']) && $f['mimeType'] === 'application/pdf';
            });

            if (!empty($pdfFiles)) {
                $result[$folder['name']] = self::formatPdfFiles($pdfFiles);
            }
        }

        // If there are files in root, put them in a special group
        if (!empty($rootPdfFiles)) {
            $result['Umum'] = self::formatPdfFiles($rootPdfFiles);
        }

        return $result;
    }

    /**
     * Get 3-level nested structure (Category Root -> Periods -> Prodi -> Files)
     * NOW USES SINGLE API CALL for much faster loading!
     *
     * @param string $rootFolderId
     * @return array
     */
    public static function getCategoryNestedStructure($rootFolderId)
    {
        if (!$rootFolderId) {
            return [];
        }

        $cacheKey = 'gdrive_structure_' . md5($rootFolderId);
        $ttl = self::getCacheTtl();

        return \Cache::remember($cacheKey, $ttl, function () use ($rootFolderId) {
            // Use new single-call method (much faster!)
            $nestedItems = self::getNestedStructure($rootFolderId, 2);

            if (empty($nestedItems)) {
                // Fallback to old method if new endpoint not deployed yet
                \Log::info('Falling back to legacy multi-call method');
                return self::getCategoryNestedStructureLegacy($rootFolderId);
            }

            return self::formatNestedToGrouped($nestedItems);
        });
    }

    /**
     * Legacy method - uses multiple API calls (slower)
     * Kept for backward compatibility if GAS not updated
     *
     * @param string $rootFolderId
     * @return array
     */
    private static function getCategoryNestedStructureLegacy($rootFolderId)
    {
        $items = self::getFilesFromFolder($rootFolderId);
        $result = [];

        foreach ($items as $item) {
            if (isset($item['mimeType']) && $item['mimeType'] === 'application/vnd.google-apps.folder') {
                $periodName = $item['name'];
                $result[$periodName] = self::getFilesGroupedBySubfolder($item['id']);
            }
        }

        krsort($result);
        return $result;
    }

    /**
     * Get all Monev PDF files organized by category (Period) and subfolders (Prodi)
     *
     * @return array
     */
    public static function getAllMonevFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_MONEV'] ?? null);
    }

    public static function getAllAmiFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_AMI'] ?? null);
    }

    /**
     * Get all RTM PDF files organized by period and prodi
     *
     * @return array
     */
    public static function getAllRtmFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_RTM'] ?? null);
    }

    private static function getSurveyStructure($prefix)
    {
        $rootId = self::FOLDER_IDS['ROOT_SURVEY_KEPUASAN'] ?? null;
        if (!$rootId)
            return [];

        $items = self::getFilesFromFolder($rootId);
        $result = [];

        foreach ($items as $item) {
            // Look for subfolders that match the survey type (e.g., "Grafik Kepuasan")
            if (isset($item['mimeType']) && $item['mimeType'] === 'application/vnd.google-apps.folder') {
                if (stripos($item['name'], $prefix) !== false) {
                    // Inside this folder, get the nested structure (Periods -> Prodi)
                    return self::getCategoryNestedStructure($item['id']);
                }
            }
        }

        return $result;
    }

    /**
     * Get all Grafik Kepuasan PDF files organized by period and prodi
     *
     * @return array
     */
    public static function getAllGrfKpsFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_GRAFIK_KEPUASAN'] ?? null);
    }

    /**
     * Get all Survei Kepuasan PDF files organized by period and prodi
     *
     * @return array
     */
    public static function getAllSvrKpsFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_SURVEI_KEPUASAN'] ?? null);
    }

    /**
     * Get all Monev Survei Kepuasan PDF files organized by period and prodi
     *
     * @return array
     */
    public static function getAllMnvSvrKpsFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_MONEV_SURVEI_KEPUASAN'] ?? null);
    }

    /**
     * Get all RTM Kepuasan PDF files organized by period and prodi
     *
     * @return array
     */
    public static function getAllRtmKpsFiles()
    {
        return self::getCategoryNestedStructure(self::FOLDER_IDS['ROOT_RTM_KEPUASAN'] ?? null);
    }

    /**
     * Get all Standar SPMI PDF files organized by category
     *
     * @return array
     */
    public static function getAllStandarSpmiFiles()
    {
        $folderId = self::FOLDER_IDS['Standar SPMI'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Standar SPMI' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Formulir SPMI PDF files organized by category
     *
     * @return array
     */
    public static function getAllFormulirSpmiFiles()
    {
        $folderId = self::FOLDER_IDS['Formulir SPMI'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Formulir SPMI' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Manual Mutu PDF files organized by category
     *
     * @return array
     */
    public static function getAllManualMutuFiles()
    {
        $folderId = self::FOLDER_IDS['Manual Mutu'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Manual Mutu' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all SOP SPMI PDF files organized by category
     *
     * @return array
     */
    public static function getAllSopSpmiFiles()
    {
        $folderId = self::FOLDER_IDS['SOP SPMI'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['SOP SPMI' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Kebijakan SPMI PDF files organized by category
     *
     * @return array
     */
    public static function getAllKebijakanSpmiFiles()
    {
        $folderId = self::FOLDER_IDS['Kebijakan SPMI'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Kebijakan SPMI' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Standar Lampauan PDF files organized by category
     *
     * @return array
     */
    public static function getAllStandarLampauanFiles()
    {
        $folderId = self::FOLDER_IDS['Standar Lampauan'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Standar Lampauan' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Akreditasi Perguruan Tinggi PDF files organized by category
     *
     * @return array
     */
    public static function getAllAkreditasiPerguruanTinggiFiles()
    {
        $folderId = self::FOLDER_IDS['Akreditasi Perguruan Tinggi'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Akreditasi Perguruan Tinggi' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Akreditasi International ISO PDF files
     *
     * @return array
     */
    public static function getAllAkredInterIsoFiles()
    {
        $folderId = self::FOLDER_IDS['Akreditasi International ISO'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Akreditasi International ISO' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all UDINUS PDF files
     *
     * @return array
     */
    public static function getAllUdinusFiles()
    {
        $folderId = self::FOLDER_IDS['UDINUS'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['UDINUS' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Huachiew Chalermprakiet PDF files
     *
     * @return array
     */
    public static function getAllHuachiewFiles()
    {
        $folderId = self::FOLDER_IDS['Huachiew Chalermprakiet'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Huachiew Chalermprakiet' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all TGBC Thailand PDF files
     *
     * @return array
     */
    public static function getAllTgbcFiles()
    {
        $folderId = self::FOLDER_IDS['TGBC Thailand'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['TGBC Thailand' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all In House Training ISO PDF files
     *
     * @return array
     */
    public static function getAllInHouseTrainingIsoFiles()
    {
        $folderId = self::FOLDER_IDS['In House Training ISO'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['In House Training ISO' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Workshop Akreditasi AUN-QA PDF files
     *
     * @return array
     */
    public static function getAllWorkshopAunQaFiles()
    {
        $folderId = self::FOLDER_IDS['Workshop Akreditasi AUN-QA'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Workshop Akreditasi AUN-QA' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Workshop Pelatihan AMI PDF files
     *
     * @return array
     */
    public static function getAllWorkshopPelatihanAmiFiles()
    {
        $folderId = self::FOLDER_IDS['Workshop Pelatihan AMI'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Workshop Pelatihan AMI' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Workshop Peningkatan Penjamin Mutu PDF files
     *
     * @return array
     */
    public static function getAllWorkshopPeningkatanPenjaminMutuFiles()
    {
        $folderId = self::FOLDER_IDS['Workshop Peningkatan Penjamin Mutu'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Workshop Peningkatan Penjamin Mutu' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all Pemenang Hibah SPMI Tahun 2021 PDF files
     *
     * @return array
     */
    public static function getAllPemenangHibahSpmiTahun2021Files()
    {
        $folderId = self::FOLDER_IDS['Pemenang Hibah SPMI Tahun 2021'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return ['Pemenang Hibah SPMI Tahun 2021' => self::formatPdfFiles($pdfFiles)];
    }

    /**
     * Get all 2019 PDF files
     *
     * @return array
     */
    public static function getAll2019Files()
    {
        $folderId = self::FOLDER_IDS['2019'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return self::formatPdfFiles($pdfFiles);
    }

    /**
     * Get all 2021 PDF files
     *
     * @return array
     */
    public static function getAll2021Files()
    {
        $folderId = self::FOLDER_IDS['2021'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return self::formatPdfFiles($pdfFiles);
    }

    /**
     * Get all 2023 PDF files
     *
     * @return array
     */
    public static function getAll2023Files()
    {
        $folderId = self::FOLDER_IDS['2023'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return self::formatPdfFiles($pdfFiles);
    }

    /**
     * Get all ISO International 2021 PDF files
     *
     * @return array
     */
    public static function getAllIsoInternational2021Files()
    {
        $folderId = self::FOLDER_IDS['ISO International 2021'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return self::formatPdfFiles($pdfFiles);
    }

    /**
     * Get all ISO International 2024 PDF files
     *
     * @return array
     */
    public static function getAllIsoInternational2024Files()
    {
        $folderId = self::FOLDER_IDS['ISO International 2024'] ?? null;
        if (!$folderId)
            return [];
        $files = self::getFilesFromFolder($folderId);
        $pdfFiles = array_filter($files, function ($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });
        return self::formatPdfFiles($pdfFiles);
    }
}
