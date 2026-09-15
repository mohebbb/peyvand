<?php

namespace App\Support;

class Locales
{
    /**
     * The locale codes the application supports, in display order.
     *
     * @return list<string>
     */
    public static function supported(): array
    {
        return array_keys((array) config('locales.supported'));
    }

    /**
     * The labels shown in language switchers, keyed by locale code. Each
     * label is written in its own language (e.g. "فارسی", "English").
     *
     * @return array<string, string>
     */
    public static function switcherOptions(): array
    {
        return collect((array) config('locales.supported'))
            ->map(fn (array $locale): string => $locale['name'])
            ->all();
    }

    /**
     * The locale selected for the current request: the visitor's session
     * preference when present and valid, otherwise the application default.
     */
    public static function current(): string
    {
        $selected = session((string) config('locales.session_key', 'locale'));

        return static::isValid($selected)
            ? (string) $selected
            : (string) config('app.locale');
    }

    public static function isValid(mixed $locale): bool
    {
        return is_string($locale) && in_array($locale, static::supported(), true);
    }

    /**
     * The text direction ("rtl" or "ltr") of the given or current locale.
     */
    public static function dir(?string $locale = null): string
    {
        $locale ??= static::current();

        return (string) data_get((array) config('locales.supported'), "$locale.dir", 'ltr');
    }

    public static function isRtl(?string $locale = null): bool
    {
        return static::dir($locale) === 'rtl';
    }
}
