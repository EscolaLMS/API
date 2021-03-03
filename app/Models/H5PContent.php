<?php

namespace App\Models;

use App\Models\Contracts\MediaModelContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\H5PContent
 *
 * @property integer $id
 * @property string $name
 * @property string|null $slug
 * @property string $icon_class
 * @property integer $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|H5PContent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class H5PContent extends Model implements MediaModelContract
{
    protected $table = 'h5p_contents';
    protected $guarded = array();

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'parameters' => 'json',
        'filtered' => 'json',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function library()
    {
        return $this->belongsTo(H5PLibrary::class, 'library_id');
    }

    public function getUrlAttribute(): string
    {
        return '';
    }

    public function getPathAttribute(): string
    {
        return '';
    }
}
