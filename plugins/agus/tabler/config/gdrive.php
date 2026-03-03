<?php

return [
    /*
     * Time-to-live for Google Drive API responses (in minutes).
     *
     * The value is used by \Agus\Tabler\Classes\GoogleDriveReader when it
     * caches the nested folder structure and individual folder listings.
     *
     * The default of 60 means the first visitor after the cache expires will
     * see a (slightly) slower request while we re-fetch from Google Apps
     * Script.  Increasing this number reduces the number of remote calls but
     * also delays the appearance of newly-uploaded documents.  You can set it
     * in your .env file using GDRIVE_CACHE_TTL.
     */
    'cache_ttl' => env('GDRIVE_CACHE_TTL', 60),
];
