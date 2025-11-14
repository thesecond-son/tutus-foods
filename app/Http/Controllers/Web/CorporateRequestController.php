<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CorporateRequest;

class CorporateRequestController extends Controller
{
    public function index()
    {
        return view('web.corporate.index');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'purpose' => 'required',
            'people' => 'required|integer',
            'type' => 'required',
        ]);

        CorporateRequest::create($request->all());

        return redirect()->back()->with('success', 'Thank you. Your request has been submitted! We will reach out soon.');
    }
}
