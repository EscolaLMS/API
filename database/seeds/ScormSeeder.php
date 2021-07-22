<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Courses\Models\Lesson;
use EscolaLms\Courses\Models\Topic;
use EscolaLms\HeadlessH5P\Models\H5PContent;
use EscolaLms\Courses\Models\TopicContent\H5P;
use Illuminate\Http\UploadedFile;
use App\Library\ScormHelper;
use Peopleaps\Scorm\Model\ScormModel;

class ScormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $helper;

    public function __construct()
    {
        $this->helper =  new ScormHelper();
    }

    private function fromZip($filePath)
    {
        $fullFilePath = __DIR__.'/scorm/'.$filePath;
        $fullTmpPath = __DIR__.'/scorm/tmp.zip';

        copy($fullFilePath, $fullTmpPath);
        $file =  new UploadedFile($fullTmpPath, basename($filePath), 'application/zip', null, true);

        try {
            $scorm = $this->helper->uploadScormArchive($file);
        } catch (\Exception $err) {
            echo $err->getMessage();
        } finally {
            if (is_file($fullTmpPath)) {
                unlink($fullTmpPath);
            }
        }

        return $scorm;
    }

    private function scormToCourse($filePath)
    {
        $scorm = $this->fromZip($filePath);
        $model = ScormModel::firstWhere('origin_file', $scorm['name']);
        $course = Course::factory()->create(['base_price'=>0, 'scorm_id'=>$model->id]);
        return $course;
    }

    public function run()
    {
        $this->scormToCourse('employee-health-and-wellness-sample-course-scorm12-Z_legM6C.zip');
        $this->scormToCourse('RuntimeBasicCalls_SCORM12.zip');
        $this->scormToCourse('SL360_LMS_SCORM_1_2.zip');
    }
}
