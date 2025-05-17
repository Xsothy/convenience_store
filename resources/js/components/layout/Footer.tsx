import React from 'react';
import { Facebook, Instagram, Twitter } from 'lucide-react';
import { Link, usePage } from '@inertiajs/react';
import { SharedData } from '@/types';

const Footer = () => {
  const categories = usePage<SharedData>().props.categoriesNoProducts;
  return (
    <footer className="border-t border-gray-200 bg-white pt-8 sm:pt-12">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 gap-8 pb-8 sm:gap-10 sm:pb-10 md:grid-cols-2 lg:grid-cols-3">
          {/* Column 1 - About */}
          <div className="space-y-3 sm:space-y-4">
            <h3 className="text-lg font-medium sm:text-xl">GoodSamaritanArt</h3>
            <p className="text-sm text-gray-600 sm:text-base">
              Discover unique artwork from independent artists around the world.
            </p>
            <div className="flex space-x-4 pt-2">
              <a
                href="https://instagram.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-400 transition-colors duration-300 hover:text-black"
                aria-label="Instagram"
              >
                <Instagram size={20} />
              </a>
              <a
                href="https://twitter.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-400 transition-colors duration-300 hover:text-black"
                aria-label="Twitter"
              >
                <Twitter size={20} />
              </a>
              <a
                href="https://facebook.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-400 transition-colors duration-300 hover:text-black"
                aria-label="Facebook"
              >
                <Facebook size={20} />
              </a>
            </div>
          </div>

          {/* Column 2 - Categories */}
          <div className="space-y-3 sm:space-y-4">
            <h3 className="text-lg font-medium sm:text-xl">Categories</h3>
            <ul className="grid grid-cols-2 gap-x-2 gap-y-2 sm:gap-y-2 md:grid-cols-1">
              {categories.slice(0, 6).map((category) => (
                <li key={category.slug}>
                  <Link href={`/collections/${category.slug}`} className="text-sm text-gray-600 transition-colors duration-300 hover:text-black sm:text-base">
                    {category.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Column 3 - Help */}
          <div className="space-y-3 sm:space-y-4">
            <h3 className="text-lg font-medium sm:text-xl">Help</h3>
            <ul className="grid grid-cols-2 gap-x-2 gap-y-2 sm:gap-y-2 md:grid-cols-1">
              <li><Link href="/faq" className="text-sm text-gray-600 transition-colors duration-300 hover:text-black sm:text-base">FAQ</Link></li>
              <li><Link href="/contact" className="text-sm text-gray-600 transition-colors duration-300 hover:text-black sm:text-base">Contact Us</Link></li>
              <li><Link href="/privacy" className="text-sm text-gray-600 transition-colors duration-300 hover:text-black sm:text-base">Privacy Policy</Link></li>
              <li><Link href="/terms" className="text-sm text-gray-600 transition-colors duration-300 hover:text-black sm:text-base">Terms of Service</Link></li>
            </ul>
          </div>
        </div>

        {/* Bottom Footer */}
        <div className="border-t border-gray-200 py-4 sm:py-6">
          <div className="flex flex-col justify-between space-y-3 text-center sm:space-y-4 md:flex-row md:space-y-0 md:text-left">
            <p className="text-xs text-gray-500 sm:text-sm">
              © {new Date().getFullYear()} Artify. All rights reserved.
            </p>
            <div className="flex justify-center space-x-4 md:justify-end md:space-x-6">
              <Link href="/privacy" className="text-xs text-gray-500 transition-colors duration-300 hover:text-black sm:text-sm">Privacy</Link>
              <Link href="/terms" className="text-xs text-gray-500 transition-colors duration-300 hover:text-black sm:text-sm">Terms</Link>
              <Link href="/sitemap" className="text-xs text-gray-500 transition-colors duration-300 hover:text-black sm:text-sm">Sitemap</Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
