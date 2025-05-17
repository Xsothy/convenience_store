
import React from 'react';
import { LucideIcon } from 'lucide-react';

interface ServiceCardProps {
    icon: LucideIcon;
    title: string;
    description: string;
}

const ServiceCard: React.FC<ServiceCardProps> = ({ icon: Icon, title, description }) => {
    return (
        <div className="service-card group rounded-lg border border-gray-200 bg-card p-6 shadow-sm transition-all duration-300 hover:shadow-md">
            <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-black transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
                <Icon size={24} />
            </div>
            <h3 className="mb-2 text-lg font-medium">{title}</h3>
            <p className="text-sm text-gray-600 min-h-[100px]">{description}</p>
        </div>
    );
};

export default ServiceCard;
