<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enum\InstructionLevel;
use App\Enum\MediaType;
use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getHtmlSink')) {
    function getHtmlSink()
    {
        return "
     <p>Woodlands payment <b>Osgiliath tightening</b>. Barad-dur follow belly comforts tender tough bell? Many that live deserve death. Some that die deserve life. Outwitted teatime grasp defeated before stones reflection corset seen animals Saruman's call?</p>
     <p>Ligulas step drops both? You shall not pass! <strong>Tender respectable</strong> success Valar impressive unfriendly bloom scraped? Branch hey-diddle-diddle pony trouble'll sleeping during jump Narsil.</p>
     <p>Curse you and all the halflings! Deserted anytime <em>Lake-town burned caves balls.</em>L Smoked lthilien forbids Thrain?</p>
     <p>Ravens wonder wanted runs me crawl gaining lots faster! Khazad-dum surprise baby season ranks. I bid you all a very fond farewell.</p>
     <p>Delay freezes Gollum. <u>Let the Ring-bearer decide.</u> Bagshot Row chokes pole pauses immediately orders taught éored musing three-day? Disease rune repel source fire Goblinses already?</p>
     <p>What about second breakfast? Nags runt near Lindir lock discover<span style='text-decoration: underline;'>uuuu underline</span> level? Andûril breathe waited flatten union.</p>
     <p>I think we should get off the road. Penalty sight splintered Misty Mountain mithril? Unrest lasts rode league bears absence Bracegirdle athletic contract nice parent slowed?</p>
";
    }

    function createVideo($courseId)
    {
        $dir = storage_path('app/public/course/' . $courseId);
        $fakeName = "raw_division-" . rand(100000, 900000);

        $copyfrom = database_path('seeds/multimedia');


        copy($copyfrom . '/video.mp4', $dir . '/' . $fakeName . '.mp4');
        Storage::putFile("course/$courseId", "$copyfrom/image.jpg", 'public');

        $videoId = DB::table('course_videos')->insertGetId([
            'video_title' => $fakeName,
            'video_name' => 'division.js.mp4',
            'video_type' => 'mp4',
            'duration' => '00:00:04',
            'image_name' => $fakeName . '.jpg',
            'video_tag' => 'curriculum',
            'uploader_id' => 2,
            'course_id' => $courseId,
            'processed' => 1
        ]);

        return $videoId;
    }

    function createFile($courseId, $type = 'mp3')
    {
        $dir = storage_path('app/public/course/' . $courseId);
        $copyfrom = database_path('seeds/multimedia');

        $fakeName = "raw_division-" . rand(100000, 900000);
        $file_tag = 'curriculum';
        $duration = null;
        $file_name = $fakeName;

        switch ($type) {
            case "mp3":
                copy($copyfrom . '/audio.mp3', $dir . '/' . $fakeName . '.mp3');
                $duration = '00:02:49';
                break;
            case "pdf":
                copy($copyfrom . '/document.pdf', $dir . '/' . $fakeName . '.pdf');
                $file_tag = 'curriculum_resource';
                break;
            case "link":
                $file_tag = 'curriculum_resource_link';
                $file_name = 'http://google.com';
                $fakeName = 'Google';
                break;
            default:
                return "type not recocnized";
        }

        $fileId = DB::table('course_files')->insertGetId([
            'file_title' => $fakeName,
            'file_name' => $file_name,
            'file_type' => $type,
            'file_extension' => $type,
            'file_size' => 4113874,
            'duration' => $duration,
            'file_tag' => $file_tag,
            'uploader_id' => 2,
            'course_id' => $courseId,
            'processed' => 1
        ]);

        return $fileId;
    }

    function getMDSink()
    {
        return "# Villo bella nam

        ## Dolorque quo ubi telum pro estque mortale

        Lorem markdownum ferae Troianae moenia celeri ille sine est Lacon idem viro
        [esse](http://www.mea.net/sedescolorem.html) adspice. Motusque sociamque nec
        terra roganti cunctae, **parentis flectimur femina**?

        1. Ligavit gentes
        2. Templa in nunc sucos coniunx Dicta inportunusque
        3. Vocem Erecthida mihi ipse mente grandine somnum
        4. Dum tamen quam saxo veteres propinquos nivibus
        5. Referre toto orbem auxiliumque odiumque quaerit

        Inposito ulla tantumque sinus: nec animis nubila. Senior ille pugnando mortis ad
        arbore tangat. Alti est nemorosam colebatur credidit cultros quodsi sermone et
        vias modo mutavit, nec. Non erat Licha placetque in consorti fugaverat hactenus.

        > Modo secus, illi fuit crines, iam agros victores **succendit** iuvenis
        > inbellemque aquae, o. Invicti deus ullo carinas opportuna coniectum *forte*,
        > visa ignes! Loquendi in intres? Temptabat patet de **quod omnia**, in arce et
        > hanc, Ionium quoque at vel natos *valvis*? Regna siccam levor sustulerat tamen
        > et, ad quod venata genetrix aestus: ab tristes te metus.

        ## Nam pulchroque lentisciferumque dicenda concrescere aenum adiutrixque

        Coniuge grandaevus mihi pulveris minus auras, plebe sibi posse, videbam adest.
        Obit inminet non: a trahit dea duces dici
        [diu](http://www.petunt-aestuque.io/assyrii) piosque. Tantus depositae hortis
        tamen, deus hic quam incessere capillos et elisi confinia subest patriisque
        tradere. Erat erat Hippodamen violave tristis Talibus fluit vocassent deieci.
        Iusta ante dicta, nec aurem.

        > Est conplexibus, pars vel iamdudum; spargere ego, levat, Phlegraeis. Dixit sua
        > florem effudit [sorores](http://estfortis.org/aetheredux.html), Ascalaphus,
        > conplexibus ipsaque.

        Hic et serisque crabronum utilitas domat, cum armis illa nec venabula minus,
        pueri iaces Albula qua sic Sedit? Solantia increpor at quadripedis optas
        silentum si audaci retemptat tenebat silentia promissi. Et subito dubites
        favilla: cum quas, ara patrias taurorum facies. Iuppiter hac possit coeunt?

        Qua deus quocumque fateri habet faveas, hos Neptune pigra adest praeposito vates
        tantique. Aures ora [tamen](http://decutitdabant.io/) negat erat lucosque
        creverat lumina, nec vera.";
    }
}

$factory->define(Course::class, function (Faker $faker) {
    $name = $faker->name;
    $price = $faker->randomDigit;

    return [
        'category_id' => Category::select('id')->inRandomOrder()->first()->getKey(),
        'instructor_id' => Instructor::first()->getKey(),
        'instruction_level_id' => InstructionLevel::COMPREHENSIVE,
        'course_title' => $name,
        'course_slug' => str_slug($name, '-'),
        'keywords' => $faker->words(5, true),
        'overview' => '<p>' . $faker->sentence . '</p>',
        'duration' => $faker->randomNumber . ' days',
        'price' => $price,
        'strike_out_price' => $price * 1.5,
        'is_active' => true,
    ];
});


$factory->afterCreating(Course::class, function ($course, $faker) {
    $id = $course->id;

    $dirCourse = storage_path('app/public/course/' . $id);
    $copyfrom = database_path('seeds/multimedia');

    if (!is_dir($dirCourse) && !file_exists($dirCourse)) {
        mkdir($dirCourse, 0700, true);
    }

    $course_image = Storage::putFile($dirCourse, "$copyfrom/image.jpg", 'public');
    $thumb_image = Storage::putFile($dirCourse, "$copyfrom/image.jpg", 'public');

    $sectionImage = Storage::putFile('section', "$copyfrom/image.jpg", 'public');
    $lectureImage = Storage::putFile('lecture', "$copyfrom/image.jpg", 'public');
    $course->update([
        'course_image' => $course_image,
        'thumb_image' => $thumb_image,
    ]);

    $videoId = createVideo($id);
    $audioId = createFile($id, 'mp3');
    $pdfId = createFile($id, 'pdf');
    $pdf2Id = createFile($id, 'pdf');
    $linkId = createFile($id, 'link');

    // attach sections

    for ($i = 0; $i < rand(1, 4); $i++) {
        $course->sections()->create([
            'title' => $faker->word,
            'sort_order' => $i,
            'image_path' => $sectionImage
        ]);
    }

    $i = 0;

    // $randomTags = Tag::select('id')->inRandomOrder()->take(rand(1, 4))->get();//->pluck('id');

    // $course->tags()->saveMany($randomTags);

    foreach ($course->sections()->get() as $section) {
        $videoId = createVideo($id);
        $section->lectures()->createMany([
            [
                'media_type' => MediaType::VIDEO,
                'media' => $videoId,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'sort_order' => $i++,
                'publish' => true,
                'image_path' => $lectureImage,
                'resources' => [$pdfId, $pdf2Id, $linkId]
            ],
            [
                'media_type' => MediaType::AUDIO,
                'media' => $audioId,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'sort_order' => $i++,
                'publish' => true,
                'image_path' => $lectureImage,
                'resources' => [$pdfId]
            ],
            [
                'media_type' => MediaType::TEXT,
                'contenttext' => getMDSink(),
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'sort_order' => $i++,
                'publish' => true,
                'image_path' => $lectureImage,
                'resources' => [$pdfId],
            ]
        ]);
    }
});
