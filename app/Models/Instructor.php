<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Instructor
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $instructor_slug
 * @property string $contact_email
 * @property string $telephone
 * @property string|null $mobile
 * @property string $paypal_id
 * @property string|null $link_facebook
 * @property string|null $link_linkedin
 * @property string|null $link_twitter
 * @property string|null $link_googleplus
 * @property string $biography
 * @property string|null $instructor_image
 * @property string $total_credits
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereBiography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereInstructorImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereInstructorSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereLinkFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereLinkGoogleplus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereLinkLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereLinkTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor wherePaypalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereTotalCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instructor whereUserId($value)
 * @mixin \Eloquent
 */
class Instructor extends Model
{
    protected $table = 'instructors';
    protected $guarded = [];

    protected $appends = ['image'];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getImageAttribute(): string
    {
        return url(empty($this->instructor_image) ? 'backend/assets/images/course_detail.jpg' : $this->instructor_image);
    }
}
