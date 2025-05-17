import React, { useEffect, useState } from 'react';
import { Search } from 'lucide-react';
import CartButton from '../CartButton';
import { NavigationMenu, NavigationMenuItem, NavigationMenuList } from '@/components/ui/navigation-menu';
import { Link, router, usePage } from '@inertiajs/react';
import type { SharedData } from '@/types';
import ThemeController from '@/wayfinder/actions/App/Http/Controllers/ThemeController';

const Navbar = () => {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');
    const categories = usePage<SharedData>().props.categoriesNoProducts;

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 10);
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    useEffect(() => {
        setIsMobileMenuOpen(false);
    }, [location]);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (searchQuery.trim()) {
            router.visit(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
        }
    };

    // Split categories into main and others
  return (
      <header
          className={`fixed top-0 left-0 z-50 w-full transition-all duration-300 ${
              isScrolled ? 'glass-background border-b border-gray-200 py-3 shadow-sm' : 'py-5'
          }`}
      >
          <div className="container mx-auto flex items-center justify-between px-4">
              {/* Logo */}
              <Link
                  href="/"
                  className={`text-2xl font-bold tracking-tight transition-opacity duration-300 hover:opacity-80 ${
                      isScrolled ? 'text-gray-700' : ''
                  }`}
                  prefetch="mount"
              >
                  GoodSamaritanArt
              </Link>

              {/* Desktop Navigation */}
              <nav className="hidden md:flex">
                  <NavigationMenu>
                      <NavigationMenuList className="space-x-6">

                          <NavigationMenuItem>
                              <Link
                                  href={route('home')}
                                  className={`relative px-2 py-1 font-medium text-gray-700 transition-colors before:absolute before:bottom-0 before:left-0 before:h-[2px] before:w-0 before:bg-black before:transition-all before:duration-300 hover:text-black hover:before:w-full`}
                              >
                                  Home
                              </Link>
                          </NavigationMenuItem>

                          {categories.filter(category => category.show_in_menu).map((category) => (
                              <NavigationMenuItem key={category.slug}>
                                  <Link
                                      href={ThemeController.show({slug: category.slug})}
                                      className={`relative px-2 py-1 font-medium text-gray-700 transition-colors before:absolute before:bottom-0 before:left-0 before:h-[2px] before:w-0 before:bg-black before:transition-all before:duration-300 hover:text-black hover:before:w-full whitespace-nowrap`}
                                  >
                                      {category.name}
                                  </Link>
                              </NavigationMenuItem>
                          ))}
                      </NavigationMenuList>
                  </NavigationMenu>
              </nav>

              {/* Search and Cart */}
              <div className="hidden items-center space-x-5 md:flex">
                  <form onSubmit={handleSearch} className="relative">
                      <input
                          type="text"
                          placeholder="Search products..."
                          className={
                              'rounded-full border border-gray-300 px-4 py-2 pr-10 text-sm focus:border-gray-400 focus:outline-none' +
                              (!isScrolled
                                  ? 'border-gray-200 text-gray-700 placeholder-gray-700'
                                  : 'border-gray-300 text-gray-500 placeholder-gray-500')
                          }
                          value={searchQuery}
                          onChange={(e) => setSearchQuery(e.target.value)}
                      />
                      <button type="submit" className="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500" aria-label="Search">
                          <Search size={16} />
                      </button>
                  </form>
                  <CartButton />
              </div>

              {/* Mobile Menu Button */}
              <button
                  className="flex items-center space-x-2 md:hidden"
                  onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                  aria-label={isMobileMenuOpen ? 'Close menu' : 'Open menu'}
              >
                  <div className="space-y-1.5">
                      <span
                          className={`block h-0.5 w-6 bg-black transition-all duration-300 ${isMobileMenuOpen ? 'translate-y-2 rotate-45' : ''}`}
                      ></span>
                      <span className={`block h-0.5 w-6 bg-black transition-all duration-300 ${isMobileMenuOpen ? 'opacity-0' : ''}`}></span>
                      <span
                          className={`block h-0.5 w-6 bg-black transition-all duration-300 ${isMobileMenuOpen ? '-translate-y-2 -rotate-45' : ''}`}
                      ></span>
                  </div>
              </button>
          </div>

          {/* Mobile Menu */}
          <div
              className={`glass-background absolute right-0 left-0 border-b border-gray-200 shadow-sm transition-all duration-300 ease-in-out md:hidden ${
                  isMobileMenuOpen ? 'opacity-100 overflow-y-auto' : 'max-h-0 overflow-hidden opacity-0'
              }`}
          >
              <nav className="container mx-auto flex flex-col space-y-4 px-4 py-6">
                  <Link href="/" className="py-2 text-lg">
                      Home
                  </Link>
                  {categories
                      .filter((category) => category.show_in_menu)
                      .map((category) => (
                      <Link key={category.slug} href={ThemeController.show({slug: category.slug})} className="py-2 text-lg" prefetch>
                          {category.name}
                      </Link>
                  ))}
                  {/*<Link href={route('products.checkout.process')} className="py-2 text-lg">*/}
                  {/*    Cart*/}
                  {/*</Link>*/}
                  <form onSubmit={handleSearch} className="flex items-center space-x-2 py-2">
                      <input
                          type="text"
                          placeholder="Search products..."
                          className={'w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-gray-400 focus:outline-none'}
                          value={searchQuery}
                          onChange={(e) => setSearchQuery(e.target.value)}
                      />
                      <button type="submit" className="rounded-full bg-black p-2 text-white" aria-label="Search">
                          <Search size={16} />
                      </button>
                  </form>
              </nav>
          </div>
      </header>
  );
};

export default Navbar;
