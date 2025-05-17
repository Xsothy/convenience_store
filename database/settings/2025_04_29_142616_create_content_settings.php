<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Hero Slides
        $this->migrator->add('content.hero_slides', [
            [
                'title' => 'Beautiful Artwork for Your Home',
                'subtitle' => 'Discover unique pieces that will transform your space',
                'button_text' => 'Shop Now',
                'button_link' => 'artworks',
                'image' => public_path('images/hero-1.jpg'),
            ],
            [
                'title' => 'Handcrafted Masterpieces',
                'subtitle' => 'Each piece tells a unique story, crafted with attention to detail',
                'button_text' => 'Shop Now',
                'button_link' => '/artworks',
                'image' => public_path('images/hero-2.jpg'),
            ],
            [
                'title' => 'Personalized Art Experience',
                'subtitle' => 'Find artwork that speaks to your style and transforms your space',
                'button_text' => 'Get Started',
                'button_link' => '/artworks',
                'image' => public_path('images/hero-3.jpg'),
            ],
        ]);

        $this->migrator->add('content.categories_title', 'Shop by Category');
        $this->migrator->add('content.categories_subtitle', 'Browse our wide selection of products across various categories');

        $this->migrator->add('content.services_title', 'Our Services');
        $this->migrator->add('content.services_subtitle', 'We offer a range of services to enhance your shopping experience');

        // Services
        $this->migrator->add('content.services', [
            [
                'title' => 'Creativity Art',
                'description' => 'Unique artistic designs created by our talented team of artists',
                'icon' => 'Palette',
            ],
            [
                'title' => 'Money Return',
                'description' => '100% money-back guarantee if you\'re not satisfied with your purchase',
                'icon' => 'RefreshCcw',
            ],
            [
                'title' => 'Membership Discount',
                'description' => 'Exclusive discounts for our loyal members on all products',
                'icon' => 'Percent',
            ],
            [
                'title' => 'Printing Service',
                'description' => 'Professional printing services for all your artistic needs',
                'icon' => 'Printer',
            ],
        ]);

        $this->migrator->add('content.featured_products_title', 'Featured Products');
        $this->migrator->add('content.featured_products_subtitle', 'Explore our handpicked selection of outstanding artwork');

        $this->migrator->add('content.testimonials_title', 'What Our Customers Say');
        $this->migrator->add('content.testimonials_subtitle', 'Hear from our satisfied customers about their shopping experience');

        // Testimonials
        $this->migrator->add('content.testimonials', [
            [
                'quote' => 'The quality of products I received exceeded my expectations. The customer service was also outstanding.',
                'author' => 'Jessica Miller',
                'role' => 'Returning Customer',
                'rating' => 5,
                'image' => '/placeholder.svg?height=80&width=80',
            ],
            [
                'quote' => 'I was skeptical about ordering online, but the products arrived exactly as described and ahead of schedule.',
                'author' => 'Michael Chen',
                'role' => 'First-time Buyer',
                'rating' => 5,
                'image' => '/placeholder.svg?height=80&width=80',
            ],
            [
                'quote' => 'Great selection of quality products at reasonable prices. The shipping was fast and the packaging was excellent.',
                'author' => 'Sarah Johnson',
                'role' => 'Loyal Customer',
                'rating' => 4,
                'image' => '/placeholder.svg?height=80&width=80',
            ],
        ]);

        $this->migrator->add('content.discounted_products_title', 'Special Offers');
        $this->migrator->add('content.discounted_products_subtitle', 'Limited time discounts on select products - don\'t miss out!');
    }
};
