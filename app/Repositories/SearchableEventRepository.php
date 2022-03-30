<?php

namespace App\Repositories;

use App\Models\SearchableEvent;
use App\Repositories\Contracts\SearchableEventRepositoryContract;
use EscolaLms\Core\Repositories\BaseRepository;

class SearchableEventRepository extends BaseRepository implements SearchableEventRepositoryContract
{
    protected $fieldSearchable = [];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SearchableEvent::class;
    }
}
