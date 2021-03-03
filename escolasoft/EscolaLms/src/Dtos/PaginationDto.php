<?php

namespace EscolaSoft\EscolaLms\Dtos;

use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class PaginationDto implements DtoContract, InstantiateFromRequest
{
    private ?int $skip;
    private ?int $limit;

    /**
     * PaginationDto constructor.
     * @param int|null $skip
     * @param int|null $limit
     */
    public function __construct(?int $skip, ?int $limit)
    {
        $this->skip = $skip;
        $this->limit = $limit;
    }

    public function toArray(): array
    {
        return [
            'skip' => $this->getSkip(),
            'limit' => $this->getLimit()
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        $limit = config('paginate.default.limit', 10);

        if ($request->get('page')) {
            return new self(
                $request->get('skip', ($request->get('page') - 1) * $limit),
                $request->get('limit', $limit),
            );
        }

        return new self(
            $request->get('skip', config('paginate.default.limit', 0)),
            $request->get('limit', $limit),
        );
    }

    /**
     * @return int|null
     */
    public function getSkip(): ?int
    {
        return $this->skip;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
