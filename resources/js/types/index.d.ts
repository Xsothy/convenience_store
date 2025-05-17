import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface FlashMessages {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    categoriesNoProducts: Omit<App.Data.CategoryData, 'products'>[];
    carts: App.Data.CartData[];
    flash?: FlashMessages;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface SubscribePlanData {
    id: number;
    name: string;
    price: number;
    annual_price: number;
    currency: string;
    is_active: boolean;
    is_popular: boolean;
    description: string | null;
    features_list: string[] | null;
    created_at: string | null;
    updated_at: string | null;
    deleted_at: string | null;
}

export interface SubscribeData {
    id: number;
    user_id: number;
    period: string;
    start_at: string | null;
    end_at: string | null;
    status: string;
    plan: SubscribePlanData | null;
    created_at: string | null;
    updated_at: string | null;
}

type PaginatedCollection<T extends object> = {
    data: Array<T>;
    meta: {
        current_page: number;
        first_page_url: string | null;
        from: number;
        last_page: number;
        last_page_url: string | null;
        next_page_url: string | null;
        path: string;
        per_page: string;
        prev_page_url: string | null;
        to: number;
        total: number;
    };
};