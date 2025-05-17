<?php

namespace App\Settings;
use Spatie\LaravelSettings\Settings;

class ContentSettings extends Settings
{
    // Hero Section
    public array $hero_slides;
    public string $categories_title;
    public string $categories_subtitle;

    // Services Section
    public string $services_title;
    public string $services_subtitle;
    public array $services;
    
    // Featured Products Section
    public string $featured_products_title;
    public string $featured_products_subtitle;

    // Testimonials Section
    public string $testimonials_title;
    public string $testimonials_subtitle;
    public array $testimonials;

    // Discounted Products Section
    public string $discounted_products_title;
    public string $discounted_products_subtitle;

    public static function group(): string
    {
        return 'content';
    }
}
