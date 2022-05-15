<?php

namespace App\Mail;

use App\Organisation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Class OrganisationCreated
 * @package App\Mail
 */
class OrganisationCreated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    protected $organisation;
    public $data;

    /**
     * OrganisationCreated constructor.
     * @param Organisation $organisation
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
        $this->processData();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Congratulations')->view('emails.organisations.created', ['data' => $this->data]);
    }

    private function processData(): void {
        $this->data['organisation_name'] = $this->organisation->name;
        $this->data['owner_name'] = $this->organisation->owner->name;
        $this->data['expires_on'] = Carbon::parse($this->organisation->trial_end)->toDateString();
    }
}
