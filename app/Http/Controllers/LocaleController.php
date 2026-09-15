<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Store the selected language in the session and return the visitor to
     * the page they came from (or the home page on a first visit).
     */
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(Locales::isValid($locale), 404);

        $request->session()->put((string) config('locales.session_key', 'locale'), $locale);

        $previous = url()->previous();

        if (blank($previous) || (string) parse_url($previous, PHP_URL_PATH) === '/'.$request->path()) {
            $previous = url('/');
        }

        return redirect()->to($previous);
    }
}
