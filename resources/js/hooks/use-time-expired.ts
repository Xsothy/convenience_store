import { useEffect, useState } from 'react';

export function getRemainingTime(date: null|string): { days: number; hours: number; minutes: number; seconds: number } {
    if(!date) return { days: 0, hours: 0, minutes: 0, seconds: 0 };
    const now = new Date();
    const targetDate = new Date(date);
    const timeDiff = targetDate.getTime() - now.getTime();

    if (timeDiff <= 0) {
        return { days: 0, hours: 0, minutes: 0, seconds: 0 };
    }

    const seconds = Math.floor((timeDiff / 1000) % 60);
    const minutes = Math.floor((timeDiff / (1000 * 60)) % 60);
    const hours = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);
    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

    return { days, hours, minutes, seconds };
}
export function useTimeExpired(date: string | null): [{ days: number; hours: number; minutes: number; seconds: number }, boolean] {
    const [timeRemaining, setTimeRemaining] = useState({ days: 0, hours: 0, minutes: 0, seconds: 0 });
    const [isDiscountExpired, setIsDiscountExpired] = useState(false);

    useEffect(() => {
        // if (!date) return;
        const timer = setInterval(() => {
            const remaining = getRemainingTime(date);
            setTimeRemaining(remaining);

            // Check if discount has expired
            if (remaining.days === 0 && remaining.hours === 0 &&
                remaining.minutes === 0 && remaining.seconds === 0) {
                setIsDiscountExpired(true);
            }
        }, 1000);

        const now = new Date();
        const expirationDate = date ? new Date(date) : new Date();
        if (now >= expirationDate) {
            setIsDiscountExpired(true);
        }

        return () => clearInterval(timer);
    }, [date]);

    return [ timeRemaining, isDiscountExpired ];
}
