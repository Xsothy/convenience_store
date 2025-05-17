<?php

namespace App\Providers;

use App\Filament\Admin\Pages\ContentSettings;
use Illuminate\Support\ServiceProvider;
use Module\Cart\CartModule;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-ecommerce');
        // Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-ecommerce');

        app()->singleton(CartModule::class, function () {
            return new CartModule;
        });

        FilamentSettingsHub::register([
            SettingHold::make()
                ->order(3)
                ->label('Content Settings') // to translate label just use direct translation path like `messages.text.name`
                ->icon('heroicon-o-globe-alt')
                ->page(ContentSettings::class) // use page / route
                ->description('Manage your content here') // to translate label just use direct translation path like `messages.text.name`
                ->group('filament-settings-hub::messages.group') // to translate label just use direct translation path like `messages.text.name`
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
