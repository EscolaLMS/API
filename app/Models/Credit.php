<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Credit
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property integer $instructor_id
 * @property integer $user_id
 * @property integer $course_id
 * @property string|null $credit
 * @property integer|null $credits_for 1-course_cost,2-course_commission
 * @property integer $is_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\Instructor $instructor
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Credit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Credit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Credit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereCreditsFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereUserId($value)
 * @mixin \Eloquent
 */
class Credit extends Model
{
    protected $table = 'credits';
    protected $guarded = array();

    public function instructor()
    {
        return $this->belongsTo('App\Models\Instructor', 'instructor_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id', 'id');
    }
}
