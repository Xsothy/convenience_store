import React, { useEffect, useState } from 'react';
import { cn } from '@/lib/utils';
import { ArrowRight } from 'lucide-react';
import { Link } from '@inertiajs/react';

interface HeroSlide {
    image: string;
    title: string;
    subtitle?: string;
    button_text?: string;
    button_link?: string;
}

interface HeroProps {
    contentSettings?: {
        hero_slides: HeroSlide[];
    };
}

export default function Hero({ contentSettings }: HeroProps) {
    const slides: HeroSlide[] = contentSettings?.hero_slides || [
        {
            image: "/images/hero-1.jpg",
            title: "Quality Artwork for Your Home",
            subtitle: "Discover our carefully curated collection of premium art designed to enhance your living space.",
            button_text: "Explore Collection",
            button_link: "/collections",
        },
        {
            image: "/images/hero-2.jpg",
            title: "Handcrafted Masterpieces",
            subtitle: "Each piece tells a unique story, crafted with attention to detail and artistic excellence.",
            button_text: "View Gallery",
            button_link: "/gallery",
        },
        {
            image: "/images/hero-3.jpg",
            title: "Personalized Art Experience",
            subtitle: "Find artwork that speaks to your style and transforms your space into something extraordinary.",
            button_text: "Get Started",
            button_link: "/custom",
        }
    ];

    const [selectedIndex, setSelectedIndex] = useState(0);
    const [isAnimating, setIsAnimating] = useState(false);
    const [direction, setDirection] = useState<'next' | 'prev'>('next');

    useEffect(() => {
        const interval = setInterval(() => {
            handleSlideChange('next');
        }, 5000);

        return () => clearInterval(interval);
    }, [selectedIndex]);

    const handleSlideChange = (dir: 'next' | 'prev') => {
        if (isAnimating) return;
        
        setIsAnimating(true);
        setDirection(dir);
        
        setTimeout(() => {
            if (dir === 'next') {
                setSelectedIndex(prev => (prev + 1) % slides.length);
            } else {
                setSelectedIndex(prev => (prev - 1 + slides.length) % slides.length);
            }
            setIsAnimating(false);
        }, 500); // Match this with the CSS transition duration
    };

    const currentSlide = slides[selectedIndex];

    return (
        <section className="relative overflow-hidden bg-gray-100">
            <div className="grid grid-cols-1 md:grid-cols-8 h-full min-h-[600px] md:min-h-[700px] ">
                   {/* Right side - Image */}
                   <div className="relative h-[300px] md:h-full w-full md:order-last md:col-span-5 md:hidden">
                    {slides.map((slide, index) => (
                        <div
                            key={index}
                            className={cn(
                                "absolute inset-0 bg-cover bg-center transition-all duration-500 transform",
                                index === selectedIndex ? 
                                    "opacity-100 z-10" : 
                                    "opacity-0 z-0",
                                isAnimating && index === selectedIndex ? 
                                    (direction === 'next' ? "translate-x-full" : "-translate-x-full") : 
                                    "translate-x-0"
                            )}
                            style={{ backgroundImage: `url('storage/${slide.image}')` }}
                        />
                    ))}
                    
                    {/* Slide indicators */}
                    <div className="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10">
                        <div className="rounded-full bg-white flex gap-2 px-4 py-2">
                            {slides.map((_, index) => (
                                <button
                                    key={index}
                                    onClick={() => {
                                        setDirection(index > selectedIndex ? 'next' : 'prev');
                                        setSelectedIndex(index);
                                    }}
                                    className={cn(
                                        "h-2.5 w-2.5 rounded-full transition-all duration-300",
                                        index === selectedIndex ? "bg-primary w-6" : "bg-gray-300 hover:bg-gray-400"
                                    )}
                                    aria-label={`Go to slide ${index + 1}`}
                                    aria-current={index === selectedIndex ? "true" : "false"}
                                />
                            ))}
                        </div>
                    </div>
                </div>
                
                {/* Left side - Text Content */}
                <div className="flex flex-col justify-center p-8 md:p-16 z-10 col-span-4 lg:col-span-3 text-center gap-4">
                    <div 
                        className={cn(
                            "transition-all duration-500 transform",    
                            isAnimating ? 
                                (direction === 'next' ? "-translate-x-full opacity-0" : "translate-x-full opacity-0") : 
                                "translate-x-0 opacity-100"
                        )}
                    >
                        <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 text-gray-900">
                            {currentSlide.title}
                        </h1>
                        <p className="text-lg text-gray-700 mb-8 max-w-md mx-auto">
                            {currentSlide.subtitle}
                        </p>
                        <Link 
                            href={currentSlide.button_link || '#'} 
                            className="inline-flex items-center gap-2 bg-black text-white py-3 px-6 rounded-md hover:bg-gray-800 transition-colors"
                        >
                            {currentSlide.button_text || 'Learn More'}
                            <ArrowRight size={18} />
                        </Link>
                    </div>

                </div>

                {/* Right side - Image */}
                <div className="relative h-[300px] md:h-full w-full md:order-last md:col-span-4 lg:col-span-5 hidden md:block">
                    {slides.map((slide, index) => (
                        <div
                            key={index}
                            className={cn(
                                "absolute inset-0 bg-cover bg-center transition-all duration-500 transform",
                                index === selectedIndex ? 
                                    "opacity-100 z-10" : 
                                    "opacity-0 z-0",
                                isAnimating && index === selectedIndex ? 
                                    (direction === 'next' ? "translate-x-full" : "-translate-x-full") : 
                                    "translate-x-0"
                            )}
                            style={{ backgroundImage: `url('storage/${slide.image}')` }}
                        />
                    ))}
                    
                    {/* Slide indicators */}
                    <div className="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10">
                        <div className="rounded-full bg-white flex gap-2 px-4 py-2">
                            {slides.map((_, index) => (
                                <button
                                    key={index}
                                    onClick={() => {
                                        setDirection(index > selectedIndex ? 'next' : 'prev');
                                        setSelectedIndex(index);
                                    }}
                                    className={cn(
                                        "h-2.5 w-2.5 rounded-full transition-all duration-300",
                                        index === selectedIndex ? "bg-primary w-6" : "bg-gray-300 hover:bg-gray-400"
                                    )}
                                    aria-label={`Go to slide ${index + 1}`}
                                    aria-current={index === selectedIndex ? "true" : "false"}
                                />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
