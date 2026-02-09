<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Models\AboutUsSettings;

class PagesController extends Controller
{
    public function about()
    {
        $homepage = HomepageSetting::firstOrFail();

        $aboutUs = AboutUsSettings::firstOrFail();

        return view('web.about', [
            'homepage' => $homepage,
            'aboutUs' => $aboutUs,
        ]);
    }

    public function refund()
    {
        $homepage = HomepageSetting::firstOrFail();

        return view('web.refund', [
            'homepage' => $homepage,
        ]);
    }

    public function privacy()
    {
        $homepage = HomepageSetting::firstOrFail();

        return view('web.privacy', [
            'homepage' => $homepage,
        ]);
    }
}
