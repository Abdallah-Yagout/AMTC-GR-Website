<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
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


        $data['tournaments']=Tournament::take(3)->latest()->get();
        $data['news']=News::take(3)->latest()->get();

        return view('home',$data);
    }

    public function switchLanguage($locale)
    {
        $availableLocales = ['en', 'ar'];
        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
            Session::put('dir', $locale === 'ar' ? 'rtl' : 'ltr');
            App::setLocale($locale);

        }


        return Redirect::back();
    }
}
