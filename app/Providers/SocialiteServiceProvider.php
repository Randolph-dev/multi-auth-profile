<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Steam\SteamExtendSocialite;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->evetns->listen(
            SocialiteWasCalled::class,
            [DiscordExtendSocialite::class, 'handle']
        );

        $this->app->events->listen(
            SocialiteWasCalled::class,
            [SteamExtendSocialite::class, 'handle']
        );
    }
}
