<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CourseProgress
 *
 * @property int $progress_id
 * @property int $user_id
 * @property int $course_id
 * @property int $lecture_id
 * @property int $status 0-incomplete,1-complete
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $seconds
 * @property-read \App\Models\CurriculumLecturesQuiz $lecture
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereLectureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereProgressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseProgress whereUserId($value)
 * @mixin \Eloquent
 */
class CourseProgress extends Model
{
    public $table = 'course_progress';
    protected $primaryKey = 'progress_id';


    protected $dates = ['deleted_at', 'finished_at'];


    public $fillable = [
        'progress_id',
        'user_id',
        'course_id',
        'lecture_id',
        'status',
        'seconds',
        'finished_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'finished_at' => 'datetime'
    ];

    public function lecture(): BelongsTo
    {
        return $this->belongsTo(CurriculumLecturesQuiz::class, 'lecture_id');
    }
}
