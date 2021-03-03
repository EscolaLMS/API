<?php

namespace App\Repositories;

use App\Models\CourseFile;
use App\Models\CourseFiles;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CourseFileRepositoryContract;

/**
 * Class CourseFileRepository
 * @package App\Repositories
 * @version December 10, 2020, 5:16 pm UTC
 */
class CourseFileRepository extends BaseRepository implements CourseFileRepositoryContract
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'file_name',
        'file_title',
        'file_type',
        'file_extension',
        'file_size',
        'duration',
        'file_tag',
        'uploader_id',
        'processed'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CourseFiles::class;
    }
}
