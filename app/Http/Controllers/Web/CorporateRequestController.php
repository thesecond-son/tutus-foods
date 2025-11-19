<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CorporateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CorporateRequestCustomerMail;
use App\Mail\CorporateRequestAdminMail;

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
            'email' => 'required|email',
            'phone' => 'required',
            'purpose' => 'required',
            'people' => 'required|integer',
            'type' => 'required',
        ]);

        $corporateRequest = DB::transaction(function() use ($request) {
            $req = CorporateRequest::create($request->all());
    
            // Emails after commit
            DB::afterCommit(function () use ($req) {
                // Customer email
                Mail::to($req->email)->send(new CorporateRequestCustomerMail($req));
    
                // Admin email
                Mail::to('info@tutuscupcakes.com')->send(new CorporateRequestAdminMail($req));
            });
    
            return $req;
        });

        return redirect()->back()->with('success', 'Thank you. Your request has been submitted! We will reach out soon.');
    }
}
