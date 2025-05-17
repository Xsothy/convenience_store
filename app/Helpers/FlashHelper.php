<?php

if (! function_exists('flash_success')) {
    /**
     * Set a success flash message
     *
     * @param  string  $message  The message to flash
     */
    function flash_success(string $message): void
    {
        session()->flash('success', $message);
    }
}

if (! function_exists('flash_error')) {
    /**
     * Set an error flash message
     *
     * @param  string  $message  The message to flash
     */
    function flash_error(string $message): void
    {
        session()->flash('error', $message);
    }
}

if (! function_exists('flash_warning')) {
    /**
     * Set a warning flash message
     *
     * @param  string  $message  The message to flash
     */
    function flash_warning(string $message): void
    {
        session()->flash('warning', $message);
    }
}

if (! function_exists('flash_info')) {
    /**
     * Set an info flash message
     *
     * @param  string  $message  The message to flash
     */
    function flash_info(string $message): void
    {
        session()->flash('info', $message);
    }
}
