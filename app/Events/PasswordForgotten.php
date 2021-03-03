<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordForgotten
{
    use Dispatchable, SerializesModels;

    private User $user;
    private string $returnUrl;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, string $returnUrl)
    {
        $this->user = $user;
        $this->returnUrl = $returnUrl;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }
}
