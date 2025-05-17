import React from 'react';
import ProductCard from '@/components/ProductCard';
import CategorySlider from '@/components/CategorySlider';
import { Palette, Percent, Printer, RefreshCcw } from 'lucide-react';
import ServiceCard from '@/components/ServiceCard';
import TestimonialSlider from '@/components/TestimonialSlider';
import FeaturedProduct from '@/components/sections/Home/FeaturedProduct';
import Hero from '@/components/sections/Home/Hero';
import {AppLayout, ArtworkLayout} from '@/layouts';

const Index = ({
                   featuredProducts,
                   categories,
                   discountedProducts
               }: {
    featuredProducts: App.Data.ProductData[];
    categories: App.Data.CategoryData[];
    discountedProducts: App.Data.ProductData[];
}) => {
    return (
        <>

            {/* Hero Section */}
            <Hero />

            {/* Categories Section */}
            <section className="bg-white py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold text-gray-700 sm:text-4xl">Shop by Category</h2>
                        <p className="mx-auto max-w-2xl text-gray-600">Browse our wide selection of products across various categories</p>
                    </div>

                    <CategorySlider categories={categories} />
                </div>
            </section>

            <section className="bg-gray-100 py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold sm:text-4xl">Our Services</h2>
                        <p className="mx-auto max-w-2xl text-gray-600">We offer a range of services to enhance your shopping experience</p>
                    </div>

                    <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
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
                    </div>
                </div>
            </section>

            {/* Featured Product */}
            <FeaturedProduct products={featuredProducts} />

            <section className="bg-gray-50 py-16 md:py-24">
                <div className="container mx-auto px-4">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold sm:text-4xl">What Our Customers Say</h2>
                        <p className="mx-auto max-w-2xl text-gray-600">Hear from our satisfied customers about their shopping experience</p>
                    </div>

                    <TestimonialSlider />
                </div>
            </section>

            {/* Discounted Products */}
            {discountedProducts.length > 0 && (
                <section className="bg-white py-16 md:py-24">
                    <div className="container mx-auto px-4">
                        <div className="mb-12 text-center">
                            <h2 className="mb-4 text-3xl font-bold sm:text-4xl">Special Offers</h2>
                            <p className="mx-auto max-w-2xl text-gray-600">Limited time discounts on select products - don't miss out!</p>
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

        </>
    );
};

Index.layout = page => (
    <AppLayout>
        <ArtworkLayout children={page} />
    </AppLayout>
);

export default Index;
