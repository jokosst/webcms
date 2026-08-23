<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['id', 'en'])) {
            Session::put('locale', $locale);
            session()->save();
            App::setLocale($locale);
        }

        return back();
    }
}
