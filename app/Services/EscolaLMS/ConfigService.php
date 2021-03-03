<?php

namespace App\Services\EscolaLMS;

use App\Models\Config;
use App\Repositories\Contracts\ConfigRepositoryContract;
use App\Services\EscolaLMS\Contracts\ConfigServiceContract;
use Illuminate\Support\Facades\Storage;
use SiteHelpers;

class ConfigService implements ConfigServiceContract
{
    private ConfigRepositoryContract $configRepository;

    public function __construct(ConfigRepositoryContract $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function save(string $code, array $input, array $files): void
    {
        foreach ($files as $file_key => $file) {
            //delete old file
            if (Storage::exists($input['old_' . $file_key])) {
                Storage::delete($input['old_' . $file_key]);
            }
            unset($input['old_' . $file_key]);
            //save the file in original name
            $file_name = $file->getClientOriginalName();
            // create path
            $path = 'config';

            //check if the file name is already exists
            $new_file_name = SiteHelpers::checkFileName($path, $file_name);

            //upload the image and save the image name in array, to save it in DB
            $input[$file_key] = $file->storeAs($path, $new_file_name);
        }

        $this->configRepository->save($code, $input);
    }

    public function getOptions(string $code): array
    {
        return $this->configRepository->getOptions($code);
    }

    public function getOption(string $code = '', string $key = ''): string
    {
        $option = $this->configRepository->getOption($code, $key);

        return $option ? $option->option_value : '';
    }
}
