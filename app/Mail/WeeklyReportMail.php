<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyReportMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Report $report)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Weekly Alumni Portal Summary')
            ->view('emails.weekly-report');
    }
}
