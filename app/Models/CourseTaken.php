<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseTaken
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTaken whereUserId($value)
 * @mixin \Eloquent
 */
class CourseTaken extends Model
{
    protected $table = 'course_taken';
    protected $guarded = array();
}
