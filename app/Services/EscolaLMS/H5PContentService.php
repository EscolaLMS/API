<?php

namespace App\Services\EscolaLMS;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Models\H5PContent;
use App\Services\EscolaLMS\Contracts\H5PContentServiceContract;

class H5PContentService implements H5PContentServiceContract
{
    public function find(int $id, array $columns = ['*']): Model
    {
        if ($id) {
            return H5PContent::find($id);
        }
        return null;
    }

    public function delete(int $id): ?bool
    {
        return H5PContent::destroy($id);
    }
}
