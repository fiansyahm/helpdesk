<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pageview;

class PageviewController extends Controller
{
    public function index()
    {
        $pageview = Pageview::all();
        return view('admin.admin', ['pageview' => $pageview]);
    }

}
