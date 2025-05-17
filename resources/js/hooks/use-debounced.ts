import { useRef, useCallback } from "react";

export function useDebounced<T extends (...args: any[]) => void>(
    callback: T,
    delay: number
) {
    const timeoutRef = useRef<NodeJS.Timeout | null>(null);

    const debounced = useCallback((...args: Parameters<T>) => {
        if (timeoutRef.current) clearTimeout(timeoutRef.current);

        timeoutRef.current = setTimeout(() => {
            callback(...args);
        }, delay);
    }, [callback, delay]);

    return debounced;
}
