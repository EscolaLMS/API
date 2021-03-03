<?php

namespace Tests\Unit\ValueObjects;

use App\Enum\MediaType;
use App\Models\CurriculumLecturesQuiz;
use App\Services\EscolaLMS\Media\AudioMedia;
use App\Services\EscolaLMS\Media\DocumentMedia;
use App\Services\EscolaLMS\Media\InteractiveMedia;
use App\Services\EscolaLMS\Media\Media;
use App\Services\EscolaLMS\Media\NullableMedia;
use App\Services\EscolaLMS\Media\PresentationMedia;
use App\Services\EscolaLMS\Media\TextMedia;
use App\Services\EscolaLMS\Media\VideoMedia;
use Tests\TestCase;

class MediaTest extends TestCase
{
    public function test_instantiate_media_with_text_type_without_media(): void
    {
        $lecture = factory(CurriculumLecturesQuiz::class)->make([
            'media_type' => MediaType::TEXT,
            'media' => null
        ]);

        $this->assertInstanceOf(TextMedia::class, Media::make($lecture));
    }

    /**
     * @dataProvider nullableMediaDataTypes
     */
    public function test_instantiate_media($instance, $type): void
    {
        $lecture = factory(CurriculumLecturesQuiz::class)->make([
            'media_type' => $type
        ]);

        $this->assertInstanceOf($instance, Media::make($lecture));
    }

    public function nullableMediaDataTypes(): array
    {
        return [
            [NullableMedia::class, null],
            [NullableMedia::class, ''],
            [NullableMedia::class, false],
            [NullableMedia::class, 'invalidMediaType'],
            [NullableMedia::class, '999999'],
            [VideoMedia::class, MediaType::VIDEO],
            [VideoMedia::class, (string)MediaType::VIDEO],
            [TextMedia::class, MediaType::TEXT],
            [TextMedia::class, (string)MediaType::TEXT],
            [AudioMedia::class, MediaType::AUDIO],
            [DocumentMedia::class, MediaType::DOCUMENT],
            [PresentationMedia::class, MediaType::PRESENTATION],
            [InteractiveMedia::class, MediaType::INTERACTIVE],
        ];
    }
}
