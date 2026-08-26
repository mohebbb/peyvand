<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends Controller
{
    /**
     * Resolve a short code and redirect to its destination.
     *
     * Missing or inactive links render a clean local 404 view.
     * Valid, active links increment the click counter and 302-redirect.
     */
    public function __invoke(string $shortCode): RedirectResponse|Response
    {
        $link = ShortLink::query()
            ->where('short_code', $shortCode)
            ->first();

        if ($link === null || ! $link->is_active) {
            abort(404);
        }

        $link->incrementClicks();

        return redirect()->away($link->destination_url, 302);
    }
}
