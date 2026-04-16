<?php

namespace App\Mail;

use App\Models\JobMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobMatchAlert extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public JobMatch $match)
    {
        $this->match->loadMissing(['job.employer', 'alumni.alumniProfile']);
    }

    public function build(): self
    {
        return $this
            ->subject('New Job Match Recommendation')
            ->view('emails.job-match-alert');
    }
}
