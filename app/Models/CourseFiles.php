<?php namespace App\Models;

use App\Models\Contracts\MediaModelContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CourseFiles
 *
 * @property integer $id
 * @property string $file_name
 * @property string $file_title
 * @property string $file_type
 * @property string $file_extension
 * @property string $file_size
 * @property string|null $duration
 * @property string $file_tag
 * @property integer $uploader_id
 * @property integer $processed 0-not processed,1-processed
 * @property integer $created_at
 * @property integer $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereFileExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereFileTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereFileTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseFiles whereUploaderId($value)
 * @mixin \Eloquent
 */
class CourseFiles extends Model implements MediaModelContract
{
    protected $table = 'course_files';
    public $guarded = [];

    protected $appends = [
        'url',
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getUrlAttribute(): string
    {
        if ($this->file_type == 'link') {
            return $this->file_name;
        }

        return route('file', ['path' => $this->path]);
    }

    public function getPathAttribute(): string
    {
        return 'course/' . $this->course->getKey() . '/' . $this->file_name . '.' . $this->file_type;
    }
}
