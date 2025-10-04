<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GeneralSetting;

class OauthConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        // No need to register anything here
    }

    public function boot()
    {
        // Fetch Google credentials
        try {
            $setting = GeneralSetting::first();

            if ($setting) {
                // Dynamically set the config values
                config([
                    'services.google.client_id' =>  $setting->google_client_id,
                    'services.google.client_secret' => $setting->google_client_secret,
                    'services.google.redirect' =>  $setting->google_callback,

                   'services.facebook.client_id' =>  $setting->facebook_client_id,
                    'services.facebook.client_secret' => $setting->facebook_client_secret,
                    'services.facebook.redirect' => $setting->facebook_callback,
                ]);
            }
        } catch (\Exception $e) {
            // Database not ready yet (migrations not run)
        }
    }
}
