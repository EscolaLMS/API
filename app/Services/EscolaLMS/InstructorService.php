<?php

namespace App\Services\EscolaLMS;

use App\Dto\InstructorCreateDto;
use App\Dto\InstructorUpdateDto;
use App\Models\Instructor;
use App\Repositories\Contracts\InstructorRepositoryContract;
use App\Services\EscolaLMS\Contracts\InstructorServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Image;

class InstructorService implements InstructorServiceContract
{
    private Instructor $model;
    private InstructorRepositoryContract $instructorRepository;

    /**
     * InstructorService constructor.
     * @param Instructor $instructor
     */
    public function __construct(Instructor $instructor, InstructorRepositoryContract $instructorRepository)
    {
        $this->model = $instructor;
        $this->instructorRepository = $instructorRepository;
    }

    public function getInstructorList(): LengthAwarePaginator
    {
        return $this->model->query()->groupBy('instructors.id')->paginate(8);
    }

    public function create(InstructorCreateDto $instructorCreateDto): bool
    {
        $data = $instructorCreateDto->getData();
        $user = $instructorCreateDto->getUser();
        $slug = $data['first_name'] . '-' . $data['last_name'];
        $slug = str_slug($slug, '-');
        $finalSlug = \App\Library\EscolaHelpers::slugify($slug, 'instructors', 'instructor_slug');

        $instructor = Instructor::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'contact_email' => $data['contact_email'],
            'telephone' => $data['telephone'],
            'paypal_id' => $data['paypal_id'],
            'biography' => $data['biography'],
            'instructor_slug' => $finalSlug,
        ]);
        if (!empty($instructor)) {
            $user->assignRole('instructor');
            return true;
        }
        return false;
    }

    public function update(InstructorUpdateDto $instructorUpdateDto): bool
    {
        $data = $instructorUpdateDto->getData();
        $user = $instructorUpdateDto->getUser();
        $files = $instructorUpdateDto->getFiles();

        $instructor = $this->instructorRepository->find($user->instructor->id);
        $instructor->first_name = $data['first_name'];
        $instructor->last_name = $data['last_name'];
        $instructor->contact_email = $data['contact_email'];
        $instructor->telephone = $data['telephone'];
        $instructor->mobile = $data['mobile'];
        $instructor->link_facebook = $data['link_facebook'];
        $instructor->link_linkedin = $data['link_linkedin'];
        $instructor->link_twitter = $data['link_twitter'];
        $instructor->link_googleplus = $data['link_googleplus'];
        $instructor->paypal_id = $data['paypal_id'];
        $instructor->biography = $data['biography'];

        if (!empty($files['course_image']) && !empty($data['course_image_base64'])) {
            $old_image = $data['old_course_image'] ?? '';
            $old_image = str_replace('storage/', '', $old_image);
            if ($old_image && Storage::exists($old_image)) {
                Storage::delete($old_image);
            }

            $file_name = $files['course_image']->getClientOriginalName();
            $exploded = explode(',', $data['course_image_base64'], 2);
            $encoded = $exploded[1];
            $decoded = base64_decode($encoded);
            $image_make = Image::make($decoded)->encode('jpg');

            $path = "instructor/$instructor->id";

            //check if the file name is already exists
            $new_file_name = \SiteHelpers::checkFileName($path, $file_name);
            Storage::put("$path/$new_file_name", $image_make->__toString(), 'public');
            $instructor->instructor_image = "storage/$path/$new_file_name";
        }

        return $instructor->save();
    }
}
