import React, { useState, useEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { ShoppingCart, User, Heart, Search, ShoppingBag } from 'lucide-react';
import {
  NavigationMenu,
  NavigationMenuList,
  NavigationMenuItem,
  NavigationMenuLink
} from "@/components/ui/navigation-menu";
import ArtworkController from '@/wayfinder/actions/App/Http/Controllers/ArtworkController';
import { SharedData } from '@/types';
import UserDropdown from '@/components/layout/UserDropdown';

export default function MintedHeader() {
  const [searchQuery, setSearchQuery] = useState('');
  const [isScrolled, setIsScrolled] = useState(false);
  const {url} = usePage();
  const totalItems = usePage<SharedData>().props.carts.reduce((acc, cart) => acc + (cart.qty ?? 0), 0);

  const user = usePage().props.user;

  const handleLogout = () => {
      router.post(route('logout'))
  }

  const handleLogin = () => {
      router.visit('/login')
  }

  // Handle scrolling effect
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 10);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      router.visit(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
    }
  };

  return (
    <header className={`bg-white sticky top-0 z-50 ${isScrolled ? 'shadow-sm' : ''}`}>
      {/* Top navigation bar with user nav and main tabs */}
      <div className="container mx-auto px-4 border-b">
        <div className="flex items-center justify-center md:justify-between py-2">
          <div className="flex items-center space-x-6 text-primary text-2xl font-bold w-40 hidden md:block">
          </div>
          {/* Main category navigation */}
          {/*  <NavigationMenu>*/}
          {/*    <NavigationMenuList className="space-x-6">*/}
          {/*      <NavigationMenuItem>*/}
          {/*        <NavigationMenuLink */}
          {/*          href={ArtworkController.index()}*/}
          {/*          data-active={!url.startsWith('/wedding')}*/}
          {/*        >*/}
          {/*          ARTWORKS*/}
          {/*        </NavigationMenuLink>*/}
          {/*      </NavigationMenuItem>*/}
          {/*      <NavigationMenuItem>*/}
          {/*        <NavigationMenuLink */}
          {/*          href="/wedding" */}
          {/*          data-active={url.startsWith('/wedding')}*/}
          {/*        >*/}
          {/*          WEDDINGS*/}
          {/*        </NavigationMenuLink>*/}
          {/*      </NavigationMenuItem>*/}
          {/*    </NavigationMenuList>*/}
          {/*  </NavigationMenu>*/}

          {/* User Navigation - only visible on desktop */}
          <div className="items-center space-x-4 hidden md:flex">
            <form onSubmit={handleSearch} className="relative mr-2 hidden md:block">
              <input
                type="text"
                placeholder="Search..."
                className="rounded-full border border-gray-300 px-4 py-1 pr-8 text-sm w-40 focus:border-gray-400 focus:outline-none"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
              <button type="submit" className="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500" aria-label="Search">
                <Search size={14} />
              </button>
            </form>
            <Link href="/favorites" className="text-gray-800 hover:text-primary">
              <Heart className="h-5 w-5" />
            </Link>
            <Link
              href="/cart" 
              className="relative text-gray-700 transition-colors duration-300 hover:text-black"
            >
              <ShoppingBag className="cursor-pointer" size={20} />
              {totalItems > 0 && (
                <span className="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-black text-xs text-white">
                  {totalItems}
                </span>
              )}
            </Link>
            <UserDropdown />
          </div>
        </div>
      </div>
    </header>
  );
} 