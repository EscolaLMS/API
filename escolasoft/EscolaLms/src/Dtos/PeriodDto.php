<?php

namespace EscolaSoft\EscolaLms\Dtos;

use Carbon\Carbon;
use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use Illuminate\Http\Request;

class PeriodDto implements DtoContract
{
    private ?Carbon $from;
    private ?Carbon $to;

    /**
     * PeriodDto constructor.
     * @param Carbon|null $from
     * @param Carbon|null $to
     */
    public function __construct(?Carbon $from = null, ?Carbon $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function toArray(): array
    {
        return [
            'from' => $this->from(),
            'to' => $this->to()
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        $from = $to = null;

        if ($request->get('from')) {
            $from = (new Carbon($request->get('from')))->startOfDay();
        }

        if ($request->get('to')) {
            $to = (new Carbon($request->get('to')))->endOfDay();
        }

        return new self($from, $to);
    }

    /**
     * @return Carbon|null
     */
    public function from(): ?Carbon
    {
        return $this->from;
    }

    /**
     * @return Carbon|null
     */
    public function to(): ?Carbon
    {
        return $this->to;
    }
}
