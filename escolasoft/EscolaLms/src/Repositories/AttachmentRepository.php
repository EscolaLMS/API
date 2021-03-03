<?php

namespace EscolaSoft\EscolaLms\Repositories;

use EscolaSoft\EscolaLms\Models\Attachment;
use EscolaSoft\EscolaLms\Repositories\Contracts\AttachmentRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * Class AttachmentRepository
 * @package App\Repositories
 * @version June 7, 2020, 9:00 pm UTC
 */
class AttachmentRepository extends BaseRepository implements AttachmentRepositoryContract
{
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'filename',
        'path',
        'is_thumbnail',
        'user_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Attachment::class;
    }

    /**
     *
     * @return Model
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\UploadException
     */
    public function store(UploadedFile $file): Model
    {
        $path = $file->store("attachments");
        if ($path) {
            $fileModel = $this->create([
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
            ]);

            return $fileModel;
        } else {
            throw new UploadException();
        }
    }
}
