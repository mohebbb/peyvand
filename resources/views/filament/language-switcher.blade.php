{{--
    Language switcher rendered via the `panels::auth.login.form.after`
    render hook. Uses inline styles because it is not part of the compiled
    Filament stylesheet.
--}}
<div style="margin-top: 1rem; text-align: center; font-size: 0.8125rem;">
    @foreach (\App\Support\Locales::switcherOptions() as $locale => $name)
        @unless ($loop->first)
            <span style="color: #9ca3af; margin: 0 0.5rem;" aria-hidden="true">|</span>
        @endunless
        @if ($locale === \App\Support\Locales::current())
            <span style="font-weight: 700; color: #f59e0b;">{{ $name }}</span>
        @else
            <a
                href="{{ route('locale.switch', ['locale' => $locale]) }}"
                style="color: #9ca3af; text-decoration: underline; text-underline-offset: 2px;"
            >{{ $name }}</a>
        @endif
    @endforeach
</div>
