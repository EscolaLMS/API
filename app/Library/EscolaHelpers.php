<?php
namespace App\Library;

use Illuminate\Support\Facades\DB;

class EscolaHelpers
{
    public static function HumanFileSize($size, $unit="")
    {
        if ((!$unit && $size >= 1<<30) || $unit == "GB") {
            return number_format($size/(1<<30), 2)." GB";
        }
        if ((!$unit && $size >= 1<<20) || $unit == "MB") {
            return number_format($size/(1<<20), 2)." MB";
        }
        if ((!$unit && $size >= 1<<10) || $unit == "KB") {
            return number_format($size/(1<<10), 2)." KB";
        }
        return number_format($size)." bytes";
    }

    /**
     * @param string $name name that should be `slufigy`
     * @param string $table name of a table
     * @param string $column name of a table column
     * @return string slug
     */
    public static function slugify($name, $table = null, $column = null)
    {
        $slug = str_slug($name, '-');
        if ($table) {
            $results = DB::select(DB::raw("SELECT count(*) as total from $table where $column LIKE '$slug%'"));
            $slug = ($results['0']->total > 0) ? "{$slug}-{$results['0']->total}" : $slug;
        }
        return $slug;
    }
}
