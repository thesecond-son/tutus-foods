<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;

class PagesController extends Controller
{
    public function about()
    {
        $homepage = HomepageSetting::firstOrFail();

        return view('web.about', [
            'homepage' => $homepage,
        ]);
    }
}
