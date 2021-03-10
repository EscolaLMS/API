<?php

namespace App\Models;

use App\Dto\CurriculumLectureQuizDto;
use App\Enum\LectureType;
use App\Services\Contracts\ImageServiceContract;
use App\Services\EscolaLMS\Contracts\CourseServiceContract;
use App\Services\EscolaLMS\Media\Media;
use EscolaLms\Categories\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SiteHelpers;
use Treestoneit\ShoppingCart\Buyable;
use Treestoneit\ShoppingCart\BuyableTrait;

/**
 * App\Models\Course
 *
 * @property int $id
 * @property int $instructor_id
 * @property int $category_id
 * @property int $instruction_level_id
 * @property string $course_title
 * @property string $course_slug
 * @property string|null $keywords
 * @property string|null $overview
 * @property string|null $course_image
 * @property string|null $thumb_image
 * @property int|null $course_video
 * @property string|null $duration
 * @property string|null $price
 * @property string|null $strike_out_price
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $summary
 * @property int $instructor_income
 * @property-read \EscolaLms\Categories\Models\Category $category
 * @property-read string $instructor_name
 * @property-read \App\Models\Instructor $instructor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CurriculumLecturesQuiz[] $lectures
 * @property-read int|null $lectures_count
 * @property-read \App\Models\InstructionLevel $level
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseProgress[] $progress
 * @property-read int|null $progress_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CurriculumSection[] $sections
 * @property-read int|null $sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @property-read \App\Models\CourseVideos|null $video
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereInstructionLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereInstructorIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereOverview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStrikeOutPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereThumbImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Course extends Model implements Buyable
{
    use BuyableTrait;

    protected $table = 'courses';
    protected $guarded = [];

    public function getBuyablePrice(): float
    {
        return (float)$this->price;
    }

    public function getBuyableDescription(): string
    {
        return $this->course_title;
    }

    public static function is_subscribed($course_slug, $user_id)
    {
        $course = DB::table('courses')->where('course_slug', $course_slug)->first();
        return DB::table('course_taken')
            ->where('course_taken.course_id', $course->id)
            ->where('course_taken.user_id', $user_id)
            ->first();
    }

    public function students_count($course_id)
    {
        return DB::table('course_taken')->where('course_id', $course_id)->count();
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function video()
    {
        return $this->hasOne(CourseVideos::class, 'course_id');
    }

    public function sections()
    {
        return $this->hasMany(CurriculumSection::class, 'course_id');
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'courses_related', 'course_id', 'course_related_id');
    }

    public function getcurriculumArray($id = '', $course_slug = '')
    {
        $lectures = DB::table('curriculum_sections')
            ->join('curriculum_lectures_quiz', 'curriculum_lectures_quiz.section_id', '=', 'curriculum_sections.section_id')
            ->leftJoin('course_videos', 'course_videos.id', '=', 'curriculum_lectures_quiz.media')
            ->leftJoin('course_files', 'course_files.id', '=', 'curriculum_lectures_quiz.media')
            ->select(
                'curriculum_sections.section_id',
                'curriculum_lectures_quiz.lecture_quiz_id',
                'curriculum_sections.title as s_title',
                'curriculum_lectures_quiz.title as l_title'
            )
            ->where('curriculum_sections.course_id', '=', $id)
            ->where("curriculum_lectures_quiz.publish", '=', '1')
            ->orderBy('curriculum_sections.sort_order', 'asc')
            ->orderBy('curriculum_lectures_quiz.sort_order', 'asc')
            ->get()->toArray();


        $lectures_array = array();
        $sections_array = array();
        $s_number = $l_number = 0;
        foreach ($lectures as $lecture) {
            $l_number++;
            if (!in_array($lecture->section_id, $sections_array)) {
                $s_number++;
                $section['section_id'] = $lecture->section_id;
                $section['lecture_quiz_id'] = $lecture->lecture_quiz_id;
                $section['s_title'] = $lecture->s_title;
                $section['l_title'] = $lecture->l_title;
                $section['number'] = $s_number;
                $section['is_section'] = true;

                array_push($lectures_array, $section);
            }

            array_push($sections_array, $lecture->section_id);

            $lecture->is_section = false;
            $lecture->number = $l_number;
            $lecture->lecture_quiz_id = $lecture->lecture_quiz_id;

            $lecture->url = SiteHelpers::encrypt_decrypt($lecture->lecture_quiz_id);
            array_push($lectures_array, (array)$lecture);
        }
        $return['sections'] = $lectures_array;

        return $return;
    }

    public function getcurriculum($id = '')
    {
        // TODO: refactor change to VO CourseContent
        $lectures = DB::table('curriculum_sections')
            ->join('curriculum_lectures_quiz', 'curriculum_lectures_quiz.section_id', '=', 'curriculum_sections.section_id')
            ->leftJoin('course_videos', 'course_videos.id', '=', 'curriculum_lectures_quiz.media')
            ->leftJoin('course_files', 'course_files.id', '=', 'curriculum_lectures_quiz.media')
            ->select(
                'curriculum_sections.section_id',
                'curriculum_sections.title as s_title',
                'curriculum_lectures_quiz.lecture_quiz_id',
                'curriculum_lectures_quiz.title as l_title',
                'curriculum_sections.sort_order as s_sort_order',
                'curriculum_lectures_quiz.sort_order as l_sort_order',
                'curriculum_lectures_quiz.media_type',
                'course_videos.duration as v_duration',
                'course_files.duration as f_duration'
            )
            ->where('curriculum_sections.course_id', '=', $id)
            ->where("curriculum_lectures_quiz.publish", '=', '1')
            ->orderBy('curriculum_sections.sort_order', 'asc')
            ->orderBy('curriculum_lectures_quiz.sort_order', 'asc')
            ->get();

        $return['sections'] = $sections = array();
        $is_curriculum = $videos_count = 0;

        foreach ($lectures as $lecture) {
            $is_curriculum = 1;
            if ($lecture->media_type == 0) {
                $videos_count++;
            }
            $section_id_name = $lecture->section_id . '{#-#}' . $lecture->s_title;
            if (!array_key_exists($section_id_name, $sections)) {
                $sections[$section_id_name] = array();
            }
            array_push($sections[$section_id_name], $lecture);
        }
        $return['is_curriculum'] = $is_curriculum;
        $return['sections'] = $sections;
        $return['lectures_count'] = count($lectures);
        $return['videos_count'] = $videos_count;
        return $return;
    }

    public function insertLectureQuizRow($data, $id = null)
    {
        // TODO: method should be removed after full refactor
        $curriculumLectureQuizDto = new CurriculumLectureQuizDto(
            CurriculumSection::find($data['section_id'] ?? CurriculumLecturesQuiz::find($id)->section_id),
            $data['type'] ?? LectureType::SECTION,
            $data['sort_order'] ?? 0,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['contenttext'] ?? null,
            $data['media'] ?? null,
            $data['media_type'] ?? null,
            $data['resources'] ?? null,
            $data['publish'] ?? true,
        );

        $curriculumLecturesQuiz = $id == null ? $this->getCourseService()->createLectureQuizRow($curriculumLectureQuizDto) : $this->getCourseService()->updateLectureQuizRow($curriculumLectureQuizDto, CurriculumLecturesQuiz::find($id));
        return $curriculumLecturesQuiz->getKey();
    }

    public function insertLectureQuizResourceRow($data, $id)
    {
        $lecturesquiz = DB::table('curriculum_lectures_quiz')->where('lecture_quiz_id', '=', $id)->get();

        if (!$lecturesquiz->isEmpty() && !is_null($lecturesquiz['0']->resources)) {
            $resources = json_decode($lecturesquiz['0']->resources, true);
            array_push($resources, $data['resources']);
        } else {
            $resources = array($data['resources']);
        }
        $data['resources'] = json_encode($resources);
        $this->insertLectureQuizRow($data, $id);
    }

    public function postLectureResourceDelete($lid, $rid)
    {
        $resfiles = DB::table('course_files')->where('id', '=', $rid)->get();

        if (!$resfiles->isEmpty()) {
            DB::table('course_files')->where('id', '=', $rid)->delete();

            $lecturesquiz = DB::table('curriculum_lectures_quiz')->where('lecture_quiz_id', '=', $lid)->get();

            if (!$lecturesquiz->isEmpty() && !is_null($lecturesquiz['0']->resources)) {
                $resources = json_decode($lecturesquiz['0']->resources, true);
                if (($key = array_search($rid, $resources)) !== false) {
                    unset($resources[$key]);
                }
            }
            $data['resources'] = json_encode($resources);
            $this->insertLectureQuizRow($data, $lid);
        }
    }


    public function checkDeletePreviousFiles($lectureId): void
    {
        Media::make(CurriculumLecturesQuiz::find($lectureId))->delete();
    }

    public function getcurriculumsection($id = '')
    {
        return DB::table('curriculum_sections')
            ->join('curriculum_lectures_quiz', 'curriculum_lectures_quiz.section_id', '=', 'curriculum_sections.section_id')
            ->select('curriculum_sections.section_id', 'curriculum_sections.course_id', 'curriculum_sections.title', 'curriculum_sections.sort_order')
            ->where('curriculum_sections.course_id', '=', $id)->where("curriculum_lectures_quiz.publish", '=', '1')->orderBy('curriculum_sections.sort_order', 'asc')->groupBy('curriculum_sections.section_id')->get();
    }

    public function getResources($resources)
    {
        $resource_files = array();
        if ($resources) {
            $resources = json_decode($resources);

            foreach ($resources as $resource) {
                $resource_file = DB::table("course_files")->where("course_files.id", '=', $resource)->first();
                array_push($resource_files, $resource_file);
            }
        }
        return $resource_files;
    }

    public function getlecturedetails($lid = '')
    {
        $getmediatype = DB::table("curriculum_lectures_quiz")->where("lecture_quiz_id", '=', $lid)->get();
        if (count($getmediatype) > 0) {
            $mediaid = $getmediatype['0']->media;
            $lecture_quiz_id = $getmediatype['0']->lecture_quiz_id;
            if (isset($getmediatype['0']->media_type) && $getmediatype['0']->media_type == '0') {
                return DB::table("curriculum_lectures_quiz")
                    ->select('curriculum_lectures_quiz.*', 'course_videos.*', 'curriculum_sections.title as section_title')
                    ->leftJoin('curriculum_sections', 'curriculum_sections.section_id', '=', 'curriculum_lectures_quiz.section_id')
                    ->leftJoin('course_videos', 'course_videos.id', '=', 'curriculum_lectures_quiz.media')
                    ->where("curriculum_lectures_quiz.media", '=', $mediaid)
                    ->where("curriculum_lectures_quiz.lecture_quiz_id", '=', $lecture_quiz_id)->first();
            } elseif (isset($getmediatype['0']->media_type) && $getmediatype['0']->media_type == '3') {
                return DB::table("curriculum_lectures_quiz")
                    ->select('curriculum_lectures_quiz.*', 'curriculum_sections.title as section_title')
                    ->leftJoin('curriculum_sections', 'curriculum_sections.section_id', '=', 'curriculum_lectures_quiz.section_id')
                    ->where("curriculum_lectures_quiz.lecture_quiz_id", '=', $lecture_quiz_id)->first();
            } elseif (isset($getmediatype['0']->media_type) && ($getmediatype['0']->media_type == '2' || $getmediatype['0']->media_type == '1')) {
                return DB::table("curriculum_lectures_quiz")
                    ->select('curriculum_lectures_quiz.*', 'course_files.*', 'curriculum_sections.title as section_title', 'curriculum_sections.course_id')
                    ->leftJoin('curriculum_sections', 'curriculum_sections.section_id', '=', 'curriculum_lectures_quiz.section_id')
                    ->leftJoin('course_files', 'course_files.id', '=', 'curriculum_lectures_quiz.media')
                    ->where("curriculum_lectures_quiz.media", '=', $mediaid)
                    ->where("curriculum_lectures_quiz.lecture_quiz_id", '=', $lecture_quiz_id)->first();
            } else {
                return $getmediatype;
            }
        }
    }

    public function getfirstlecturedetails($cid = '')
    {
        $getmediatype = DB::table("curriculum_sections")
            ->join('curriculum_lectures_quiz', 'curriculum_lectures_quiz.section_id', '=', 'curriculum_sections.section_id')
            ->where("curriculum_sections.course_id", '=', $cid)->get();

        if (count($getmediatype) > 0) {
            if (isset($getmediatype['0']->media_type) && $getmediatype['0']->media_type == '0') {
                $mediaid = $getmediatype['0']->media;
                return DB::table("curriculum_lectures_quiz")
                    ->leftJoin('course_videos', 'course_videos.id', '=', 'curriculum_lectures_quiz.media')
                    ->where("curriculum_lectures_quiz.media", '=', $mediaid)->get();
            } elseif (isset($getmediatype['0']->media_type) && $getmediatype['0']->media_type == '3') {
                $mediaid = $getmediatype['0']->media;
                return DB::table("curriculum_lectures_quiz")
                    ->where("curriculum_lectures_quiz.media", '=', $mediaid)->get();
            } else {
                $mediaid = $getmediatype['0']->media;
                return DB::table("curriculum_lectures_quiz")
                    ->leftJoin('course_files', 'course_files.id', '=', 'curriculum_lectures_quiz.media')
                    ->where("curriculum_lectures_quiz.media", '=', $mediaid)->get();
            }
        }
    }

    public function getFileDetails($id = '')
    {
        return DB::table('course_files')
            ->select('course_files.*', 'curriculum_sections.course_id')
            ->leftJoin('curriculum_lectures_quiz', 'course_files.id', '=', 'curriculum_lectures_quiz.media')
            ->leftJoin('curriculum_sections', 'curriculum_sections.section_id', '=', 'curriculum_lectures_quiz.section_id')
            ->where('course_files.id', '=', $id)
            ->first();
    }

    // get next and previous lecturer id's
    public function getalllecture($cid = '')
    {
        $sections = DB::table("curriculum_sections")->select('section_id')->where("course_id", '=', $cid)->get();
        $section_id = array();
        foreach ($sections as $value) {
            $section_id[] = $value->section_id;
        }

        $lectures = DB::table("curriculum_lectures_quiz")
            ->select("lecture_quiz_id")
            ->where("publish", '=', 1)
            ->whereIn('section_id', $section_id)
            ->orderBy('section_id')
            ->orderBy('sort_order')
            ->get();

        return $lectures;
    }

    public static function getCoursecompletedStatus($lecture_id)
    {
        return DB::table('course_progress')->where('lecture_id', $lecture_id)->where('user_id', \Auth::user()->id)->where('status', 1)->count();
    }

    public function getVideobyid($video_id = '')
    {
        return DB::table('curriculum_lectures_quiz')
            ->join('course_videos', 'course_videos.id', '=', 'curriculum_lectures_quiz.media')
            ->where('curriculum_lectures_quiz.lecture_quiz_id', $video_id)->first();
    }

    public function getFilebyid($file_id = '')
    {
        return DB::table('curriculum_lectures_quiz')
            ->join('course_files', 'course_files.id', '=', 'curriculum_lectures_quiz.media')
            ->where('curriculum_lectures_quiz.lecture_quiz_id', $file_id)->first();
    }

    public function getvideoinfo($id = '')
    {
        return DB::table('course_videos')->where('id', '=', $id)->get();
    }

    public function getvideoinfoFirst($id = '')
    {
        return DB::table('course_videos')->where('id', '=', $id)->first();
    }

    public function getcourseid($lid = '')
    {
        return DB::table('curriculum_lectures_quiz')
            ->join('curriculum_sections', 'curriculum_sections.section_id', '=', 'curriculum_lectures_quiz.section_id')
            ->select('curriculum_sections.course_id')
            ->where('curriculum_lectures_quiz.lecture_quiz_id', '=', $lid)
            ->get();
    }

    public function getcourseinfo($id = '')
    {
        $course_get = DB::table('courses')->where('id', '=', $id)->first();
        return $course_get;
    }

    public function updateLectureStatus($course_id = '', $lecture_id = '', $status = '')
    {
        $user_id = Auth::user()->id;
        $lecture = DB::table("course_progress")
            ->where("course_id", '=', $course_id)
            ->where("lecture_id", '=', $lecture_id)
            ->where("user_id", '=', $user_id)
            ->first();
        $status = $status == 'true' ? 1 : 0;
        $dataarray = array();
        if ($lecture) {
            $getid = $lecture->progress_id;
            DB::table('course_progress')->where("progress_id", '=', $getid)->update($dataarray);
            return $getid;
        } else {
            $dataarray['course_id'] = $course_id;
            $dataarray['user_id'] = $user_id;
            $dataarray['lecture_id'] = $lecture_id;
            $dataarray['status'] = $status;
            return DB::table('course_progress')->insertGetId($dataarray);
        }
    }

    /**
     * Get all of the record's tags.
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'morphable');
    }


    public function level(): BelongsTo
    {
        return $this->belongsTo(InstructionLevel::class, 'instruction_level_id');
    }

    public function lectures(): HasManyThrough
    {
        return $this->hasManyThrough(CurriculumLecturesQuiz::class, CurriculumSection::class, 'course_id', 'section_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseProgress::class, 'course_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }


    public function getCourseImageAttribute($path): ?string
    {
        return $this->getImageService()->url($path, 'large');
    }

    public function getThumbImageAttribute($path): ?string
    {
        return $this->getImageService()->url($path, 'large');
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category->name ?? '';
    }

    public function getInstructorNameAttribute(): string
    {
        return ($this->instructor->first_name ?? '') . ' ' . ($this->instructor->last_name ?? '');
    }


    /**
     * @return CourseServiceContract
     *
     * This is used only for refactor, we don't know where methods from model are used yet,
     * so before full refactor we will use existing method replaced by service solution
     */
    private function getCourseService(): CourseServiceContract
    {
        return app(CourseServiceContract::class);
    }

    private function getImageService(): ImageServiceContract
    {
        return app(ImageServiceContract::class);
    }
}
