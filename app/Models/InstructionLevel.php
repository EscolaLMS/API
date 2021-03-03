<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InstructionLevel
 *
 * @property integer $id
 * @property string $level
 * @method static \Illuminate\Database\Eloquent\Builder|InstructionLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstructionLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstructionLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstructionLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstructionLevel whereLevel($value)
 * @mixin \Eloquent
 */
class InstructionLevel extends Model
{
    protected $table = 'instruction_levels';
    public $timestamps = false;
    protected $guarded = array();
}
