<?php

namespace App\Models;

use EscolaLms\Cart\Contracts\Productable;
use EscolaLms\Cart\Contracts\ProductableTrait;
use EscolaLms\Cart\Models\Product;
use EscolaLms\Core\Models\User;
use EscolaLms\Courses\Events\CourseAccessStarted;
use EscolaLms\Courses\Events\CourseAssigned;
use EscolaLms\Courses\Events\CourseFinished;
use EscolaLms\Courses\Events\CourseUnassigned;
use EscolaLms\ModelFields\Traits\ModelFields;


class Course extends \EscolaLms\Courses\Models\Course implements Productable
{
    use ProductableTrait;

    public function attachToUser(User $user, int $quantity = 1, ?Product $product = null): void
    {
        $productUser = $product?->users()->where('user_id', $user->getKey())->first()?->pivot;

        $this->users()->syncWithoutDetaching([$user->getKey() => ['end_date' => $productUser?->end_date]]);
        
        event(new CourseAssigned($user, $this));
        event(new CourseAccessStarted($user, $this));
    }

    public function detachFromUser(User $user, int $quantity = 1, ?Product $product = null): void
    {
        $this->users()->detach($user->getKey());
        event(new CourseUnassigned($user, $this));
        event(new CourseFinished($user, $this));
    }
}
