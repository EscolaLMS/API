<?php

namespace Tests\Integrations;

use App\Dto\ExternalResourceDto;
use EscolaLms\Core\Enums\UserRole;
use App\Models\Course;
use App\Models\CourseFiles;
use App\Models\CourseVideos;
use App\Models\CurriculumLecturesQuiz;
use App\Models\User;
use App\Services\EscolaLMS\Contracts\MediaContract;
use App\Services\EscolaLMS\Media\AudioMedia;
use App\Services\EscolaLMS\Media\DocumentMedia;
use App\Services\EscolaLMS\Media\TextMedia;
use App\Services\EscolaLMS\Media\VideoMedia;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\MakeServices;
use Tests\TestCase;

class CourseCurriculumTest extends TestCase
{
    use MakeServices;

    private const SEEDER_SHOULD_CREATE_ROWS = 3;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('default');
        Storage::fake('default');
        Storage::fake('local');
    }

    public function test_getCurriculumInfo_when_content_exists(): void
    {
        // This is default seeder data, so counting may be changed
        $course = app(\Database\Seeders\CourseTableSeeder::class)->clearAndRun();
        $result = $this->courseService()->getCourseContent($course, $course->instructor->user)->toArray();

        $this->assertInstanceOf(Course::class, $result['course']);
        //$this->assertCount(self::SEEDER_SHOULD_CREATE_ROWS, $result['sections']);
        //$this->assertCount(self::SEEDER_SHOULD_CREATE_ROWS, $result['lecturesquiz']);
        //$this->assertCount(self::SEEDER_SHOULD_CREATE_ROWS, $result['lecturesmedia']);
        //$this->assertCount(self::SEEDER_SHOULD_CREATE_ROWS, $result['lecturesresources']);
    }

    public function test_getUserFiles_on_seeded_data(): void
    {
        $individualTypeCount = 3;

        $user = factory(User::class)->create();
        $videos = factory(CourseVideos::class, $individualTypeCount)->create([
            'uploader_id' => $user->getKey()
        ]);

        $types = [
            ['curriculum', 'mp3'],
            ['curriculum_presentation', 'pdf'],
            ['curriculum', 'pdf'],
            ['curriculum_resource', 'docx']
        ];

        foreach ($types as $type) {
            [$tag, $type] = $type;

            factory(CourseFiles::class, $individualTypeCount)->create([
                'uploader_id' => $user->getKey(),
                'file_type' => $type,
                'file_extension' => $type,
                'file_tag' => $tag
            ]);
        }

        $results = $this->courseService()->getUserFiles($user)->toArray();

        foreach (['uservideos', 'useraudios', 'userpresentation', 'userdocuments', 'userresources'] as $key) {
            $this->assertCount($individualTypeCount, $results[$key], $key);
        }
    }

    public function test_can_storeVideoLecture(): VideoMedia
    {
        Storage::fake('default');
        Storage::fake('local');
        Mail::fake();
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $video = UploadedFile::fake()->create('video.mp4', 100);
        $user = factory(User::class)->create()->assignRole(UserRole::INSTRUCTOR);
        event(new Registered($user));

        $medium = $this->courseService()->storeVideoLecture($curriculumLecturesQuiz, $video, $user);

        $this->assertInstanceOf(VideoMedia::class, $medium);
        $this->assertInstanceOf(CourseVideos::class, $medium->get());

        return $medium;
    }

    /**
     * @param VideoMedia $media
     * @return VideoMedia
     *
     * @depends test_can_storeVideoLecture
     */
    public function test_can_getResource(VideoMedia $media): VideoMedia
    {
        $this->assertNotEmpty($media->getResource()['url']);
        return $media;
    }

    /**
     * @param VideoMedia $medium
     * @depends test_can_getResource
     */
    public function test_can_delete_video_media(VideoMedia $medium): void
    {
        $this->assertMediumCanBeDeleted($medium);
    }

    public function test_can_storeLectureAudio(): AudioMedia
    {
        Mail::fake();
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $audio = UploadedFile::fake()->create('audio.mp3', 100);
        $user = factory(User::class)->create()->assignRole(UserRole::INSTRUCTOR);
        event(new Registered($user));

        $medium = $this->courseService()->storeLectureAudio($curriculumLecturesQuiz, $audio, $user);

        $this->assertInstanceOf(AudioMedia::class, $medium);
        $this->assertInstanceOf(CourseFiles::class, $medium->get());

        return $medium;
    }

    /**
     * @param AudioMedia $medium
     * @depends test_can_storeLectureAudio
     */
    public function test_can_delete_audio_media(AudioMedia $medium): void
    {
        $this->assertMediumCanBeDeleted($medium);
    }

    public function test_can_storeLectureDocument(): DocumentMedia
    {
        Mail::fake();
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $book = UploadedFile::fake()->create('book.pdf', 100);
        $user = factory(User::class)->create()->assignRole(UserRole::INSTRUCTOR);
        event(new Registered($user));

        $medium = $this->courseService()->storeLectureDocument($curriculumLecturesQuiz, $book, $user);

        $this->assertInstanceOf(DocumentMedia::class, $medium);
        $this->assertInstanceOf(CourseFiles::class, $medium->get());

        return $medium;
    }

    /**
     * @param DocumentMedia $medium
     * @depends test_can_storeLectureDocument
     */
    public function test_can_delete_document_media(DocumentMedia $medium): void
    {
        $this->assertMediumCanBeDeleted($medium);
    }

    public function test_can_storeLectureText(): TextMedia
    {
        Mail::fake();
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $user = factory(User::class)->create()->assignRole(UserRole::INSTRUCTOR);
        event(new Registered($user));

        $medium = $this->courseService()->storeLectureText($curriculumLecturesQuiz, "Example text", $user);

        $this->assertInstanceOf(TextMedia::class, $medium);
        $this->assertIsString($medium->get());

        return $medium;
    }

    /**
     * @param TextMedia $medium
     * @depends test_can_storeLectureText
     */
    public function test_can_delete_text_media(TextMedia $medium): void
    {
        $this->assertNull($medium->delete());
    }

    public function test_can_storeLectureResource(): void
    {
        Mail::fake();
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $book = UploadedFile::fake()->create('book.pdf', 100);
        $user = factory(User::class)->create()->assignRole(UserRole::INSTRUCTOR);
        event(new Registered($user));

        $response = $this->courseService()->storeLectureResource($curriculumLecturesQuiz, $book, $user);
        $curriculumLecturesQuiz->refresh();

        $this->assertEquals("book.pdf", $response->file_title);
        $this->assertContains($response->getKey(), $curriculumLecturesQuiz->resources);
    }

    public function test_can_putLectureResource(): void
    {
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $courseFile = factory(CourseFiles::class)->create();

        $response = $this->courseService()->putLectureResource($curriculumLecturesQuiz, $courseFile);
        $curriculumLecturesQuiz->refresh();

        $this->assertContains($response->getKey(), $curriculumLecturesQuiz->resources);
    }

    public function test_can_storeExternalResource(): CurriculumLecturesQuiz
    {
        $curriculumLecturesQuiz = factory(CurriculumLecturesQuiz::class)->create();
        $user = factory(User::class)->create()->assignRole(UserRole::INSTRUCTOR);

        $externalResourceDto = new ExternalResourceDto("Example", "http://example.com");
        $file = $this->courseService()->storeExternalResource($curriculumLecturesQuiz, $externalResourceDto, $user);
        $curriculumLecturesQuiz->refresh();

        $this->assertContains($file->getKey(), $curriculumLecturesQuiz->resources);

        return $curriculumLecturesQuiz;
    }

    /**
     * @depends test_can_storeExternalResource
     */
    public function test_can_deleteLecture(CurriculumLecturesQuiz $curriculumLecturesQuiz): void
    {
        $this->courseService()->deleteLectureQuiz($curriculumLecturesQuiz);

        $this->assertNull($curriculumLecturesQuiz->fresh());
    }

    private function assertMediumCanBeDeleted(MediaContract $medium): void
    {
        $path = $medium->get()->path;
        $medium->delete();
        $this->assertFalse(Storage::exists($path));
    }
}
