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
        'Periode 2021/2022 ami' => '1JieEkkFcqb0ThMxDNoO4SbQwcRnDJf6k',
        'Periode 2022/2023 ami' => '1FdFs5FPnGP7C1Ba_My7gyfogR8YFurFv',
        'Periode 2023/2024 ami' => '16fhw19FrHiA1Fjz-NozyLx5YMRu1fNe6',
        'Periode 2024/2025 ami' => '1l2qRjMSPynnnWPSTOuxrwnH09SO34O5v',
        'Periode 2021/2022 rtm' => '1n-4nkhv8BhcPhLLZn-nRATRl2krj9tte',
        'Periode 2022/2023 rtm' => '1OUl8We-qUopDAp2VZvYV0zgkPRmcvX75',
        'Periode 2023/2024 rtm' => '1_tdIRZd_MJ6xd3dIkvTEOoCSbpf27sKL',
        'Periode 2024/2025 rtm' => '1gwT5tEayLaj85i_JmUQ9mGzzvQMOYhSF',
        'Periode 2021/2022 grafik kepuasan' => '1AaEPBqFwB5v-gKdDdBhzC7GOzpA1y6UM',
        'Periode 2022/2023 grafik kepuasan' => '1FO1GFMA4Z2sM8qHMRy2Dl0tadPsgrRo2',
        'Periode 2023/2024 grafik kepuasan' => '1Kdkg7uMN6gXX9wYCs204QlKcyNFbxlbn',
        'Periode 2024/2025 grafik kepuasan' => '1rRS-auZQeEKipy9p43PMUWO9YdFWyjfl',
        'Periode 2021/2022 survei kepuasan' => '11HhPk_V70I2Bukti_ajmrV37SL77aMg_',
        'Periode 2022/2023 survei kepuasan' => '1rRB-N9tuwToxNWBzJxggAyVbfV7sqbOf',
        'Periode 2023/2024 survei kepuasan' => '1vPO7cMd2XZz3Xcdl3As-n3TU23t_IwMh',
        'Periode 2024/2025 survei kepuasan' => '1EEXzbJOzQsUP_6rYLk7GzjAZ8XmdeA8c',
        'Periode 2021/2022 monev survei kepuasan' => '1CUMghta-WXJE3XgME8zeE6ZITAsoZ4BB',
        'Periode 2022/2023 monev survei kepuasan' => '1LBnheQfxajI_ysjYFLbLX32IGU1EqJ-d',
        'Periode 2023/2024 monev survei kepuasan' => '19fxja7vZ6n0HvtngWSEFFYlsG6y5f-U8',
        'Periode 2024/2025 monev survei kepuasan' => '1PyoJNecQkoY0htJ_w7gzKLhO-3xEis4X',
        'Periode 2021/2022 rtm kepuasan' => '1BiUYDdKCccYp_qMYgnNUqhKNrUI1P0y_',
        'Periode 2022/2023 rtm kepuasan' => '1LKwTXYWiFAcaVSeNJTvi2ElaG9_wJLWD',
        'Periode 2023/2024 rtm kepuasan' => '1BG9UBfrVSeFer4gbzTxDq-qW6i7pFnuO',
        'Periode 2024/2025 rtm kepuasan' => '1qYeU81Y8lqqEWOZeiRIMSum3a84iPsjH',
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

        // \Log::info('Fetching files from Google Drive', [
        //     'url' => $url,
        //     'folderId' => $folderId
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

        // \Log::info('Google Drive API Response', [
        //     'httpCode' => $httpCode,
        //     'curlError' => $curlError,
        //     'response' => $result
        // ]);

        if ($httpCode !== 200) {
            // \Log::error('Google Drive API error: HTTP ' . $httpCode, [
            //     'curlError' => $curlError,
            //     'response' => $result
            // ]);
            return [];
        }

        $data = json_decode($result, true);

        if (!$data || !isset($data['success']) || !$data['success']) {
            // \Log::error('Google Drive API error: ' . ($data['error'] ?? 'Unknown error'), [
            //     'response' => $result,
            //     'decoded' => $data
            // ]);
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
            // Skip folder "Keterbukaan Informasi Publik" agar tidak tampil di menu Monev
            if ($category === 'Keterbukaan Informasi Publik' || strpos($category, 'Periode') === 0) {
                continue;
            }
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
     * Get all PDF files organized by category
     *
     * @return array
     */
    public static function getAllAmiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Periode' dan diakhiri dengan 'ami'
            if (strpos($category, 'Periode') !== 0 || substr($category, -3) !== 'ami') {
                continue;
            }
            $files = self::getFilesFromFolder($folderId);

            // Filter hanya file PDF
            $pdfFiles = array_filter($files, function($file) {
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transformasi format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
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
            }, array_values($pdfFiles));
        }

        return $result;
    }

    /**
     * Get all PDF files organized by category
     *
     * @return array
     */
    public static function getAllRtmFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Periode' dan diakhiri dengan 'rtm'
            if (strpos($category, 'Periode') !== 0 || substr($category, -3) !== 'rtm') {
                continue;
            }
            $files = self::getFilesFromFolder($folderId);

            // Filter hanya file PDF
            $pdfFiles = array_filter($files, function($file) {
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transformasi format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
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
            }, array_values($pdfFiles));
        }

        return $result;
    }

     /**
     * Get all Grafik Kepuasan PDF files organized by category
     *
     * @return array
     */
    public static function getAllGrfKpsFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Periode' dan diakhiri dengan 'grafik kepuasan'
            if (strpos($category, 'Periode') !== 0) {
                continue;
            }

            // Check if ends with 'grafik kepuasan' (case-insensitive)
            if (stripos(strrev(strtolower($category)), strrev(strtolower('grafik kepuasan'))) !== 0) {
                continue;
            }

            $files = self::getFilesFromFolder($folderId);

            // Filter hanya file PDF
            $pdfFiles = array_filter($files, function($file) {
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transformasi format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
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
            }, array_values($pdfFiles));
        }

        return $result;
    }

    /**
     * Get all Survei Kepuasan PDF files organized by category (excluding Monev Survei Kepuasan)
     *
     * @return array
     */
    public static function getAllSvrKpsFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Skip jika bukan kategori Periode
            if (strpos($category, 'Periode') !== 0) {
                continue;
            }

            // Skip jika kategori adalah 'monev survei kepuasan'
            if (stripos(strtolower($category), 'monev survei kepuasan') !== false) {
                continue;
            }

            // Check if ends with 'survei kepuasan' (case-insensitive)
            if (stripos(strrev(strtolower($category)), strrev(strtolower('survei kepuasan'))) !== 0) {
                continue;
            }

            $files = self::getFilesFromFolder($folderId);

            // Filter hanya file PDF
            $pdfFiles = array_filter($files, function($file) {
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transformasi format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
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
            }, array_values($pdfFiles));
        }

        return $result;
    }

    /**
     * Get all Grafik Kepuasan PDF files organized by category
     *
     * @return array
     */
    public static function getAllMnvSvrKpsFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Periode' dan diakhiri dengan 'monev survei kepuasan'
            if (strpos($category, 'Periode') !== 0) {
                continue;
            }

            // Check if ends with 'monev survei kepuasan' (case-insensitive)
            if (stripos(strrev(strtolower($category)), strrev(strtolower('monev survei kepuasan'))) !== 0) {
                continue;
            }

            $files = self::getFilesFromFolder($folderId);

            // Filter hanya file PDF
            $pdfFiles = array_filter($files, function($file) {
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transformasi format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
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
            }, array_values($pdfFiles));
        }

        return $result;
    }

    /**
     * Get all Grafik Kepuasan PDF files organized by category
     *
     * @return array
     */
    public static function getAllRtmKpsFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Periode' dan diakhiri dengan 'rtm kepuasan'
            if (strpos($category, 'Periode') !== 0) {
                continue;
            }

            // Check if ends with 'rtm kepuasan' (case-insensitive)
            if (stripos(strrev(strtolower($category)), strrev(strtolower('rtm kepuasan'))) !== 0) {
                continue;
            }

            $files = self::getFilesFromFolder($folderId);

            // Filter hanya file PDF
            $pdfFiles = array_filter($files, function($file) {
                return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
            });

            // Transformasi format dan bersihkan nama file
            $result[$category] = array_map(function($file) {
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
