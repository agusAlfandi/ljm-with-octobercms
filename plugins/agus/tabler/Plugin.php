<?php namespace Agus\Tabler;

use Backend\Facades\Backend;
use System\Classes\PluginBase;
use Agus\Tabler\Models\Akreditasi;
use Agus\Tabler\Models\Akreditasi_program_studi;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
    }

    /**
     * registerMarkupTags used by the frontend.
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'getInformasiPublikFiles' => function() {
                    // Ambil data dari database
                    $records = \Agus\Tabler\Models\Informasi_publik::all();

                    // Ambil file dari Google Drive
                    $category = 'Keterbukaan Informasi Publik';
                    $categoryKey = \Agus\Tabler\Classes\GoogleDriveUploader::normalizeCategoryName($category);
                    $folderId = \Agus\Tabler\Classes\GoogleDriveReader::FOLDER_IDS[$categoryKey] ?? null;

                    if (!$folderId) {
                        // \Log::error('Folder ID not found for Informasi Publik', ['categoryKey' => $categoryKey]);
                        return [];
                    }

                    $driveFiles = \Agus\Tabler\Classes\GoogleDriveReader::getFilesFromFolder($folderId);

                    // Map file Google Drive berdasarkan nama file untuk lookup cepat
                    $driveFilesMap = [];
                    foreach ($driveFiles as $driveFile) {
                        $driveFilesMap[$driveFile['name']] = $driveFile;
                    }

                    // Gabungkan data database dengan Google Drive
                    $result = [];
                    foreach ($records as $record) {
                        if ($record->file_name && isset($driveFilesMap[$record->file_name])) {
                            $driveFile = $driveFilesMap[$record->file_name];
                            $result[] = [
                                'title' => $record->title,
                                'sub_title' => $record->sub_title,
                                'file_name' => $record->file_name,
                                'file_id' => $driveFile['id'],
                                'file_url' => $driveFile['url'],
                                'created_at' => $record->created_at,
                            ];
                        }
                    }

                    return $result;
                },
                'getAgendas' => function($limit = 5) {
                    try {
                        $agendas = \Agus\Tabler\Models\Agenda::orderBy('created_at', 'desc')
                            ->limit($limit)
                            ->get();
                        if ($agendas->count() > 0) {
                            return $agendas;
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error loading agendas: ' . $e->getMessage());
                    }
                    // return collect([
                    //     (object)[
                    //         'title' => 'Workshop Akreditasi',
                    //         'description' => 'Workshop persiapan akreditasi program studi untuk meningkatkan kualitas pendidikan',
                    //         'created_at' => now()->addDays(7),
                    //         'is_active' => true
                    //     ],
                    //     (object)[
                    //         'title' => 'Audit Mutu Internal',
                    //         'description' => 'Pelaksanaan audit mutu internal semester genap tahun akademik 2025/2026',
                    //         'created_at' => now()->addDays(12),
                    //         'is_active' => true
                    //     ]
                    // ]);
                },
                'getPageHeader' => function() {
                    return \Agus\Tabler\Models\Page_header::first();
                },
                'getMediaLjm' => function() {
                    return \Agus\Tabler\Models\Media_ljm::all();
                },
                'getInformasiPublik' => function() {
                    return \Agus\Tabler\Models\Informasi_publik::all();
                },
                'getPageFooter' => function() {
                    return \Agus\Tabler\Models\Page_footer::first();
                },
                'getProfile' => function() {
                    return \Agus\Tabler\Models\Profile::first();
                },
                'getStrukturOrganisasi' => function() {
                    return \Agus\Tabler\Models\Struktur_organisasi::first();
                },
                'getAllMonevByCategory' => function() {
                    return \Agus\Tabler\Models\Monev::all()->groupBy('category_label');
                },
                'getMonevPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllMonevFiles();
                },
                'getAkreditasi' => function() {
                    return Akreditasi_program_studi::all();
                },
                'getAmiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllAmiFiles();
                },
                'getRtmPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllRtmFiles();
                },
                'getGrfKpsPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllGrfKpsFiles();
                },
                'getSvrKpsPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllSvrKpsFiles();
                },
                'getMnvSvrKpsPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllMnvSvrKpsFiles();
                },
                'getRtmKpsPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllRtmKpsFiles();
                },
                'getStandarSpmiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllStandarSpmiFiles();
                },
                'getFormulirSpmiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllFormulirSpmiFiles();
                },
                'getManualMutuPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllManualMutuFiles();
                },
                'getSopSpmiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllSopSpmiFiles();
                },
                'getKebijakanSpmiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllKebijakanSpmiFiles();
                },
                'getStandarLampauanPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllStandarLampauanFiles();
                },
                'getAkreditasiPerguruanTinggiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllAkreditasiPerguruanTinggiFiles();
                },
                'getAkredInterIsoPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllAkredInterIsoFiles();
                },
                'getUdinusPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllUdinusFiles();
                },
                'getHuachiewPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllHuachiewFiles();
                },
                'getTgbcPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllTgbcFiles();
                },
                'getInHouseTrainingIsoPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllInHouseTrainingIsoFiles();
                },
                'getWorkshopAunQaPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllWorkshopAunQaFiles();
                },
                'getWorkshopPelatihanAmiPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllWorkshopPelatihanAmiFiles();
                },
                'getWorkshopPeningkatanPenjaminMutuPdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllWorkshopPeningkatanPenjaminMutuFiles();
                },
                'getPemenangHibahSpmiTahun2021PdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllPemenangHibahSpmiTahun2021Files();
                },
                'get2019PdfFiles' => function() {
                    // Otomatis ambil file dari Google Drive
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAll2019Files();
                },
                'get2021PdfFiles' => function() {
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAll2021Files();
                },
                'get2023PdfFiles' => function() {
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAll2023Files();
                },
                'getIsoInternational2021PdfFiles' => function() {
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllIsoInternational2021Files();
                },
                'getIsoInternational2024PdfFiles' => function() {
                    return \Agus\Tabler\Classes\GoogleDriveReader::getAllIsoInternational2024Files();
                },
            ]
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return [
            'agus.tabler.access_all' => [
                'tab' => 'LJM Management',
                'label' => 'Access All LJM Management'
            ],
            'agus.tabler.manage_general' => [
                'tab' => 'LJM Management',
                'label' => 'Manage General (Page Header, Footer, Agenda, Media, Info Publik)'
            ],
            'agus.tabler.manage_about' => [
                'tab' => 'LJM Management',
                'label' => 'Manage Tentang Kami (Profile, Struktur Organisasi)'
            ],
            'agus.tabler.manage_spmi_docs' => [
                'tab' => 'LJM Management',
                'label' => 'Manage Dokumen Formal SPMI'
            ],
            'agus.tabler.monev' => [
                'tab' => 'LJM Management',
                'label' => 'Access Monitoring dan Evaluasi'
            ],
            'agus.tabler.ami' => [
                'tab' => 'LJM Management',
                'label' => 'Access Audit Mutu Internal'
            ],
            'agus.tabler.rtm' => [
                'tab' => 'LJM Management',
                'label' => 'Access Rapat Tinjauan Manajemen'
            ],
            'agus.tabler.grafikkepuasan' => [
                'tab' => 'LJM Management',
                'label' => 'Access Grafik Kepuasan'
            ],
            'agus.tabler.surveikepuasan' => [
                'tab' => 'LJM Management',
                'label' => 'Access Survei Kepuasan'
            ],
            'agus.tabler.monevsurveikepuasan' => [
                'tab' => 'LJM Management',
                'label' => 'Access Monev Survei Kepuasan'
            ],
            'agus.tabler.rtmkepuasan' => [
                'tab' => 'LJM Management',
                'label' => 'Access RTM Kepuasan'
            ],
            'agus.tabler.manage_accreditation' => [
                'tab' => 'LJM Management',
                'label' => 'Manage Akreditasi'
            ],
            'agus.tabler.manage_improvement' => [
                'tab' => 'LJM Management',
                'label' => 'Manage Peningkatan (Benchmarking, Workshop, Rekognisi, Auditor)'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'main-menu' => [
                'label' => 'LJM Management',
                'icon' => 'icon-university',
                'url' => Backend::url('agus/tabler/pageheader'),
                'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_general'],
                'order' => 100,
                'sideMenu' => [
                    'menu-page-header' => [
                        'label' => 'Page Header',
                        'icon' => 'icon-header',
                        'url' => Backend::url('agus/tabler/pageheader'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_general'],
                    ],
                    'menu-page-footer' => [
                        'label' => 'Page Footer',
                        'icon' => 'icon-minus-square',
                        'url' => Backend::url('agus/tabler/pagefooter'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_general'],
                    ],
                    'menu-agenda' => [
                        'label' => 'Agenda',
                        'icon' => 'icon-calendar',
                        'url' => Backend::url('agus/tabler/agenda'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_general'],
                    ],
                    'menu-media-ljm' => [
                        'label' => 'Media LJM',
                        'icon' => 'icon-film',
                        'url' => Backend::url('agus/tabler/medialjm'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_general'],
                    ],
                    'menu-informasi-publik' => [
                        'label' => 'Informasi Publik',
                        'icon' => 'icon-bullhorn',
                        'url' => Backend::url('agus/tabler/informasipublik'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_general'],
                    ],
                ]
            ],
            'main-menu-item' => [
                'label' => 'Tentang Kami',
                'icon' => 'icon-building',
                'url' => Backend::url('agus/tabler/profile'),
                'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_about'],
                'order' => 200,
                'sideMenu' => [
                    'menu-struktur-organisasi' => [
                        'label' => 'Struktur Organisasi',
                        'icon' => 'icon-sitemap',
                        'url' => Backend::url('agus/tabler/strukturorganisasi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_about'],
                    ],
                ]
            ],
            'main-menu-dokumen-formal-spmi' => [
                'label' => 'Dokumen Formal SPMI',
                'icon' => 'icon-folder-open',
                'url' => Backend::url('agus/tabler/kebijakan_spmi'),
                'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                'order' => 200,
                'sideMenu' => [
                    'menu-struktur-kebijakan-spmi' => [
                        'label' => 'Kebijakan SPMI',
                        'icon' => 'icon-balance-scale',
                        'url' => Backend::url('agus/tabler/kebijakan_spmi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                    ],
                    'menu-struktur-standar-spmi' => [
                        'label' => 'Standar SPMI',
                        'icon' => 'icon-check-square',
                        'url' => Backend::url('agus/tabler/standar_spmi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                    ],
                    'menu-struktur-formulir-spmi' => [
                        'label' => 'Formulir SPMI',
                        'icon' => 'icon-file-text',
                        'url' => Backend::url('agus/tabler/formulir_spmi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                    ],
                    'menu-struktur-manual-mutu' => [
                        'label' => 'Manual Mutu',
                        'icon' => 'icon-book',
                        'url' => Backend::url('agus/tabler/manual_mutu'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                    ],
                    'menu-struktur-sop-spmi' => [
                        'label' => 'SOP SPMI',
                        'icon' => 'icon-list-ol',
                        'url' => Backend::url('agus/tabler/sop_spmi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                    ],
                    'menu-struktur-standar-lampauan' => [
                        'label' => 'Standar Lampauan',
                        'icon' => 'icon-star',
                        'url' => Backend::url('agus/tabler/standar_lampauan'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_spmi_docs'],
                    ],
                ]
            ],
            'menu-implementasi-spmi' => [
                'label' => 'Implementasi SPMI',
                'icon' => 'icon-tasks',
                'url' => Backend::url('agus/tabler/monev'),
                'permissions' => ['agus.tabler.access_all', 'agus.tabler.monev', 'agus.tabler.ami', 'agus.tabler.rtm', 'agus.tabler.grafikkepuasan', 'agus.tabler.surveikepuasan', 'agus.tabler.monevsurveikepuasan', 'agus.tabler.rtmkepuasan'],
                'order' => 500,
                'sideMenu' => [
                    'menu-struktur-monev' => [
                        'label' => 'Monitoring dan Evaluasi',
                        'icon' => 'icon-bar-chart',
                        'url' => Backend::url('agus/tabler/monev'),
                        'permissions' => ['agus.tabler.monev'],
                    ],
                    'menu-struktur-ami' => [
                        'label' => 'Audit Mutu Internal',
                        'icon' => 'icon-search-plus',
                        'url' => Backend::url('agus/tabler/ami'),
                        'permissions' => ['agus.tabler.ami'],
                    ],
                     'menu-struktur-rtm' => [
                        'label' => 'Rapat Tinjauan Manajemen',
                        'icon' => 'icon-comments',
                        'url' => Backend::url('agus/tabler/rtm'),
                        'permissions' => ['agus.tabler.rtm'],
                    ],
                      'menu-struktur-grafik-kepuasan' => [
                        'label' => 'Grafik Kepuasan',
                        'icon' => 'icon-area-chart',
                        'url' => Backend::url('agus/tabler/grafikkepuasan'),
                        'permissions' => ['agus.tabler.grafikkepuasan'],
                    ],
                    'menu-struktur-survei-kepuasan' => [
                        'label' => 'Survei Kepuasan',
                        'icon' => 'icon-check-square-o',
                        'url' => Backend::url('agus/tabler/surveikepuasan'),
                        'permissions' => ['agus.tabler.surveikepuasan'],
                    ],
                    'menu-struktur-monev-survei-kepuasan' => [
                        'label' => 'Monev Survei Kepuasan',
                        'icon' => 'icon-pie-chart',
                        'url' => Backend::url('agus/tabler/monevsurveikepuasan'),
                        'permissions' => ['agus.tabler.monevsurveikepuasan'],
                    ],
                    'menu-struktur-rtm-kepuasan' => [
                        'label' => 'RTM Kepuasan',
                        'icon' => 'icon-users',
                        'url' => Backend::url('agus/tabler/rtmkepuasan'),
                        'permissions' => ['agus.tabler.rtmkepuasan'],
                    ],
                ]
            ],
            'main-menu-akreditasi' => [
                'label' => 'Akreditasi',
                'icon' => 'icon-shield',
                'url' => Backend::url('agus/tabler/akreditasiprogramstudi'),
                'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_accreditation'],
                'order' => 600,
                'sideMenu' => [
                    'menu-struktur-akreditasi-program-studi' => [
                        'label' => 'Akreditasi Program Studi',
                        'icon' => 'icon-graduation-cap',
                        'url' => Backend::url('agus/tabler/akreditasiprogramstudi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_accreditation'],
                    ],
                        'menu-struktur-akred-perguruan-tinggi' => [
                            'label' => 'Akreditasi Perguruan Tinggi',
                            'icon' => 'icon-institution',
                            'url' => Backend::url('agus/tabler/akred_perguruan_tinggi'),
                            'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_accreditation'],
                        ],
                        'menu-struktur-akred-inter-iso' => [
                            'label' => 'Akreditasi Internal ISO',
                            'icon' => 'icon-check-circle',
                            'url' => Backend::url('agus/tabler/akred_inter_iso'),
                            'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_accreditation'],
                        ],
                ]
            ],
            'main-menu-peningkatan' => [
                'label' => 'Peningkatan',
                'icon' => 'icon-line-chart',
                'url' => Backend::url('agus/tabler/peningkatan'),
                'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_improvement'],
                'order' => 600,
                'sideMenu' => [
                    'menu-struktur-benchmarking' => [
                        'label' => 'Benchmarking',
                        'icon' => 'icon-exchange',
                        'url' => Backend::url('agus/tabler/benchmarking'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_improvement'],
                    ],
                    'menu-struktur-workshop' => [
                        'label' => 'Workshop',
                        'icon' => 'icon-wrench',
                        'url' => Backend::url('agus/tabler/workshop'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_improvement'],
                    ],
                    'menu-struktur-rekognisi' => [
                        'label' => 'Rekognisi',
                        'icon' => 'icon-trophy',
                        'url' => Backend::url('agus/tabler/rekognisi'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_improvement'],
                    ],
                     'menu-struktur-auditor' => [
                        'label' => 'Auditor',
                        'icon' => 'icon-user-secret',
                        'url' => Backend::url('agus/tabler/auditor'),
                        'permissions' => ['agus.tabler.access_all', 'agus.tabler.manage_improvement'],
                    ],
                ]
            ],
        ];
    }
}
