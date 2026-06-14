<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $items = News::active()->latest()->get()->values();

        $data['featuredNews'] = $items->first();
        $data['latestNews'] = $items->skip(1)->values();

        return view('news.index', $data);
    }

    public function view($slug)
    {
        $data['news'] = News::active()->where('slug', $slug)->firstOrFail();
        $data['relatedNews'] = News::active()->where('id', '!=', $data['news']->id)
            ->latest()
            ->take(3)
            ->get();

        return view('news.view', $data);
    }
}
