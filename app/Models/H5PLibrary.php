<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
class H5PLibrary extends Model
{
    protected $table = 'h5p_libraries';
    protected $guarded = array();
}
