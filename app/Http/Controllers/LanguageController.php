<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        Log::info("Switching language to: " . $lang);
        if (in_array($lang, config('app.available_locales', ['en', 'es', 'zh']))) {
            Session::put('locale', $lang);
            App::setLocale($lang);
        }
        return redirect()->back();
    }
}