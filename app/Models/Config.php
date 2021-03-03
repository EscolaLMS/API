<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Config
 *
 * @property integer $id
 * @property string $code
 * @property string $option_key
 * @property string|null $option_value
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereOptionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereOptionValue($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    protected $table = 'options';

    public $timestamps = false;

    protected $guarded = [];
}
