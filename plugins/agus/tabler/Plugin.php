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
        return [
            \Agus\Tabler\Components\SurveyAccordion::class => 'surveyAccordion'
        ];
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
                    return collect([
                        (object)[
                            'title' => 'Workshop Akreditasi',
                            'description' => 'Workshop persiapan akreditasi program studi untuk meningkatkan kualitas pendidikan',
                            'created_at' => now()->addDays(7),
                            'is_active' => true
                        ],
                        (object)[
                            'title' => 'Audit Mutu Internal',
                            'description' => 'Pelaksanaan audit mutu internal semester genap tahun akademik 2025/2026',
                            'created_at' => now()->addDays(12),
                            'is_active' => true
                        ]
                    ]);
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
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'main-menu' => [
                'label' => 'LJM Management',
                'icon' => 'icon-home',
                'url' => Backend::url('agus/tabler/pageheader'),
                'permissions' => ['agus.tabler.*'],
                'order' => 100,
                'sideMenu' => [
                    'menu-page-header' => [
                        'label' => 'Page Header',
                        'icon' => 'icon-header',
                        'url' => Backend::url('agus/tabler/pageheader'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                    'menu-page-footer' => [
                        'label' => 'Page Footer',
                        'icon' => 'icon-footer',
                        'url' => Backend::url('agus/tabler/pagefooter'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                    'menu-agenda' => [
                        'label' => 'Agenda',
                        'icon' => 'icon-calendar',
                        'url' => Backend::url('agus/tabler/agenda'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                    'menu-media-ljm' => [
                        'label' => 'Media LJM',
                        'icon' => 'icon-picture-o',
                        'url' => Backend::url('agus/tabler/medialjm'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                    'menu-informasi-publik' => [
                        'label' => 'Informasi Publik',
                        'icon' => 'icon-info',
                        'url' => Backend::url('agus/tabler/informasipublik'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                ]
            ],
            'main-menu-item' => [
                'label' => 'Tentang Kami',
                'icon' => 'icon-info-circle',
                'url' => Backend::url('agus/tabler/profile'),
                'permissions' => ['agus.tabler.*'],
                'order' => 200,
                'sideMenu' => [
                    'menu-struktur-organisasi' => [
                        'label' => 'Struktur Organisasi',
                        'icon' => 'icon-sitemap',
                        'url' => Backend::url('agus/tabler/strukturorganisasi'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                ]
            ],
            'menu-implementasi-spmi' => [
                'label' => 'Implementasi SPMI',
                'icon' => 'icon-clipboard',
                'url' => Backend::url('agus/tabler/monev'),
                'permissions' => ['agus.tabler.*'],
                'order' => 500,
                'sideMenu' => [
                    'menu-beban-belajar-mhs' => [
                        'label' => 'Monitoring dan Evaluasi',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/monev'),
                        'permissions' => ['agus.tabler.monev'],
                    ],
                    'menu-ami' => [
                        'label' => 'Audit Mutu Internal',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/ami'),
                        'permissions' => ['agus.tabler.ami'],
                    ],
                     'menu-rtm' => [
                        'label' => 'Rapat Tinjauan Manajemen',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/rtm'),
                        'permissions' => ['agus.tabler.rtm'],
                    ],
                      'menu-grafik-kepuasan' => [
                        'label' => 'Grafik Kepuasan',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/grafikkepuasan'),
                        'permissions' => ['agus.tabler.grafikkepuasan'],
                    ],
                    'menu-survei-kepuasan' => [
                        'label' => 'Survei Kepuasan',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/surveikepuasan'),
                        'permissions' => ['agus.tabler.surveikepuasan'],
                    ],
                    'menu-monev-survei-kepuasan' => [
                        'label' => 'Monev Survei Kepuasan',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/monevsurveikepuasan'),
                        'permissions' => ['agus.tabler.monevsurveikepuasan'],
                    ],
                    'menu-rtm-kepuasan' => [
                        'label' => 'RTM Kepuasan',
                        'icon' => 'icon-file-pdf-o',
                        'url' => Backend::url('agus/tabler/rtmkepuasan'),
                        'permissions' => ['agus.tabler.rtmkepuasan'],
                    ],
                ]
            ],
            'menu-akreditasi' => [
                'label' => 'Akreditasi',
                'icon' => 'icon-certificate',
                'url' => Backend::url('agus/tabler/akreditasiprogramstudi'),
                'permissions' => ['agus.tabler.*'],
                'order' => 600,
                'sideMenu' => [
                    'menu-akreditasi-program-studi' => [
                        'label' => 'Akreditasi Program Studi',
                        'icon' => 'icon-graduation-cap',
                        'url' => Backend::url('agus/tabler/akreditasiprogramstudi'),
                        'permissions' => ['agus.tabler.*'],
                    ],
                ]
            ],
        ];
    }
}
