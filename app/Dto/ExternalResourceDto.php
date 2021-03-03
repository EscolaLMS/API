<?php

namespace App\Dto;

use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class ExternalResourceDto implements DtoContract, InstantiateFromRequest
{
    private string $title;
    private string $link;

    /**
     * ExternalResourceDto constructor.
     * @param string $title
     * @param string $link
     */
    public function __construct(string $title, string $link)
    {
        $this->title = $title;
        $this->link = $link;
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->get('title'),
            $request->get('link')
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'link' => $this->getLink(),
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
