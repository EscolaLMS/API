<?php

namespace App\ValueObjects;

use App\ValueObjects\Contracts\ValueObjectContract;
use Illuminate\Support\Collection;

class UserFiles extends ValueObject implements ValueObjectContract
{
    private Collection $videos;
    private Collection $files;

    public function build(Collection $videos, Collection $files): self
    {
        $this->videos = $videos;
        $this->files = $files;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'uservideos' => $this->getVideos(),
            'useraudios' => $this->getAudios(),
            'userpresentation' => $this->getPresentations(),
            'userdocuments' => $this->getDocuments(),
            'userresources' => $this->getResources(),
        ];
    }

    /**
     * @return Collection
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * @return Collection
     */
    public function allFiles(): Collection
    {
        return $this->allFiles;
    }

    public function getAudios(): Collection
    {
        return $this->files->whereIn('file_extension', ['mp3', 'wav']);
    }

    public function getPresentations(): Collection
    {
        return $this->applyTag($this->getPdfs(), 'curriculum_presentation');
    }

    public function getDocuments(): Collection
    {
        return $this->applyTag($this->getPdfs(), 'curriculum');
    }

    public function getResources(): Collection
    {
        return $this->applyTag($this->files, 'curriculum_resource')->whereIn('file_extension', ['pdf', 'doc', 'docx']);
    }

    public function getPdfs(): Collection
    {
        return $this->files->where('file_extension', 'pdf');
    }

    private function applyTag(Collection $collection, string $tag): Collection
    {
        return $collection->where('file_tag', $tag);
    }
}
