<?php

namespace App\Services\EscolaLMS;

use App\Dto\CategoryCreateDto;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Contracts\CategoriesRepositoryContract;
use App\Services\EscolaLMS\Contracts\CategoryServiceContracts;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Dtos\PeriodDto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CategoryService implements CategoryServiceContracts
{
    private CategoriesRepositoryContract $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoriesRepositoryContract $categoryRepository
     */
    public function __construct(CategoriesRepositoryContract $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public static function slufigy($name)
    {
        return \App\Library\EscolaHelpers::slugify($name, 'categories', 'slug');
    }

    public function getList(?string $search = null)
    {
        $paginate_count = 10;

        if ($search) {
            return Category::where('name', 'LIKE', '%' . $search . '%')->paginate($paginate_count);
        }

        return Category::paginate($paginate_count);
    }

    public function find(?string $id = null)
    {
        if ($id) {
            return Category::find($id);
        }

        return Controller::getColumnTable('categories');
    }

    public function save(CategoryCreateDto $categoryDto): string
    {
        if ($categoryDto->getId()) {
            $category = Category::find($categoryDto->getId());
            $success_message = 'Category updated successfully';
        } else {
            $category = new Category();
            $success_message = 'Category added successfully';

            //create slug only while add
            $slug = $categoryDto->getName();
            $category->slug = self::slufigy($slug);
        }

        $icon = $categoryDto->getIcon();

        if (!is_null($icon)) {
            $category->icon = Storage::putFile('categories', $icon, 'public');
        }

        $category->name = $categoryDto->getName();
        $category->icon_class = $categoryDto->getIconClass();

        $category->is_active = $categoryDto->getIsActive();
        $category->parent_id = $categoryDto->getParentId();
        $category->save();

        return $success_message;
    }

    public function delete(string $id): void
    {
        Category::destroy($id);
    }

    public function getPopular(PaginationDto $pagination, PeriodDto $period): Collection
    {
        return $this->categoryRepository->getByPopularity($pagination, $period->from(), $period->to());
    }
}
