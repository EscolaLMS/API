<?php

use App\Enum\InstructionLevel;
use App\Models\Course;
use EscolaLms\Categories\Models\Category;
use App\Models\CurriculumLecturesQuiz;
use App\Models\CurriculumSection;
use App\Models\Instructor;
use App\Models\Tag;
use App\Models\User;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Features\Traits\Assertable;
use Features\Traits\CreateCourses;
use Features\Traits\CreateUsers;
use Illuminate\Testing\TestResponse;
use Laracasts\Behat\Context\LaravelContext;
use Tests\MakeServices;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends LaravelContext implements Context
{
    use CreateUsers, CreateCourses, MakeServices;

    private User $user;
    private Course $course;
    private array $sections;
    private array $lectures;

    protected TestResponse $response;

    /**
     * @Given there is :email instructor user
     */
    public function thereIsInstructorUser($email)
    {
        $this->user = $this->createUserWithRole([
            'email' => $email
        ], 'instructor');

        factory(Instructor::class)->create([
            'user_id' => $this->user->getKey()
        ]);
    }

    /**
     * @Given there is :email admin user
     */
    public function thereIsAdminUser($email)
    {
        $this->user = $this->createUserWithRole([
            'email' => $email
        ], 'admin');
    }

    /**
     * @Given there is :courseName course created by :email user
     */
    public function thereIsCourseCreatedByUser($courseName, $email)
    {
        $this->course = factory(Course::class)->create([
            'instructor_id' => $this->findInstructorByEmail($email)->getKey(),
            'course_title' => $courseName,
            'course_slug' => str_slug($courseName, '-')
        ]);
    }

    /**
     * @When user is on :url page
     */
    public function userIsOnPage($url)
    {
        $this->response = $this->actingAs($this->user)->get($url);
    }

    /**
     * @When anonymous is on :url page
     */
    public function anonymousIsOnPage($url)
    {
        $this->response = $this->get($url);
    }

    /**
     * @When user deletes created course using :url page
     */
    public function userDeletesCreatedCourseUsingPage($url)
    {
        $this->response = $this->actingAs($this->user)->get($url . '/' . $this->course->getKey());
    }

    /**
     * @Then response will have :code code
     */
    public function responseWillHaveCode($code)
    {
        $this->response->assertStatus((int)$code);
    }

    /**
     * @Then user is able to see :courseName course with :slug slug
     */
    public function userIsAbleToSeeCourseWithSlug($courseName, $slug)
    {
        $this->response->assertSee($courseName);
        $this->response->assertSee($slug);
    }

    /**
     * @Then user is not able to see :courseName course with :slug slug
     */
    public function userIsNotAbleToSeeCourseWithSlug($courseName, $slug)
    {
        $this->response->assertDontSee($courseName);
        $this->response->assertDontSee($slug);
    }

    /**
     * @Then course with :slug slug will be present in database
     */
    public function courseWithSlugWillBePresentInDatabase($slug)
    {
        $this->assertDatabaseHas('courses', [
            'course_slug' => $slug
        ]);
    }

    /**
     * @Then course with :slug slug will be not present in database
     */
    public function courseWithSlugWillBeNotPresentInDatabase($slug)
    {
        $this->assertDatabaseMissing('courses', [
            'course_slug' => $slug
        ]);
    }

    /**
     * @Given new course with name :courseName, category :category, and instruction level :level will be added
     */
    public function newCourseWithNameCategoryAndInstructionLevelWillBeAdded($courseName, $category, $level)
    {
        $category = $this->createCategory($category);

        $this->response = $this->actingAs($this->user)->post('instructor-course-info-save', [
            'course_title' => $courseName,
            'category_id' => $category->getKey(),
            'instruction_level_id' => InstructionLevel::getValue($level)
        ]);
    }

    /**
     * @When user is on :courseName course edit page
     */
    public function userIsOnCourseEditPage($courseName)
    {
        $this->course = $this->findCourseByName($courseName);
        $this->userIsOnPage('instructor-course-info/' . $this->course->getKey());
    }

    /**
     * @When update course and set status to active
     */
    public function updateCourseWithNameAndSetStatusToActive()
    {
        $this->response = $this->actingAs($this->user)->put('instructor-course-info-update/' . $this->course->getKey(), [
            'status' => true
        ]);
    }

    /**
     * @Given there is :arg1 category created
     */
    public function thereIsCategoryCreated($name)
    {
        $category = $this->createCategory($name);
    }

    /**
     * @Then the :header header should be :value
     */
    public function theHeaderShouldBe($header, $value)
    {
        $this->response->assertHeader($header, $value);
    }

    /**
     * @Then the anonymous sees
     */
    public function theAnonymousSees(PyStringNode $string)
    {
        $this->response->assertSee(trim((string)$string->getRaw()), false);
    }

    /**
     * @Given there is :title tag created
     */
    public function thereIsTagCreated($title)
    {
        $tag = factory(Tag::class)->create(['title' => $title]);
    }

    /**
     * @Then all tags are unique
     */
    public function allTagsAreUnique()
    {
        $response = json_decode($this->response->getContent());
        $tags = $response->data;
        if (count($tags) !== count(array_unique($tags))) {
            throw new Exception('Array is not unique');
        }

        return true;
    }

    /**
     * @Given there is :arg1 tag created :arg2 times
     */
    public function thereIsTagCreatedTimes($title, $num)
    {
        for ($i = 0; $i < $num; $i++) {
            $tag = factory(Tag::class)->create(['title' => $title]);
        }
    }

    /**
     * @Given course contains following lectures
     */
    public function courseContainsFollowingLectures(TableNode $table)
    {
        $sections = [];
        $lectures = [];

        foreach ($table->getHash() as $row) {
            if (!array_key_exists($row['Section'], $sections)) {
                $sections[$row['Section']] = factory(CurriculumSection::class)->create([
                    'course_id' => $this->course->getKey(),
                    'title' => $row['Section'],
                    'sort_order' => $row['Section Order']
                ]);
            }

            $lectures[$row['Lecture']] = factory(CurriculumLecturesQuiz::class)->create([
                'section_id' => $sections[$row['Section']]->getKey(),
                'title' => $row['Lecture'],
                'sort_order' => $row['Lecture Order']
            ]);
        }

        $this->sections = $sections;
        $this->lectures = $lectures;
    }

    /**
     * @When user drag sections order into following positions
     */
    public function userDragSectionsOrderIntoFollowingPositions(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->sections[$row['Section']]->update([
                'sort_order' => $row['Section Order']
            ]);
        }
    }

    /**
     * @When user drag lectures order into following positions
     */
    public function userDragLecturesOrderIntoFollowingPositions(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->lectures[$row['Lecture']]->update([
                'sort_order' => $row['Lecture Order'],
                'section_id' => $this->sections[$row['Section']]->getKey()
            ]);
        }
    }

    /**
     * @Then lectures sort will be in following order
     */
    public function lecturesSortWillBeInFollowingOrder(TableNode $table)
    {
        $courseContent = $this->courseService()->getCourseContent($this->course);

        foreach ($table->getHash() as $row) {
            $section = $courseContent->getSections()->get($row["Section Key"]);
            $this->assertEquals($row['Section'], $section->title);
            $this->assertEquals($row['Lecture'], $courseContent->getLecturesQuizzes()[$section->getKey()][$row['Lecture Key']]->title);
        }
    }

    /**
    * @Given there is :arg1 course created
    */
    public function thereIsCourseCreated($title)
    {
        $course = $this->findCourseByName($title);
    }


    /**
     * @Given there is :arg1 category created with id :arg2
     */
    public function thereIsCategoryCreatedWithId($name, $id)
    {
        $category = Category::firstOrCreate([
            'name' => $name,
            'id' => $id
        ]);

        return $category;
    }

    /**
     * @Given there is :arg1 subcategory created with id :arg2 and parentid :arg3
     */
    public function thereIsSubcategoryCreatedWithIdAndParentid($name, $id, $parent_id)
    {
        $category = Category::firstOrCreate([
            'name' => $name,
            'parent_id' => $parent_id,
            'id' => $id
        ]);

        return $category;
    }

    /**
     * @Given course :arg1  has category id :arg2
     */
    public function courseHasCategoryId($name, $catId)
    {
        $course = Course::where('course_title', $name)->first();
        $course->update([
            'category_id' => $catId
        ]);
    }

    /**
     * @Given course :arg1  has Tag :arg2
     */
    public function courseHasTag($name, $tagName)
    {
        $course = Course::where('course_title', $name)->first();
        $course->tags()->create([
            'title' => $tagName
        ]);
    }
}
