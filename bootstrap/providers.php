<?php

return [
    // The App\Provider class will be automatically loaded by October CMS.
    //
    System\ServiceProvider::class,

    // Laravel Auth providers untuk fix Gate binding
    Illuminate\Auth\AuthServiceProvider::class,

    // Include any custom Service Providers in this array, for example.
    //
    // App\Providers\AppServiceProvider::class,
    //
];
