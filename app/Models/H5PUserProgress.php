<?php

namespace App\Models;

use App\Models\Contracts\MediaModelContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\H5PUserProgress
 *
*/
class H5PUserProgress extends Model
{
    protected $table = 'h5p_user_progress';
    protected $guarded = array();

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(CurriculumLecturesQuiz::class, 'lecture_quiz_id');
    }
}
