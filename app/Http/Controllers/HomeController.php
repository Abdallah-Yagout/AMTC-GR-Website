<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Tournament;
use App\Models\Video;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct()
    {
        App::setLocale(Session::get('locale', config('app.locale')));
    }

    public function index()
    {

        $data['upcoming_tournament'] = Tournament::where('start_date', '>=', Carbon::now())
            ->orderBy('start_date', 'asc')  // Changed from 'date' to 'start_date'
            ->first();

        $data['tournaments'] = Tournament::take(3)->latest()->get();
        $data['news'] = News::active()->take(5)->latest()->get();
        $data['videos'] = Schema::hasTable('videos')
            ? Video::query()->active()->ordered()->take(10)->get()
            : collect();
        $data['heroSlides'] = Schema::hasTable('hero_slides')
            ? HeroSlide::query()->active()->ordered()->get()
            : collect();

        return view('home', $data);
    }

    public function switchLanguage($locale)
    {
        $availableLocales = ['en', 'ar'];
        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
            App::setLocale($locale);

        }

        return Redirect::back();
    }
}
