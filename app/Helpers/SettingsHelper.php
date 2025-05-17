<?php

use Illuminate\Support\Str;

//// Test database connection
//try {
//    if (! function_exists('setting')) {
//        function setting($key)
//        {
//            // Check for settings with a dot notation to access Spatie settings
//            if (str_contains($key, '.')) {
//                [$group, $name] = explode('.', $key, 2);
//                $settingClassName = '\\App\\Settings\\'.ucfirst($group).'Settings';
//
//                if (class_exists($settingClassName)) {
//                    $settings = app($settingClassName);
//
//                    return $settings->$name ?? null;
//                }
//            }
//
//            // Fallback to the original implementation
//            $settingRecord = \App\Models\Setting::where('name', $key)->first();
//
//            return $settingRecord ? $settingRecord->payload : null;
//        }
//    }
//    if (! function_exists('dollar')) {
//        function dollar($total)
//        {
//            $getDollar = setting('site_currency');
//            if ($getDollar) {
//                return '<b>'.number_format($total, 2)."</b><small>$getDollar</small>";
//            } else {
//                return false;
//            }
//        }
//    }
//
//} catch (\Exception $e) {
//    if (! function_exists('setting')) {
//        function setting($key)
//        {
//            return $key;
//        }
//    }
//}
if (! function_exists('getCurrentSession')) {
    function getCurrentSession(): string
    {
        // use custom instead of session id to avoid session refresh
        return session('device_id');
    }
}

if (! function_exists('getTokenCheckout')) {
    function getTokenCheckout(): string
    {
        $token = Str::uuid().Str::uuid();

        return str_replace('-', '', $token);
    }
}
