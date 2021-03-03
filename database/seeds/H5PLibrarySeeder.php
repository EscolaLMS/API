<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class H5PLibrarySeeder extends Seeder
{
    public function downloadAndSeed($lib)
    {
        // eg https://h5p.org/sites/default/files/h5p/exports/interactive-video-2-618.h5p
        $url = "https://h5p.org/sites/default/files/h5p/exports/$lib";
        $file_name = basename($url);

        $h5p = App::make('LaravelH5p');
        $validator = $h5p::$validator;
        $interface = $h5p::$interface;

        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name

        $filename = realpath(__dir__."/../../storage/h5p/temp")."/$lib";

        if (!is_file($filename)) {
            if (file_put_contents($filename, file_get_contents($url))) {
                echo "File downloaded from $url \n";
            } else {
                echo "File downloading failed. \n";
                return;
            }
        }

        copy($filename, $interface->getUploadedH5pPath());

        // Content update is skipped because it is new registration
        $content = null;
        $skipContent = true;
        $h5p_upgrade_only = false;

        if ($validator->isValidPackage($skipContent, $h5p_upgrade_only)) {
            $storage = $h5p::$storage;
            $storage->savePackage($content, null, $skipContent);
        } else {
            echo "Invalid package $filename \n";
        }

        @unlink($interface->getUploadedH5pPath());

        return true;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         *
         */

        $libs = [
            // "accordion-6-7138.h5p",
            "audio-27-68308.h5p",
            "arithmetic-quiz-22-57860.h5p",
            // "dialog-cards-620.h5p",
            "drag-and-drop-712.h5p",
            "drag-the-words-1399.h5p",
            // "essay-4-166755.h5p",
            "example-content-image-pairing-2-233382.h5p",
            "fill-in-the-blanks-837.h5p",
            "find-the-hotspot-3024.h5p",
            // "flashcards-51-111820.h5p",
            "guess-the-answer-2402.h5p",
            "image-hotspots-2-825.h5p",
            "image-multiple-hotspot-question-65081.h5p",
            // "interactive-video-2-618.h5p",
            "mark-the-words-2-1408.h5p",
            "multiple-choice-713.h5p",
            // "personality-quiz-21254.h5p",
            "question-set-616.h5p",
            "questionnaire-4-30615.h5p",
            "single-choice-set-1515.h5p",
            // "timeline-3-716.h5p",
            "true-false-question-34806.h5p",
            "image-sequencing-3-110117.h5p"
        ];

        foreach ($libs as $lib) {
            echo "seeding $lib \n";
            $this->downloadAndSeed($lib);
        }
    }
}
