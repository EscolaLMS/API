<?php

namespace App\Events;

use App\Models\CurriculumSection;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SectionCompleted
{
    use Dispatchable, SerializesModels;

    private CurriculumSection $section;
    private User $user;

    public function __construct(CurriculumSection $section, User $user)
    {
        $this->section = $section;
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSection(): CurriculumSection
    {
        return $this->section;
    }
}
