<?php

namespace App\Mail;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CorporateRequest;

class CorporateRequestAdminMail extends Mailable
{
    use SerializesModels;

    public $requestData;

    public function __construct(CorporateRequest $requestData)
    {
        $this->requestData = $requestData;
    }

    
    public function build()
    {
        return $this->subject('We received your corporate request')
                    ->view('emails.corporate.admin')
                    ->with(['request' => $this->requestData]);
    }
}
