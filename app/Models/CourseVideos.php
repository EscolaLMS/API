<?php namespace App\Models;

use App\Models\Contracts\MediaModelContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\CourseVideos
 *
 * @property integer $id
 * @property string|null $video_title
 * @property string $video_name
 * @property string|null $video_type
 * @property string|null $duration
 * @property string|null $image_name
 * @property string|null $video_tag
 * @property integer|null $uploader_id
 * @property integer|null $course_id
 * @property integer $processed 0-not processed,1-processed
 * @property integer|null $created_at
 * @property integer|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereUploaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereVideoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereVideoTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereVideoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseVideos whereVideoType($value)
 * @mixin \Eloquent
 */
class CourseVideos extends Model implements MediaModelContract
{
    protected $table = 'course_videos';
    public $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getPathAttribute(): string
    {
        return 'course/' . $this->course->getKey() . '/' . $this->video_title . '.' . $this->video_type;
    }
}
