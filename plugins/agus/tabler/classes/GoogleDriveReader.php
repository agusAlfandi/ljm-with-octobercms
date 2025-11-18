<?php namespace Agus\Tabler\Classes;

class GoogleDriveReader
{
    /**
     * Google Apps Script Web App URL
     */
    const WEB_APP_URL = 'https://script.google.com/macros/s/AKfycby3xc0R0bdws4ubU1iLBkyevkjjI-FRTq_4DW93KBzKHbp4PugsHVzi_z46xqssErzj/exec';

    /**
     * Google Drive Folder IDs per category
     * Update dengan Folder ID yang berbeda untuk setiap kategori
     */
    const FOLDER_IDS = [
        'Beban Belajar Mahasiswa' => '1fGubOb8IWVGqm4WNf618xJFyRqBgpvMj',
        'Monev Dosen' => '1yg4AG82aPY5CNJYXoCUhiZwPhFXorAmo',
        'Monev Kehadiran Mahasiswa' => '12a75NMkDxzDm3HLz8cz7jR793jwWePm9',
        'Monev Materi dengan RPS' => '1HbX0lP-s-Wlfux2gWtJ9DItJXlui4_WB',
        'Monev Nilai' => '1RPAFgjL3Z-vvCLx7l8CXbTNeeBqBoMZE',
        'Monev UTS UAS -- RPS' => '1V2ykjb_UKurnaCgi6wsW1kEwSFI_vyTv',
        'Keterbukaan Informasi Publik' => '1PZ9L9NVjiKLOgIAfq8nJxqyaXHIibFOi',
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
    ];

    /**
     * Get files from Google Drive folder
     *
     * @param string $folderId Google Drive folder ID
     * @return array List of files
     */
    public static function getFilesFromFolder($folderId)
    {
        $url = self::WEB_APP_URL . '?action=list&folderId=' . urlencode($folderId);

        \Log::info('Fetching files from Google Drive', [
            'url' => $url,
            'folderId' => $folderId
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        \Log::info('Google Drive API Response', [
            'httpCode' => $httpCode,
            'curlError' => $curlError,
            'response' => $result
        ]);

        if ($httpCode !== 200) {
            \Log::error('Google Drive API error: HTTP ' . $httpCode, [
                'curlError' => $curlError,
                'response' => $result
            ]);
            return [];
        }

        $data = json_decode($result, true);

        if (!$data || !isset($data['success']) || !$data['success']) {
            \Log::error('Google Drive API error: ' . ($data['error'] ?? 'Unknown error'), [
                'response' => $result,
                'decoded' => $data
            ]);
            return [];
        }

        return $data['files'] ?? [];
    }

    /**
     * Get all PDF files organized by category
     *
     * @return array
     */
    public static function getAllMonevFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            $files = self::getFilesFromFolder($folderId);

            // Filter only PDF files
            $pdfFiles = array_filter($files, function($file) {
                // Cek apakah file adalah PDF
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transform to required format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
                $cleanName = $file['name'];

                // Hilangkan hash OctoberCMS (format: 691ac1222c28e953840030.pdf)
                $cleanName = preg_replace('/^[a-f0-9]{20,}\./', '', $cleanName);
                $cleanName = preg_replace('/^[a-f0-9]{20,}_/', '', $cleanName);

                // Hilangkan ekstensi untuk tampilan
                $displayName = preg_replace('/\.pdf$/i', '', $cleanName);

                return [
                    'title' => $displayName,
                    'fileId' => $file['id'],
                    'url' => $file['url'],
                    'size' => $file['size'] ?? 0,
                    'createdDate' => $file['createdDate'] ?? null,
                    'modifiedDate' => $file['modifiedDate'] ?? null,
                ];
            }, array_values($pdfFiles));
        }

        return $result;
    }

    /**
     * Find file by name in specific folder
     *
     * @param string $folderId Google Drive folder ID
     * @param string $fileName File name to search for (exact match)
     * @return array|null File data or null if not found
     */
    public static function findFileByName($folderId, $fileName)
    {
        $files = self::getFilesFromFolder($folderId);

        foreach ($files as $file) {
            if ($file['name'] === $fileName) {
                return $file;
            }
        }

        return null;
    }
}
