<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CurriculumSection
 *
 * @property int $section_id
 * @property int|null $course_id
 * @property string|null $title
 * @property string|null $image_path
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @property-read string|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CurriculumLecturesQuiz[] $lectures
 * @property-read int|null $lectures_count
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurriculumSection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurriculumSection extends Model
{
    public $table = 'curriculum_sections';
    public $guarded = [];

    protected $dates = ['deleted_at'];
    protected $primaryKey = 'section_id';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string'
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

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(CurriculumLecturesQuiz::class, 'section_id');
    }


    public function getImageAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
