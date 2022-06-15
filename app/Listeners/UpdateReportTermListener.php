<?php

namespace App\Listeners;

use App\Services\Contracts\ConsultationServiceContract;
use EscolaLms\Consultations\Events\ReportTerm;

class UpdateReportTermListener
{
    private ConsultationServiceContract $consultationServiceContract;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->consultationServiceContract = app(ConsultationServiceContract::class);
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ReportTerm $reportTerm)
    {
        $this->consultationServiceContract->updateReportTerm($reportTerm->getConsultationTerm());
    }
}
