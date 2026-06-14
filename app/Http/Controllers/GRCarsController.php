<?php

namespace App\Http\Controllers;

use App\Models\GRCar;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class GRCarsController extends Controller
{
    public function __construct()
    {
        App::setLocale(Session::get('locale', config('app.locale')));
    }

    public function index()
    {
        $cars = Schema::hasTable('gr_cars')
            ? GRCar::query()->active()->ordered()->get()
            : collect();

        return view('gr-cars.index', compact('cars'));
    }
}
