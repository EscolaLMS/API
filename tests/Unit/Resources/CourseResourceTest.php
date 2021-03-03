<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\CoursesResource;
use App\Models\Course;
use App\ValueObjects\CourseContent;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseResourceTest extends TestCase
{
    public function test_if_details_has_exactly_same_keys_as_listing(): void
    {
        $course = factory(Course::class)->make();

        $listingRes = (new CoursesResource(new Collection([$course])))->toArray(null);
        $singleRes = (new CourseResource(CourseContent::make($course)))->toArray(null);


        foreach ($listingRes['data'][0] as $requiredKey => $requiredValue) {
            $this->assertArrayHasKey($requiredKey, $singleRes, $requiredKey . ' not exists - add value to CourseResource');
            $this->assertEquals($requiredValue, $singleRes[$requiredKey], $requiredKey . ' is not equal - add value to CourseResource');
        }
    }
}
