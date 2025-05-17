// Main auth layout that exports the default auth layout
import AuthSimpleLayout from './';

export default function AuthLayout({ children, title, description, ...props }: { 
    children: React.ReactNode; 
    title: string; 
    description: string;
    [key: string]: any;
}) {
    return (
        <AuthSimpleLayout title={title} description={description} {...props}>
            {children}
        </AuthSimpleLayout>
    );
} 