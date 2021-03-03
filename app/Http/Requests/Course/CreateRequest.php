<?php

namespace App\Http\Requests\Course;

use App\Enum\UserRole;

class CreateRequest extends CourseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasAnyRole(UserRole::ADMIN, UserRole::INSTRUCTOR);
    }
}
