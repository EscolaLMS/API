<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseView;
use App\Library\VideoHelpers;
use App\Models\Course;
use App\Models\CourseFiles;
use App\Repositories\Contracts\CourseFileRepositoryContract;
use App\Repositories\Contracts\CourseVideoRepositoryContract;
use EscolaLms\Core\Repositories\Criteria\Primitives\CourseInstructor;
use EscolaLms\Core\Repositories\Criteria\Primitives\CourseSearch;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use Crypt;
use DB;
use EscolaLms\Core\Http\Resources\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Image;
use Session;
use URL;

class CourseCurriculumController extends Controller
{
    private CourseServiceContract $courseService;
    private CourseVideoRepositoryContract $courseVideoRepository;
    private CourseFileRepositoryContract $courseFileRepository;

    /**
     * CourseCurriculumController constructor.
     * @param CourseServiceContract $courseService
     * @param CourseVideoRepositoryContract $courseVideoRepository
     * @param CourseFileRepositoryContract $courseFileRepository
     */
    public function __construct(CourseServiceContract $courseService, CourseVideoRepositoryContract $courseVideoRepository, CourseFileRepositoryContract $courseFileRepository)
    {
        $this->courseService = $courseService;
        $this->courseVideoRepository = $courseVideoRepository;
        $this->courseFileRepository = $courseFileRepository;

        $this->model = new Course(); // TODO: remove that
    }

    public function instructorCourseCurriculum(Course $course, Request $request): View
    {
        $courseContent = $this->courseService->getCourseContent($course)->toArray();
        $userFiles = $this->courseService->getUserFiles($request->user())->toArray();

        return view('instructor.course.create_curriculum', array_merge($courseContent, $userFiles));
    }

    public function instructorCourseCurriculumReact(Course $course, Request $request): View
    {
        $courseContent = $this->courseService->getCourseContent($course)->toArray();
        //$userFiles = $this->courseService->getUserFiles($request->user())->toArray();

        return view('instructor.course.create_curriculum_react', array_merge($courseContent));
    }

    public function postVideo(): JsonResponse
    {
        $video_id = $_POST['vid'];
        $vidoes = $this->model->getVideobyid($video_id);

        return new JsonResponse($vidoes->video_title ?? '');
    }

    public function postLectureQuizDelete(Request $request): JsonResponse
    {
        $this->courseService->deleteLectureQuiz($request->input('lid'));

        return (new Status(true))->response();
    }

    public function postLectureDescSave(Request $request): JsonResponse
    {
        $id = $this->courseService->storeLectureDesc($request->get('lecturedescription'), $request->get('lid'));
        return new JsonResponse($id);
    }

    public function postLecturePresentationSave($lid, Request $request): JsonResponse
    {
        $course_id = $request->input('course_id');
        $document = $request->file('lecturepre');
        $file = array('document' => $document);
        $rules = array('document' => 'required|mimes:pdf');
        $validator = Validator::make($file, $rules);

        if ($validator->fails()) {
            $return_data[] = array(
                'status' => false,
            );
        } else {
            $pdftext = file_get_contents($document);
            $pdfPages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
            $file_tmp_name = $document->getPathName();
            $file_name = explode('.', $document->getClientOriginalName());
            $file_name = $file_name[0] . '_' . time() . rand(4, 9999);
            $file_type = $document->getClientOriginalExtension();
            $file_title = $document->getClientOriginalName();
            $file_size = $document->getSize();

            $request->file('lecturepre')->storeAs('course/' . $course_id, $file_name . '.' . $file_type);

            $courseFiles = new CourseFiles;
            $courseFiles->file_name = $file_name;
            $courseFiles->file_title = $file_title;
            $courseFiles->file_type = $file_type;
            $courseFiles->file_extension = $file_type;
            $courseFiles->file_size = $file_size;
            $courseFiles->duration = $pdfPages;
            $courseFiles->file_tag = 'curriculum_presentation';
            $courseFiles->uploader_id = \Auth::user()->getKey();
            $courseFiles->course_id = $course_id;
            if ($courseFiles->save()) {
                if (!empty($lid)) {
                    $data['media'] = $courseFiles->id;
                    $data['media_type'] = '5';
                    $data['publish'] = '0';
                    $newID = $this->model->insertLectureQuizRow($data, $lid);
                }
                if ($pdfPages == 1) {
                    $pdfPage = $pdfPages . ' Page';
                } else {
                    $pdfPage = $pdfPages . ' Pages';
                }
                $return_data = array(
                    'status' => true,
                    'file_title' => $file_title,
                    'duration' => $pdfPage
                );
            } else {
                $return_data = array(
                    'status' => false,
                );
            }
        }

        return new JsonResponse($return_data);
    }

    public function postLectureResourceDelete(Request $request): JsonResponse
    {
        $this->courseService->deleteLectureResource($request->input('lid'), $request->input('rid'));

        return (new Status(true))->response();
    }

    public function postLectureLibrarySave(Request $request): JsonResponse
    {
        $data = $this->courseService->storeLectureLibrary(
            $request->input('courseid'),
            CourseFiles::find($request->input('lib')),
            $request->input('lid'),
            $request->input('type')
        );

        return new JsonResponse($data);
    }
}
