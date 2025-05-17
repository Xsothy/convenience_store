import React from 'react';
import ProductCard from '@/components/ProductCard';
import CategorySlider from '@/components/CategorySlider';
import { Heart, Palette, Percent, Printer, RefreshCcw, Shield, ShoppingCart, Truck } from 'lucide-react';
import ServiceCard from '@/components/ServiceCard';
import TestimonialSlider from '@/components/TestimonialSlider';
import FeaturedProduct from '@/components/sections/Home/FeaturedProduct';
import Hero from '@/components/sections/Home/Hero';
import { ArtworkLayout, AppLayout } from '@/layouts';

interface HeroSlide {
    title: string;
    subtitle: string;
    button_text: string;
    button_link: string;
    image: string;
}

interface Service {
    title: string;
    description: string;
    icon: string;
}

interface Testimonial {
    quote: string;
    author: string;
    role: string;
    rating: number;
    image?: string;
}

interface ContentSettings {
    hero_slides: HeroSlide[];
    categories_title: string;
    categories_subtitle: string;
    services_title: string;
    services_subtitle: string;
    services: Service[];
    featured_products_title: string;
    featured_products_subtitle: string;
    testimonials_title: string;
    testimonials_subtitle: string;
    testimonials: Testimonial[];
    discounted_products_title: string;
    discounted_products_subtitle: string;
}

const HomePage = ({
    featuredProducts,
    categories,
    discountedProducts,
    contentSettings
}: {
    featuredProducts: App.Data.ProductData[];
    categories: App.Data.CategoryData[];
    discountedProducts: App.Data.ProductData[];
    contentSettings?: ContentSettings;
}) => {
    // Create a mapping of icon names to icon components
    const iconMap = {
        'Palette': Palette,
        'RefreshCcw': RefreshCcw,
        'Percent': Percent,
        'Printer': Printer,
        'Heart': Heart,
        'ShoppingCart': ShoppingCart,
        'Truck': Truck,
        'Shield': Shield
    };

    return (
        <>
            {/* Hero Section */}
            <Hero contentSettings={contentSettings} />

                 {/* Discounted Products */}
            {discountedProducts.length > 0 && (
                <section className="bg-white py-16 md:py-24">
                    <div className="container mx-auto px-4">
                        <div className="mb-12 text-center">
                            <h2 className="mb-4 text-3xl font-bold sm:text-4xl">
                                {contentSettings?.discounted_products_title || "Special Offers"}
                            </h2>
                            <p className="mx-auto max-w-2xl text-gray-600">
                                {contentSettings?.discounted_products_subtitle || "Limited time discounts on select products - don't miss out!"}
                            </p>
                        </div>

                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            {discountedProducts.map((product) => (
                                <ProductCard
                                    key={product.id}
                                    product={product}
                                />
                            ))}
                        </div>
                    </div>
                </section>
            )}


            {/* Categories Section */}
            <section className="bg-gray-100 py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold text-gray-700 sm:text-4xl">
                            {contentSettings?.categories_title || "Shop by Category"}
                        </h2>
                        <p className="mx-auto max-w-2xl text-gray-600">
                            {contentSettings?.categories_subtitle || "Browse our wide selection of products across various categories"}
                        </p>
                    </div>

                    <CategorySlider categories={categories} />
                </div>
            </section>

            <section className="bg-white py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold sm:text-4xl">
                            {contentSettings?.services_title || "Our Services"}
                        </h2>
                        <p className="mx-auto max-w-2xl text-gray-600">
                            {contentSettings?.services_subtitle || "We offer a range of services to enhance your shopping experience"}
                        </p>
                    </div>

                    <div className="grid grid-cols-1 gap-8 sm:grid-cols-1 lg:grid-cols-2">
                        {contentSettings?.services ? (
                            contentSettings.services.map((service, index) => (
                                <ServiceCard
                                    key={index}
                                    icon={iconMap[service.icon as keyof typeof iconMap] || Palette}
                                    title={service.title}
                                    description={service.description}
                                />
                            ))
                        ) : (
                            // Default services if not defined in contentSettings
                            <>
                                <ServiceCard
                                    icon={Palette}
                                    title="Creativity Art"
                                    description="Unique artistic designs created by our talented team of artists"
                                />
                                <ServiceCard
                                    icon={RefreshCcw}
                                    title="Money Return"
                                    description="100% money-back guarantee if you're not satisfied with your purchase"
                                />
                                <ServiceCard
                                    icon={Percent}
                                    title="Membership Discount"
                                    description="Exclusive discounts for our loyal members on all products"
                                />
                                <ServiceCard
                                    icon={Printer}
                                    title="Printing Service"
                                    description="Professional printing services for all your artistic needs"
                                />
                            </>
                        )}
                    </div>
                </div>
            </section>

            {/* Featured Product */}
            <FeaturedProduct products={featuredProducts} />

            <section className="bg-gray-100 py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold sm:text-4xl">
                            {contentSettings?.testimonials_title || "What Our Customers Say"}
                        </h2>
                        <p className="mx-auto max-w-2xl text-gray-600">
                            {contentSettings?.testimonials_subtitle || "Hear from our satisfied customers about their shopping experience"}
                        </p>
                    </div>

                    <TestimonialSlider testimonials={contentSettings?.testimonials} />
                </div>
            </section>
        </>
    );
};

HomePage.layout = page => (
    <AppLayout>
        <ArtworkLayout children={page} />
    </AppLayout>
)

export default HomePage; 