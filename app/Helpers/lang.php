<?php
if (!function_exists('__t')) {
    /**
     * __t
     *
     * Returns translation for given $key with a fallback $default value.
     *
     * @param  string|null  $key
     * @param  string|null  $default
     * @param  array        $replace
     * @param  string|null  $locale
     *
     * @return string|array|null
     */
    function __t(?string $key = null, ?string $default = null, array $replace = [], ?string $locale = null)
    {
        if (Lang::has($key, $locale)) {
            return __($key, $replace, $locale);
        }
        if ($default) {
            if (Str::contains($default, [' ', '.', ':'])) {
                return __($default, $replace, $locale);
            }

            // is probably single word
            return $default;
        }
        return $key;
    }
}

if (!function_exists('__t_common_key')) {
    function __t_common_key(string $message): string
    {
        return 'common.' . str_replace([' ', '.', "'", '"'], ['_', '', '_', '_'], strtolower($message));
    }
}
