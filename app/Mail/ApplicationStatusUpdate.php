<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdate extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public JobApplication $application)
    {
        $this->application->loadMissing(['job', 'alumni.alumniProfile']);
    }

    public function build(): self
    {
        return $this
            ->subject('Your Job Application Status Has Changed')
            ->view('emails.application-status-update');
    }
}
