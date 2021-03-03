<?php


namespace App\Dto;

use EscolaSoft\EscolaLms\Dtos\Contracts\DtoContract;
use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class FilterCourseListDto implements DtoContract, InstantiateFromRequest
{
    private $category_search;
    private $instruction_level_id;
    private $prices;
    private $sort_price;
    private $keyword;

    /**
     * FilterCourseList constructor.
     * @param $category_search
     * @param $instruction_level_id
     * @param $prices
     * @param $sort_price
     * @param $keyword
     */
    public function __construct($category_search, $instruction_level_id, $prices, $sort_price, $keyword)
    {
        $this->category_search = $category_search;
        $this->instruction_level_id = $instruction_level_id;
        $this->prices = $prices;
        $this->sort_price = $sort_price;
        $this->keyword = $keyword;
    }

    /**
     * @return mixed
     */
    public function getCategorySearch()
    {
        return $this->category_search;
    }

    /**
     * @return mixed
     */
    public function getInstructionLevelId()
    {
        return $this->instruction_level_id;
    }

    /**
     * @return mixed
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @return mixed
     */
    public function getSortPrice()
    {
        return $this->sort_price;
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    public function toArray(): array
    {
        return [
            'category_search' => $this->getCategorySearch(),
            'instruction_level_id' => $this->getInstructionLevelId(),
            'prices' => $this->getPrices(),
            'sort_price' => $this->getSortPrice(),
            'keyword' => $this->getKeyword(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->input('category_id'),
            $request->input('instruction_level_id'),
            $request->input('price_id'),
            $request->input('sort_price'),
            $request->input('keyword')
        );
    }
}
