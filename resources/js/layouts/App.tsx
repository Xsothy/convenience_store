// Main app layout that exports the default app layout
import AppSidebarLayout from './app/';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: any[];
}

export default function AppLayout({ children, breadcrumbs, ...props }: AppLayoutProps) {
    return (
        <AppSidebarLayout breadcrumbs={breadcrumbs} {...props}>
            {children}
        </AppSidebarLayout>
    );
} 