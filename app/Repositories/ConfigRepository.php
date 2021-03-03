<?php

namespace App\Repositories;

use App\Models\Config;
use App\Repositories\Contracts\ConfigRepositoryContract;

class ConfigRepository extends BaseRepository implements ConfigRepositoryContract
{
    public function getFieldsSearchable()
    {
        return [];
    }

    public function model()
    {
        return Config::class;
    }

    public function save(string $code, array $options): void
    {
        foreach ($options as $key => $value) {
            $this->model->newQuery()->updateOrInsert([
                'code' => $code,
                'option_key' => $key,
            ], [
                'option_value' => $value,
            ]);
        }
    }

    public function getOption(string $code, string $key): ?Config
    {
        return $this->model->newQuery()
            ->where('code', $code)
            ->where('option_key', $key)
            ->first();
    }

    public function getOptions(string $code): array
    {
        return $this->model->newQuery()
            ->where('code', $code)
            ->pluck('option_value', 'option_key')
            ->toArray();
    }
}
