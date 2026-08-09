<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\AboutPageContent;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function index(): View
    {
        $content = AboutPageContent::current();
        return view('website.about.about', compact('content'));
    }
}
