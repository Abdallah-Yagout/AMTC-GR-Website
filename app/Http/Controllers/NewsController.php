<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $data['news']=News::active()->paginate();
        return view('news.index',$data);
    }

    public function view($slug)
    {
        $data['news']=News::active()->where('slug',$slug)->firstOrFail();
        $data['relatedNews'] = News::active()->where('id', '!=', $data['news']->id)
            ->latest()
            ->take(3)
            ->get();
        return view('news.view',$data);
    }
}
