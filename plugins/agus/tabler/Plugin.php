<?php namespace Agus\Tabler;

use System\Classes\PluginBase;

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
                'getAgendas' => function($limit = 5) {
                    try {
                        // Debug: cek apakah model bisa diakses
                        $agendas = \Agus\Tabler\Models\Agenda::orderBy('created_at', 'desc')
                                                             ->limit($limit)
                                                             ->get();

                        // Jika ada data, return data real
                        if ($agendas->count() > 0) {
                            return $agendas;
                        }
                    } catch (\Exception $e) {
                        // Log error untuk debugging
                        \Log::error('Error loading agendas: ' . $e->getMessage());
                    }

                    // Fallback jika tidak ada data atau error
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
            ]
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
}
