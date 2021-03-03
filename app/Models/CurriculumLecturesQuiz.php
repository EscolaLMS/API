<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * App\Models\CurriculumLecturesQuiz
 *
 * @property int $lecture_quiz_id
 * @property int|null $section_id
 * @property int|null $type
 * @property string|null $title
 * @property string|null $image_path
 * @property string|null $description
 * @property string|null $contenttext
 * @property string|null $media
 * @property int|null $media_type
 * @property int|null $sort_order
 * @property int $publish
 * @property array|null $resources
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $duration
 * @property-read bool $has_media
 * @property-read \App\Models\CourseProgress|null $progress
 * @property-read \App\Models\CurriculumSection|null $section
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereContenttext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereLectureQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereMediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumLecturesQuiz whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurriculumLecturesQuiz extends Model
{
    use HasJsonRelationships;

    public $table = 'curriculum_lectures_quiz';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'lecture_quiz_id';
    public $guarded = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'contenttext' => 'string',
        'media' => 'string',
        'resources' => 'json'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderScope);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CurriculumSection::class, 'section_id');
    }

    public function progress(): HasOne
    {
        return $this->hasOne(CourseProgress::class, 'lecture_id');
    }

    public function resourceFiles(): BelongsToJson
    {
        return $this->belongsToJson(CourseFiles::class, 'resources');
    }

    // Accessors
    public function getHasMediaAttribute(): bool
    {
        return !is_null($this->media_type);
    }

    public function getImageAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
