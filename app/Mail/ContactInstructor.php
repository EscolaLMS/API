<?php

namespace App\Mail;

use App\Models\Config;
use App\Services\EscolaLMS\Contracts\ConfigServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInstructor extends Mailable
{
    use Queueable, SerializesModels;

    public $enquiry;

    private ConfigServiceContract $configService;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->enquiry = $request;

        $this->configService = app(ConfigServiceContract::class);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $admin_email = $this->configService->getOption('settingGeneral', 'admin_email');

        return $this->from($admin_email)
               ->subject('Enquiry for Instructor')
               ->view('emails.contact_instructor');
    }
}
