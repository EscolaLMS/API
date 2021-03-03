<?php

namespace Database\Seeders;

use App\Models\User;
use EscolaSoft\LaravelH5p\Events\H5pEvent;
use EscolaSoft\LaravelH5p\Exceptions\H5PException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

//use H5pCore;

class H5PContentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('h5p_contents')->truncate();

        $libSeeder = App::make('Database\Seeders\H5PLibrarySeeder');

        // Accordion
        // $libSeeder->downloadAndSeed('accordion-6-7138.h5p');
        // $this->seed('H5P.Accordion 1.0', file_get_contents(__dir__.'/h5p/accordion.json'));

        // ArithmeticQuiz
        // $libSeeder->downloadAndSeed('arithmetic-quiz-22-57860.h5p');
        $this->seed('H5P.ArithmeticQuiz 1.1', file_get_contents(__dir__.'/h5p/arithmetic_quiz.json'));

        // Audio
        //$libSeeder->downloadAndSeed('audio-27-68308.h5p');
        // $libSeeder->downloadAndSeed('dialog-cards-620.h5p'); // audio from dialog cards seems to be different
        // copy(__dir__.'/multimedia/audio.mp3', storage_path("app/public/h5p/content/{$id}/audios/files-5fe9dbfa6a025.mp3"));
        // $this->seed('H5P.Audio 1.4', file_get_contents(__dir__.'/h5p/audio.json'));

        // Fill the blanks
        // $libSeeder->downloadAndSeed('fill-in-the-blanks-837.h5p');
        $this->seed('H5P.Blanks 1.12', file_get_contents(__dir__.'/h5p/blanks.json'));

        // Dialog Cards
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/image-5fedd526b1364.jpg"));
        // copy(__dir__.'/multimedia/audio.mp3', storage_path("app/public/h5p/content/{$id}/audios/audio-5fedd5358d2e9.mp3"));
        // // $libSeeder->downloadAndSeed('dialog-cards-620.h5p');
        // $this->seed('H5P.Dialogcards 1.8', file_get_contents(__dir__.'/h5p/dialog_cards.json'));

        // Drag & Drop

        // $libSeeder->downloadAndSeed('drag-and-drop-712.h5p');
        $id = $this->seed('H5P.DragQuestion 1.13', file_get_contents(__dir__.'/h5p/drag_drop.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/background-5fedd8eddcb4a.jpg"));
        copy(__dir__.'/multimedia/logo.png', storage_path("app/public/h5p/content/{$id}/images/file-5fedd9685d78f.png"));


        // Drag Text
        // $libSeeder->downloadAndSeed('drag-the-words-1399.h5p');
        $this->seed('H5P.DragText 1.8', file_get_contents(__dir__.'/h5p/drag_text.json'));

        // Guess the anwser
        // $libSeeder->downloadAndSeed('guess-the-answer-2402.h5p');
        // Guess The Answer image

        $id = $this->seed('H5P.GuessTheAnswer 1.4', file_get_contents(__dir__.'/h5p/guess_image.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/file-5fede30200ec8.jpg"));

        // Guess The Answer video
        $id = $this->seed('H5P.GuessTheAnswer 1.4', file_get_contents(__dir__.'/h5p/guess_video.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/videos/"), 0777, true);
        copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/sources-5fede3fc44c5d.mp4"));

        // Find the hotspot
        // $libSeeder->downloadAndSeed('image-hotspots-2-825.h5p');
        $id = $this->seed('H5P.ImageHotspotQuestion 1.8', file_get_contents(__dir__.'/h5p/image_hotspot.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/backgroundImage-5fede571b2412.jpg"));

        // Image hotspots
        // $id = $this->seed('H5P.ImageHotspots 1.9', file_get_contents(__dir__.'/h5p/image_hotspots.json'));
        // @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        // @mkdir(storage_path("app/public/h5p/content/{$id}/videos/"), 0777, true);
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/image-5fede7a562f20.jpg"));
        // copy(__dir__.'/multimedia/logo.png', storage_path("app/public/h5p/content/{$id}/images/file-5fede805a8b59.png"));
        // copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/sources-5fede7de1aecc.mp4"));

        // Multiple hotspots
        //$libSeeder->downloadAndSeed('image-multiple-hotspot-question-65081.h5p');
        $id = $this->seed('H5P.ImageMultipleHotspotQuestion 1.0', file_get_contents(__dir__.'/h5p/multiple_hotspots.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/backgroundImage-5fedec3e59475.jpg"));

        // Image pair
        //$libSeeder->downloadAndSeed('image-multiple-hotspot-question-65081.h5p');

        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/image-5fedee23775a4.jpg"));
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/match-5fedee26d87d3.jpg"));
        // copy(__dir__.'/multimedia/logo.png', storage_path("app/public/h5p/content/{$id}/images/image-5fedee3526b22.png"));
        // copy(__dir__.'/multimedia/logo.png', storage_path("app/public/h5p/content/{$id}/images/match-5fedee3a6c333.png"));
        // $this->seed('H5P.ImagePair 1.4', file_get_contents(__dir__.'/h5p/image_pair.json'));

        // Image Sequencing
        //$libSeeder->downloadAndSeed('image-sequencing-3-110117.h5p');

        $id = $this->seed('H5P.ImageSequencing 1.1', file_get_contents(__dir__.'/h5p/image_seq.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        @mkdir(storage_path("app/public/h5p/content/{$id}/audios/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/image-5fedf0396c297.jpg"));
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/image-5fedf059d818c.jpg"));
        copy(__dir__.'/multimedia/logo.png', storage_path("app/public/h5p/content/{$id}/images/image-5fedf0499bba7.png"));
        copy(__dir__.'/multimedia/audio.mp3', storage_path("app/public/h5p/content/{$id}/audios/audio-5fedf04513dd0.mp3"));
        copy(__dir__.'/multimedia/audio.mp3', storage_path("app/public/h5p/content/{$id}/audios/audio-5fedf0547fc52.mp3"));


        // Interactive video
        //$libSeeder->downloadAndSeed('interactive-video-2-618.h5p');
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/poster-5fedf22ae4094.jpg"));
        // copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/files-5fedf21bde878.mp4"));
        // $this->seed('H5P.InteractiveVideo 1.22', file_get_contents(__dir__.'/h5p/interactive_video.json'));

        // Mark the words
        //$libSeeder->downloadAndSeed('mark-the-words-2-1408.h5p');
        // $this->seed('H5P.MarkTheWords 1.9', file_get_contents(__dir__.'/h5p/mark_words.json'));

        // Multiple choice single/multiple
        //$libSeeder->downloadAndSeed('multiple-choice-713.h5p');
        // single

        $id = $this->seed('H5P.MultiChoice 1.14', file_get_contents(__dir__.'/h5p/multiple_choice.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/videos/"), 0777, true);
        copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/sources-5fedf705ebaae.mp4"));

        // multiple
        $id = $this->seed('H5P.MultiChoice 1.14', file_get_contents(__dir__.'/h5p/multiple_choice2.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/file-5fedf54f65320.jpg"));


        // Personality
        //$libSeeder->downloadAndSeed('personality-quiz-21254.h5p');

        // $id = $this->seed('H5P.PersonalityQuiz 1.0', file_get_contents(__dir__.'/h5p/personality.json'));
        // @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/file-5fedf8f23917a.jpg"));
        // copy(__dir__.'/multimedia/logo.png', storage_path("app/public/h5p/content/{$id}/images/file-5fedf90d1e49f.png"));


        // Questionare
        //$libSeeder->downloadAndSeed('questionnaire-4-30615.h5p');
        $id = $this->seed('H5P.Questionnaire 1.3', file_get_contents(__dir__.'/h5p/questionare.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/file-5fedfa61c7428.jpg"));


        // library: H5P.QuestionSet 1.17
        $id = $this->seed('H5P.QuestionSet 1.17', file_get_contents(__dir__.'/h5p/question_set.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/images/"), 0777, true);
        @mkdir(storage_path("app/public/h5p/content/{$id}/videos/"), 0777, true);
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/backgroundImage-5fedfba4db92d.jpg"));
        copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/backgroundImage-5fedfba85a1f6.jpg"));
        copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/successVideo-5fedfc23b1b96.mp4"));
        copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/failVideo-5fedfc2a721ef.mp4"));



        // H5P.SingleChoiceSet 1.11
        $id = $this->seed('H5P.SingleChoiceSet 1.11', file_get_contents(__dir__.'/h5p/single_choice.json'));

        // H5P.Summary 1.10
        // $id = $this->seed('H5P.Summary 1.10', file_get_contents(__dir__.'/h5p/summary.json'));

        // H5P.Timeline 1.1
        // thumbnail-5fedff3700b38.jpg
        // backgroundImage-5fedfebd9310d.jpg
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/thumbnail-5fedff3700b38.jpg"));
        // copy(__dir__.'/multimedia/image.jpg', storage_path("app/public/h5p/content/{$id}/images/backgroundImage-5fedfebd9310d.jpg"));
        // $this->seed('H5P.Timeline 1.1', file_get_contents(__dir__.'/h5p/timeline.json'));

        // H5P.TrueFalse 1.6
        $id = $this->seed('H5P.TrueFalse 1.6', file_get_contents(__dir__.'/h5p/true_false.json'));
        @mkdir(storage_path("app/public/h5p/content/{$id}/videos/"), 0777, true);
        copy(__dir__.'/multimedia/video.mp4', storage_path("app/public/h5p/content/{$id}/videos/sources-5fee002309365.mp4"));
    }

    private function seed($library, $parameters)
    {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        $user = User::inRandomOrder()->first();

        $oldLibrary = null;
        $oldParams = null;
        $event_type = 'create';
        $content = [
            'disable'    => false,
            'user_id'    => $user->id,
            'embed_type' => 'div',
            'filtered'   => '',
            'slug'       => config('laravel-h5p.slug'),
        ];

        $content['filtered'] = '';
        $content['library'] = $core->libraryFromString($library);
        if (!$content['library']) {
            throw new H5PException('Invalid library.');
        }

        // Check if library exists.
        $content['library']['libraryId'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
        if (!$content['library']['libraryId']) {
            throw new H5PException('No such library');
        }

        //new
        $params = json_decode($parameters);
        if (!empty($params->metadata) && !empty($params->metadata->title)) {
            $content['title'] = $params->metadata->title;
        } else {
            $content['title'] = '';
        }
        $content['params'] = json_encode($params->params);
        if ($params === null) {
            throw new H5PException('Invalid parameters');
        }

        $set = [
            \H5PCore::DISPLAY_OPTION_FRAME     => filter_input(INPUT_POST, 'frame', FILTER_VALIDATE_BOOLEAN),
            \H5PCore::DISPLAY_OPTION_DOWNLOAD  => filter_input(INPUT_POST, 'download', FILTER_VALIDATE_BOOLEAN),
            \H5PCore::DISPLAY_OPTION_EMBED     => filter_input(INPUT_POST, 'embed', FILTER_VALIDATE_BOOLEAN),
            \H5PCore::DISPLAY_OPTION_COPYRIGHT => filter_input(INPUT_POST, 'copyright', FILTER_VALIDATE_BOOLEAN),
        ];
        $content['disable'] = $core->getStorableDisplayOptions($set, $content['disable']);

        // Save new content
        $content['id'] = $core->saveContent($content);

        // Move images and find all content dependencies
        $editor->processParameters($content['id'], $content['library'], $params, $oldLibrary, $oldParams);

        event(new H5pEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return $content['id'];
    }
}
