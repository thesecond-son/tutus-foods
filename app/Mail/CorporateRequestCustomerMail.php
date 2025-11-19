<?php

namespace App\Mail;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CorporateRequest;

class CorporateRequestCustomerMail extends Mailable
{
    use SerializesModels;

    public $requestData;

    public function __construct(CorporateRequest $requestData)
    {
        $this->requestData = $requestData;
    }

    public function build()
    {
        return $this->subject('New Corporate Request Submitted')
                    ->view('emails.corporate.customer')
                    ->with(['request' => $this->requestData]);
    }
}
