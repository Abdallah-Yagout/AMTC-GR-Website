<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $data['news']=News::paginate();
        return view('news.index',$data);
    }

    public function view($slug)
    {
        $data['news']=News::where('slug',$slug)->firstOrFail();
        $data['relatedNews'] = News::where('id', '!=', $data['news']->id)
            ->latest()
            ->take(3)
            ->get();
        return view('news.view',$data);
    }
}
