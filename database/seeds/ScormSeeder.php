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
            $this->helper->uploadScormArchive($file);
        } catch (\Exception $err) {
            echo $err->getMessage();
        } finally {
            if (is_file($fullTmpPath)) {
                unlink($fullTmpPath);
            }
        }
    }

    public function run()
    {
        $courses = Course::with('lessons')->get();

        $this->fromZip('employee-health-and-wellness-sample-course-scorm12-Z_legM6C.zip');
        $this->fromZip('RuntimeBasicCalls_SCORM12.zip');
    }
}
