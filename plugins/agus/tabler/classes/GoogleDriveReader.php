<?php namespace Agus\Tabler\Classes;

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
        'Periode 2021/2022 ami' => '1BATxq9QQWQYlGi8F-JBATFlzIC5mDbMP',
        'Periode 2022/2023 ami' => '1ieTzD_3btjvLTfW7C6RxNHb1C2VLa5U-',
        'Periode 2023/2024 ami' => '13NRDa7pbVfCZygKaCpPV3nLQ9sSq5CJf',
        'Periode 2024/2025 ami' => '1EcTwagdL_9G2XfgSrChU0G0xPYF0rT3m',
        'Periode 2021/2022 rtm' => '1DgElsjM4zhss7YqNI2iWsEzV_QnuJ-Xu',
        'Periode 2022/2023 rtm' => '1vYDNCJ4ZaO7L-zvvP8Jsn_QbnhZ3irHV',
        'Periode 2023/2024 rtm' => '1NV6tY6YcJbiEn6xHSHxuZ0FEchItMW4l',
        'Periode 2024/2025 rtm' => '1JWgZdm8jqxqr2oEG9_b1kWGDaI5vaPe6',
        'Periode 2021/2022 grafik kepuasan' => '13P2eHmO_117n0UgaaiUMfzS1cmrppqUR',
        'Periode 2022/2023 grafik kepuasan' => '1x5YCM73XKi9bFi2aD2mbJhYFCb4Tw2vH',
        'Periode 2023/2024 grafik kepuasan' => '1fiqgtUw1LoNZ2HXyfa4Bhchlwlg12FCr',
        'Periode 2024/2025 grafik kepuasan' => '1Evd8y0058W-23OB-InXtJqnf8-QWmh_6',
        'Periode 2021/2022 survei kepuasan' => '10JyQXhPBRa0igiiuuZ2dfv_zLlPlCVHe',
        'Periode 2022/2023 survei kepuasan' => '1jpDNHw36inEAhUQ_QucSj4d8_u3ClCvh',
        'Periode 2023/2024 survei kepuasan' => '16x-mqkzndK9nkq9ea2UOOeodnRuURmjK',
        'Periode 2024/2025 survei kepuasan' => '14gfNvjocgPtmEJAiPgz0JbgfxEh_HiQm',
        'Periode 2021/2022 monev survei kepuasan' => '1Nch7q_sxo0pcs50yJb6C-ibFcupkXCuT',
        'Periode 2022/2023 monev survei kepuasan' => '15wwI4zAIoa2-Lo_Hftb_FE5FUQWuXacm',
        'Periode 2023/2024 monev survei kepuasan' => '1cqsVPOZjmjjEKFuHS3B9af10tw5lWqDs',
        'Periode 2024/2025 monev survei kepuasan' => '1QkQaZK1bEdHWE_Df_G1u2mdJ3xDIiMA2',
        'Periode 2021/2022 rtm kepuasan' => '1ESIfAZOy6cG_Vr5xAQwxDdkdQoOSkAXI',
        'Periode 2022/2023 rtm kepuasan' => '1e4JdyKLsWb9XXhSmbIFA5s6vd767dRVN',
        'Periode 2023/2024 rtm kepuasan' => '1lF7WPGuMXjNWgm25nmKkPOcAxTdZCQWz',
        'Periode 2024/2025 rtm kepuasan' => '1fpcf0VxXe2S_YNdBXFZvgBAWQDgoF9Ys',
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
            if ($category === 'Keterbukaan Informasi Publik' ||
                strpos($category, 'Periode') === 0 ||
                strpos($category, 'Standar SPMI') === 0 ||
                strpos($category, 'Formulir SPMI') === 0 ||
                strpos($category, 'Manual Mutu') === 0 ||
                strpos($category, 'SOP SPMI') === 0 ||
                strpos($category, 'Kebijakan SPMI') === 0 ||
                strpos($category, 'UDINUS') === 0 ||
                strpos($category, 'Huachiew Chalermprakiet') === 0 ||
                strpos($category, 'TGBC Thailand') === 0 ||
                strpos($category, 'Standar Lampauan') === 0 ||
                strpos($category, 'Akreditasi') === 0 ||
                strpos($category, 'In House Training ISO') === 0 ||
                strpos($category, 'Workshop') === 0 ||
                strpos($category, 'Pemenang Hibah SPMI') === 0 ||
                strpos($category, 'ISO International 2021') === 0 ||
                strpos($category, 'ISO International 2024') === 0 ||
                is_numeric($category)
            ) {
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
     * Get all Formulir SPMI PDF files organized by category
     *
     * @return array
     */
    public static function getAllStandarSpmiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Standar' dan diakhiri dengan 'standar spmi'
            if (strpos($category, 'Standar SPMI') !== 0) {
                continue;
            }

            // Check if ends with 'standar spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('standar spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Formulir SPMI PDF files organized by category
     *
     * @return array
     */
    public static function getAllFormulirSpmiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'Formulir SPMI') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Manual Mutu PDF files organized by category
     *
     * @return array
     */
    public static function getAllManualMutuFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'Manual Mutu') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Manual Mutu PDF files organized by category
     *
     * @return array
     */
    public static function getAllSopSpmiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'SOP SPMI') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Manual Mutu PDF files organized by category
     *
     * @return array
     */
    public static function getAllKebijakanSpmiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'Kebijakan SPMI') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Standar Lampauan PDF files organized by category
     *
     * @return array
     */
    public static function getAllStandarLampauanFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'Standar Lampauan') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Standar Lampauan PDF files organized by category
     *
     * @return array
     */
    public static function getAllAkreditasiPerguruanTinggiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'Akreditasi Perguruan Tinggi') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Standar Lampauan PDF files organized by category
     *
     * @return array
     */
    public static function getAllAkredInterIsoFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Hanya ambil kategori yang mengandung 'Formulir' dan diakhiri dengan 'formulir spmi'
            if (strpos($category, 'Akreditasi International ISO') !== 0) {
                continue;
            }

            // Check if ends with 'formulir spmi' (case-insensitive)
            // if (stripos(strrev(strtolower($category)), strrev(strtolower('formulir spmi'))) !== 0) {
            //     continue;
            // }

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
     * Get all Manual Mutu PDF files organized by category
     *
     * @return array
     */
    public static function getAllUdinusFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori UDINUS (case-insensitive, cocok persis)
            if ($category !== 'UDINUS') {
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
     * Get all Huachiew Chalermprakiet PDF files organized by category
     *
     * @return array
     */
    public static function getAllHuachiewFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori Huachiew Chalermprakiet (case-insensitive, cocok persis)
            if ($category !== 'Huachiew Chalermprakiet') {
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
     * Get all TGBC Thailand PDF files organized by category
     *
     * @return array
     */
    public static function getAllTgbcFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori TGBC Thailand (case-insensitive, cocok persis)
            if ($category !== 'TGBC Thailand') {
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
     * Get all TGBC Thailand PDF files organized by category
     *
     * @return array
     */
    public static function getAllInHouseTrainingIsoFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori TGBC Thailand (case-insensitive, cocok persis)
            if ($category !== 'In House Training ISO') {
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
     * Get all Workshop AUN QA PDF files organized by category
     *
     * @return array
     */
    public static function getAllWorkshopAunQaFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori TGBC Thailand (case-insensitive, cocok persis)
            if ($category !== 'Workshop Akreditasi AUN-QA') {
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
     * Get all Workshop AUN QA PDF files organized by category
     *
     * @return array
     */
    public static function getAllWorkshopPelatihanAmiFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori TGBC Thailand (case-insensitive, cocok persis)
            if ($category !== 'Workshop Pelatihan AMI') {
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
     * Get all Workshop AUN QA PDF files organized by category
     *
     * @return array
     */
    public static function getAllWorkshopPeningkatanPenjaminMutuFiles()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori TGBC Thailand (case-insensitive, cocok persis)
            if ($category !== 'Workshop Peningkatan Penjamin Mutu') {
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
     * Get all Workshop AUN QA PDF files organized by category
     *
     * @return array
     */
    public static function getAllPemenangHibahSpmiTahun2021Files()
    {
        $result = [];

        foreach (self::FOLDER_IDS as $category => $folderId) {
            // Ambil hanya kategori TGBC Thailand (case-insensitive, cocok persis)
            if ($category !== 'Pemenang Hibah SPMI Tahun 2021') {
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
     * Get all Workshop AUN QA PDF files organized by category
     *
     * @return array
     */
    public static function getAll2019Files()
    {
        $folderId = self::FOLDER_IDS['2019'] ?? null;

        if (!$folderId) {
            return [];
        }

        $files = self::getFilesFromFolder($folderId);

        // Filter hanya file PDF
        $pdfFiles = array_filter($files, function($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });

        // Transformasi format dan bersihkan nama file
        return array_map(function($file) {
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

    /**
     * Get all 2021 PDF files organized by category
     *
     * @return array
     */
    public static function getAll2021Files()
    {
        $folderId = self::FOLDER_IDS['2021'] ?? null;

        if (!$folderId) {
            return [];
        }

        $files = self::getFilesFromFolder($folderId);

        $pdfFiles = array_filter($files, function($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });

        return array_map(function($file) {
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

    /**
     * Get all 2023 PDF files organized by category
     *
     * @return array
     */
    public static function getAll2023Files()
    {
        $folderId = self::FOLDER_IDS['2023'] ?? null;

        if (!$folderId) {
            return [];
        }

        $files = self::getFilesFromFolder($folderId);

        $pdfFiles = array_filter($files, function($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });

        return array_map(function($file) {
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

    /**
     * Get all ISO International 2021 PDF files organized by category
     *
     * @return array
     */
    public static function getAllIsoInternational2021Files()
    {
        $folderId = self::FOLDER_IDS['ISO International 2021'] ?? null;

        if (!$folderId) {
            return [];
        }

        $files = self::getFilesFromFolder($folderId);

        $pdfFiles = array_filter($files, function($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });

        return array_map(function($file) {
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

    /**
     * Get all ISO International 2024 PDF files organized by category
     *
     * @return array
     */
    public static function getAllIsoInternational2024Files()
    {
        $folderId = self::FOLDER_IDS['ISO International 2024'] ?? null;

        if (!$folderId) {
            return [];
        }

        $files = self::getFilesFromFolder($folderId);

        $pdfFiles = array_filter($files, function($file) {
            return isset($file['mimeType']) && $file['mimeType'] === 'application/pdf';
        });

        return array_map(function($file) {
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
