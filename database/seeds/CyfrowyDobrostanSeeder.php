<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Courses\Models\Lesson;
use EscolaLms\Courses\Models\Topic;
use EscolaLms\Courses\Models\TopicContent\RichText;
use EscolaLms\Courses\Models\TopicContent\Video;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CyfrowyDobrostanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->downloadVideos(); 

        $dest = 'course/cyfrowy_dobrostan/wojewodzic.png';
        if (!is_dir(dirname(($dest)))) {
            mkdir(dirname(($dest)), 0777, true);
        }

        copy(__DIR__ . '/cyfrowy_dobrostan/images/wojewodzic.png', storage_path('app/public/' . $dest));

        $user = User::firstOrCreate([
            'first_name' => 'Krzysztof',
            'last_name' => 'Wojewodzic',
            'email' => 'krzysztof@escola-lms.com',
            'is_active' => true,
            'email_verified_at' => '2021-09-02 15:44:50',
            'path_avatar' => $dest,
            'bio' => "Dr Krzysztof Wojewodzic jest multiprzedsiębiorcą. Założył odnoszące sukcesy firmy, takie jak Lerni.us lub Funmedia ltd. i Escola Ltd., których wartość opiewa na miliony złotych. Specjalizacje dr Wojewodzica dotyczą zarządzania strategicznego, z naciskiem na przełomowe technologie oraz modeli biznesowych. Dr Wojewodzic jest również ekspertem w Narodowym Centrum Badań i Rozwoju oraz międzynarodowym mówcą na temat mobilnego uczenia się. W AVISTA odpowiada za rozwój nowych technologii biznesowych (mobile, ICT, e-learning)."
        ]);

        $user->password = Hash::make('password');
        $user->save();

        $dest = 'course/cyfrowy_dobrostan/cyfrowy_dobrostan.jpg';
        if (!is_dir(dirname(($dest)))) {
            mkdir(dirname(($dest)), 0777, true);
        }

        copy(__DIR__ . '/cyfrowy_dobrostan/images/cyfrowy_dobrostan.jpeg', storage_path('app/public/' . $dest));

        $course = Course::create([
            'title' => 'Cyfrowy Dobrostan',
            'summary' =>
            <<<MD
## Czym jest Cyfrowy Dobrostan

* Korzystanie z telefonu świadomie i wtedy kiedy Ty masz na to ochotę.
* Stan komfortu i zadowolenia z obecności technologii w życiu codziennym
* Zrozumienie jak z pomocą technologii można manipulować naszymi zachowaniami

## Dla kogo jest kurs

* dla ludzi, którzy chcą mniej lub mądrzej korzystać z technologii,
* dla zmęczonych ciągłymi powiadomieniami i wszechobecnym autodtwarzaniem
* dla osób, które chcą mieć kontrolę nad technologią, bez uciekania od niej w Bieszczady
* dla rodziców, którzy chcą być uczciwym przykładem dla swoich dzieci
MD,
            'image_path' => $dest,
            'video_path' => 'course/cyfrowy_dobrostan/DppIjG7CkZiBWWRpFTEwAPdcMWPQRnxjTz1dnXyG.mp4',
            'base_price' => 4000,
            'author_id' => $user->id,
            'active' => 1,
            'subtitle' => 'Stan komfortu i zadowolenia z obecności technologii w życiu codziennym',
            'language' => 'pl',
            'duration' => '10-12 godzin',
            'description' =>  <<<MD
# O czym będzie ten kurs i jak wyciągnąć z niego jak najwięcej?

1. **Korzystasz z technologii tylko w sposób produktywny i tylko tyle ile na to zaplanowałeś?.**
2. **Nigdy nie sięgasz po telefon czy komputer, by potem żałować zmarnowanego czasu?**
3. **Nie włączasz elektroniki wieczorem lub w nocy i masz niezaburzony sen?**
4. **Czerpiesz radość z korzystania z elektroniki, a jej brak nie powoduje w Tobie niepokoju?**
5. **Twoje dzieci i Twoi bliscy korzystają z multimediów tylko dla dobrych celów i bez problemu odkładają telefony, kiedy spędzacie czas razem?**

Na wszystko odpowiedziałeś tak? Świetnie, przestań dalej angażować się w ten kurs. Nie jest on dla Ciebie. Wypełnij formularz zwrotu pieniędzy i czekaj na przelew.

Jeśli jednak chcesz poprawić to, jak korzystasz elektroniki, to czytaj dalej

Samo myślenie o tym ile czasu spędzamy na naszych urządzeniach sprawia, że czujemy się gorzej 1.

Stresujemy się nie tylko tym jak blisko (lub daleko) mamy do telefonu, ale również oczekiwaniami, jakie sobie wobec nich stawiamy:

* by być produktywni
* spędzać z telefonem konstruktywny czas
* by technologie nie psuły naszych relacji rzeczywistych.

Zadaj sobie pytanie, ile razy poczułeś się przyłapany lub przyłapana na zmarnowaniu czasu z telefonem. Przez innych, a może przez samego siebie, z poczuciem „gdzie jest moje 15-20-30 minut?”.

Pierwszym etapem będzie diagnoza, sprawdzenie ile i w jaki sposób faktycznie spędzamy czasu z telefonem lub komputerem, oraz co ważniejsze, co nas wciąga.

W kursie #cyfrowydobrostan nie zachęcamy Cię do tego byś negatywnie ocenił siebie, ale byś potrafił korzystać z technologii celowo i świadomie. I to niezależnie czy korzystasz w celach podniesienia produktywności, czy dla czystej przyjemności.
MD,
            'level' => 'dla każdego'
        ]);

        // INSERT INTO "lessons" ("id", "created_at", "updated_at", "title", "duration", "order", "course_id", "active", "summary") VALUES

        $lessons = include(__DIR__ . '/cyfrowy_dobrostan/lessons.php');
        $topics = include(__DIR__ . '/cyfrowy_dobrostan/topics.php');
        $texts = include(__DIR__ . '/cyfrowy_dobrostan/texts.php');
        $videos = include(__DIR__ . '/cyfrowy_dobrostan/videos.php');

        $old_keys = [
            'lessons' => [],
            'topics' => []
        ];

        foreach ($lessons as $lesson_id => $lesson) {
            $lessonObj = Lesson::create([
                "created_at" => $lesson[0],
                "updated_at" => $lesson[1],
                "title" => $lesson[2],
                "duration" => $lesson[3],
                "order" => $lesson[4],
                "course_id" => $course->id,
                "active" => $lesson[6],
                "summary" => $lesson[7] ?? "",
            ]);

            $old_keys['lessons'][$lesson_id] = $lessonObj->id;
        }

        // // INSERT INTO "topics" ("id", "created_at", "updated_at", "title", "lesson_id", "topicable_id", "topicable_type", "order", "active", "preview") VALUES

        foreach ($topics as $topic_id => $topic) {
            $topicObj = Topic::create([
                "created_at" => $topic[0],
                "updated_at" => $topic[1],
                "title" => $topic[2],
                "lesson_id" => $old_keys['lessons'][$topic[3]],
                "order" => $topic[6],
                "active" => $topic[7],
                "preview" => $topic[8],
            ]);

            switch ($topic[5]) {
                case "EscolaLms\Courses\Models\TopicContent\RichText":
                    $text = $texts[$topic[4]];
                    //     INSERT INTO "topic_richtexts" ("id", "created_at", "updated_at", "value") VALUES

                    $richText = RichText::create([
                        "value" => $text[2]
                    ]);
                    $topicObj->topicable()->associate($richText)->save();
                    break;
                case "EscolaLms\Courses\Models\TopicContent\Video":

                    // INSERT INTO "topic_videos" ("id", "created_at", "updated_at", "value", "poster", "width", "height") VALUES
                    $video = $videos[$topic[4]];

                    $filename = basename($video[2]);
                    $path = __DIR__ . '/cyfrowy_dobrostan/videos/';
                    $dest = 'course/cyfrowy_dobrostan/' . $filename;
                    if (!is_dir(dirname(($dest)))) {
                        mkdir(dirname(($dest)), 0777, true);
                    }
                    if (!is_file(storage_path('app/public/' . $dest))) {
                        copy($path . $filename, storage_path('app/public/' . $dest));
                    }

                    $video = $videos[$topic[4]];
                    $videoObj = Video::create([
                        "value" => $dest,
                        "poster" => $video[3],
                        "width" => $video[4],
                        "height" => $video[5],
                    ]);
                    $topicObj->topicable()->associate($videoObj)->save();
                    break;
            }

            $old_keys['topics'][$topic_id] = $topicObj->id;
        }
    }

    private function downloadVideos()
    {
        $path = __DIR__ . '/cyfrowy_dobrostan/videos/';
        // INSERT INTO "topic_videos" ("id", "created_at", "updated_at", "value", "poster", "width", "height") VALUES

        $videos = include(__DIR__ . '/cyfrowy_dobrostan/videos.php');

        foreach ($videos as $video) {
            $filename = basename($video[2]);
            if (is_file($path . $filename)) {
                continue;
            } else {
                $url = "https://escola-lms-api.stage.etd24.pl/storage/" . $video[2];
                echo 'downloading ' . $url . " \n ";
                copy($url, $path . $filename);
            }
        }
    }
}
