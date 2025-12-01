<?php namespace Agus\Tabler\Classes;

class GoogleDriveUploader
{
    /**
     * Google Apps Script Web App URL
     */
    const WEB_APP_URL = 'https://script.google.com/macros/s/AKfycby3xc0R0bdws4ubU1iLBkyevkjjI-FRTq_4DW93KBzKHbp4PugsHVzi_z46xqssErzj/exec';

    /**
     * Upload file to specific Google Drive folder based on category
     * Auto-delete file with same name if exists
     *
     * @param string $filePath Local file path
     * @param int|string $category Category enum (1-6) or category name
     * @param string|null $customFileName Custom filename (optional)
     * @return array Response from Google Apps Script
     */
    public static function uploadToCategory($filePath, $category, $customFileName = null)
    {
        // Normalize category to name
        $categoryName = self::normalizeCategoryName($category);

        if (!$categoryName) {
            throw new \Exception('Invalid category: ' . $category);
        }

        $folderId = GoogleDriveReader::FOLDER_IDS[$categoryName] ?? null;
        if (!$folderId) {
            throw new \Exception('Folder ID not found for category: ' . $categoryName);
        }

        // Tentukan nama file yang akan diupload
        $fileName = $customFileName;
        if ($fileName && !preg_match('/\.pdf$/i', $fileName)) {
            $fileName .= '.pdf';
        }
        if (!$fileName) {
            $fileName = basename($filePath);
        }

        // \Log::info('Uploading to Google Drive', [
        //     'category' => $category,
        //     'categoryName' => $categoryName,
        //     'folderId' => $folderId,
        //     'fileName' => $fileName
        // ]);

        // Cek apakah sudah ada file dengan nama yang sama di folder
        $existingFile = GoogleDriveReader::findFileByName($folderId, $fileName);

        if ($existingFile) {
            // \Log::info('Found existing file with same name, deleting...', [
            //     'fileName' => $fileName,
            //     'fileId' => $existingFile['id']
            // ]);

            // Hapus file lama
            try {
                self::delete($existingFile['id']);
                // \Log::info('Old file deleted successfully', [
                //     'fileId' => $existingFile['id']
                // ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete old file, continuing with upload', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return self::upload($filePath, $folderId, $customFileName);
    }

    /**
     * Normalize category to standard name
     *
     * @param int|string $category
     * @return string|null
     */
    public static function normalizeCategoryName($category)
    {
        \Log::info('normalizeCategoryName called', [
            'category' => $category,
            'type' => gettype($category),
            'is_int' => is_int($category)
        ]);

        // Jika integer dan ada di CATEGORY_ENUM, gunakan CATEGORY_ENUM
        if (is_int($category) && isset(GoogleDriveReader::CATEGORY_ENUM[$category])) {
            $result = GoogleDriveReader::CATEGORY_ENUM[$category];
            \Log::info('Using CATEGORY_ENUM', ['result' => $result]);
            return $result;
        }

        // Jika string, normalize case dan cari match
        $categoryLower = strtolower(trim($category));

        // Mapping dari berbagai format string ke nama standard
        $mapping = [
            'beban belajar mahasiswa' => 'Beban Belajar Mahasiswa',
            'monev dosen' => 'Monev Dosen',
            'monev kehadiran mahasiswa' => 'Monev Kehadiran Mahasiswa',
            'monev materi dengan rps' => 'Monev Materi dengan RPS',
            'monev nilai' => 'Monev Nilai',
            'monev uas uts rps' => 'Monev UTS UAS -- RPS',
            'keterbukaan informasi publik' => 'Keterbukaan Informasi Publik',
            'informasi publik' => 'Keterbukaan Informasi Publik',
            'periode 2021/2022' => 'Periode 2021/2022 ami',
            'periode 2022/2023' => 'Periode 2022/2023 ami',
            'periode 2023/2024' => 'Periode 2023/2024 ami',
            'periode 2024/2025' => 'Periode 2024/2025 ami',
            'periode 2021/2022 rtm' => 'Periode 2021/2022 rtm',
            'periode 2022/2023 rtm' => 'Periode 2022/2023 rtm',
            'periode 2023/2024 rtm' => 'Periode 2023/2024 rtm',
            'periode 2024/2025 rtm' => 'Periode 2024/2025 rtm',
            'periode 2021/2022 grafik kepuasan' => 'Periode 2021/2022 grafik kepuasan',
            'periode 2022/2023 grafik kepuasan' => 'Periode 2022/2023 grafik kepuasan',
            'periode 2023/2024 grafik kepuasan' => 'Periode 2023/2024 grafik kepuasan',
            'periode 2024/2025 grafik kepuasan' => 'Periode 2024/2025 grafik kepuasan',
            'periode 2021/2022 survei kepuasan' => 'Periode 2021/2022 survei kepuasan',
            'periode 2022/2023 survei kepuasan' => 'Periode 2022/2023 survei kepuasan',
            'periode 2023/2024 survei kepuasan' => 'Periode 2023/2024 survei kepuasan',
            'periode 2024/2025 survei kepuasan' => 'Periode 2024/2025 survei kepuasan',
            'periode 2021/2022 monev survei kepuasan' => 'Periode 2021/2022 monev survei kepuasan',
            'periode 2022/2023 monev survei kepuasan' => 'Periode 2022/2023 monev survei kepuasan',
            'periode 2023/2024 monev survei kepuasan' => 'Periode 2023/2024 monev survei kepuasan',
            'periode 2024/2025 monev survei kepuasan' => 'Periode 2024/2025 monev survei kepuasan',
            'periode 2021/2022 rtm kepuasan' => 'Periode 2021/2022 rtm kepuasan',
            'periode 2022/2023 rtm kepuasan' => 'Periode 2022/2023 rtm kepuasan',
            'periode 2023/2024 rtm kepuasan' => 'Periode 2023/2024 rtm kepuasan',
            'periode 2024/2025 rtm kepuasan' => 'Periode 2024/2025 rtm kepuasan',
            'standar spmi' => 'Standar SPMI',
            'formulir spmi' => 'Formulir SPMI',
            'manual mutu' => 'Manual Mutu',
            'sop spmi' => 'SOP SPMI',
            'kebijakan spmi' => 'Kebijakan SPMI',
            'udinus' => 'UDINUS',
            'huachiew chalermprakiet' => 'Huachiew Chalermprakiet',
            'tgbc thailand' => 'TGBC Thailand',
            'in house training iso' => 'In House Training ISO',
            'workshop akreditasi aun-qa' => 'Workshop Akreditasi AUN-QA',
            'workshop pelatihan ami' => 'Workshop Pelatihan AMI',
            'workshop peningkatan penjamin mutu' => 'Workshop Peningkatan Penjamin Mutu',
            'pemenang hibah spmi tahun 2021' => 'Pemenang Hibah SPMI Tahun 2021',
            '2019' => '2019',
            '2021' => '2021',
            '2023' => '2023',
            'iso international 2021' => 'ISO International 2021',
            'iso international 2024' => 'ISO International 2024',
        ];

        // Cek mapping
        if (isset($mapping[$categoryLower])) {
            $result = $mapping[$categoryLower];
            \Log::info('Found in mapping', ['categoryLower' => $categoryLower, 'result' => $result]);
            return $result;
        }

        // Fallback: jika string sudah persis dengan key di FOLDER_IDS, gunakan langsung
        if (isset(GoogleDriveReader::FOLDER_IDS[$category])) {
            \Log::info('Found exact match in FOLDER_IDS', ['category' => $category]);
            return $category;
        }

        // Fallback: cari case-insensitive di FOLDER_IDS
        foreach (GoogleDriveReader::FOLDER_IDS as $key => $id) {
            if (strtolower($key) === $categoryLower) {
                \Log::info('Found case-insensitive match', ['key' => $key]);
                return $key;
            }
        }

        \Log::error('Category not found', ['category' => $category, 'categoryLower' => $categoryLower]);
        return null;
    }

    /**
     * Upload file to specific folder
     *
     * @param string $filePath Local file path
     * @param string $folderId Google Drive folder ID
     * @param string|null $customFileName Custom filename (optional, without extension)
     * @return array Response from Google Apps Script
     */
    public static function upload($filePath, $folderId = null, $customFileName = null)
    {
        $fileData = file_get_contents($filePath);
        $base64 = base64_encode($fileData);

        // Determine filename
        if ($customFileName) {
            // Pastikan ada ekstensi .pdf
            $fileName = $customFileName;
            if (!preg_match('/\.pdf$/i', $fileName)) {
                $fileName .= '.pdf';
            }
        } else {
            $fileName = basename($filePath);
        }

        $postData = [
            'fileName' => $fileName,
            'mimeType' => mime_content_type($filePath),
            'data' => $base64
        ];

        // Add folderId if provided
        if ($folderId) {
            $postData['folderId'] = $folderId;
        }

        $ch = curl_init(self::WEB_APP_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // \Log::info('Google Drive Upload Response', [
        //     'httpCode' => $httpCode,
        //     'curlError' => $curlError,
        //     'response' => $result
        // ]);

        return json_decode($result, true);
    }

    public static function delete($fileId)
    {
        $url = self::WEB_APP_URL . '?action=delete&id=' . urlencode($fileId);

        // \Log::info('Deleting file from Google Drive', [
        //     'fileId' => $fileId,
        //     'url' => $url
        // ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // \Log::info('Google Drive Delete Response', [
        //     'fileId' => $fileId,
        //     'httpCode' => $httpCode,
        //     'curlError' => $curlError,
        //     'response' => $result
        // ]);

        return json_decode($result, true);
    }
}
