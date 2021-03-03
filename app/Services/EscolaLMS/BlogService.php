<?php

namespace App\Services\EscolaLMS;

use App\Dto\BlogCreateDto;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\EscolaLMS\Contracts\BlogServiceContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use SiteHelpers;
use Symfony\Component\Console\Input\Input;

class BlogService implements BlogServiceContract
{
    public function getList(?string $search = null): LengthAwarePaginator
    {
        $paginate_count = 10;

        if ($search) {
            return Blog::where('blog_title', 'LIKE', '%' . $search . '%')
                ->paginate($paginate_count);
        }

        return Blog::paginate($paginate_count);
    }

    public function find(?string $id = null)
    {
        if ($id) {
            return Blog::find($id);
        }

        return Controller::getColumnTable('blogs');
    }

    public function save(BlogCreateDto $blogDto): string
    {
        if ($blogDto->getId()) {
            $blog = Blog::find($blogDto->getId());
            $success_message = 'Blog updated successfully';
        } else {
            $blog = new Blog();
            $success_message = 'Blog added successfully';

            //create slug only while add

            $slug = $blogDto->getTitle();
            $blog->blog_slug = \App\Library\EscolaHelpers::slugify($slug, 'blogs', 'blog_slug');
        }

        $blog->blog_title = $blogDto->getTitle();
        $blog->description = $blogDto->getDescription();
        $blog->is_active = $blogDto->getIsActive();

        if ($blogDto->getImage() && Input::has('blog_image_base64')) {
            //delete old file
            $old_image = $blogDto->getOldImage();
            if (Storage::exists($old_image)) {
                Storage::delete($old_image);
            }

            //get filename
            $file_name   = $blogDto->getImage()->getClientOriginalName();

            // returns Intervention\Image\Image
            $image_make = Image::make($blogDto->getImageBase64())->encode('jpg');

            // create path
            $path = "blogs";

            //check if the file name is already exists
            $new_file_name = SiteHelpers::checkFileName($path, $file_name);

            //save the image using storage
            Storage::put($path."/".$new_file_name, $image_make->__toString(), 'public');

            $blog->blog_image = $path."/".$new_file_name;
        }

        $blog->save();

        return $success_message;
    }

    public function delete(string $id): void
    {
        Blog::destroy($id);
    }
}
