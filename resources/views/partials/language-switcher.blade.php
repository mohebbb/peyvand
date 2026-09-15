{{--
    Language switcher used on public pages. Uses inline styles because the
    fallback CSS embedded in welcome.blade.php is a pre-compiled snapshot
    and does not include new utility classes.
--}}
<div style="display: inline-flex; align-items: center; font-size: 0.875rem;">
    @foreach (\App\Support\Locales::switcherOptions() as $locale => $name)
        @unless ($loop->first)
            <span style="margin: 0 0.5rem; color: #9ca3af;" aria-hidden="true">|</span>
        @endunless
        @if ($locale === \App\Support\Locales::current())
            <span style="font-weight: 700; color: currentColor;">{{ $name }}</span>
        @else
            <a
                href="{{ route('locale.switch', ['locale' => $locale]) }}"
                style="color: #f53003; text-decoration: underline; text-underline-offset: 2px;"
                hreflang="{{ $locale }}"
            >{{ $name }}</a>
        @endif
    @endforeach
</div>
