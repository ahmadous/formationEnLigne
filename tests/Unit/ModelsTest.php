<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_course_can_be_created_with_factory()
    {
        $course = Course::factory()->create();

        $this->assertInstanceOf(Course::class, $course);
        $this->assertNotNull($course->title);
        $this->assertNotNull($course->description);
        $this->assertNotNull($course->user_id);
    }

    /** @test */
    public function a_course_has_fillable_attributes()
    {
        $course = new Course();
        $fillable = ['title', 'description', 'category_id'];

        $this->assertEquals($fillable, $course->getFillable());
    }

    /** @test */
    public function a_course_has_many_episodes()
    {
        $course = Course::factory()->create();
        $episode = Episode::factory()->create(['course_id' => $course->id]);

        $this->assertTrue($course->episodes->contains($episode));
        $this->assertEquals(1, $course->episodes->count());
    }

    /** @test */
    public function a_course_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $course->user);
        $this->assertEquals($user->id, $course->user->id);
    }

    /** @test */
    public function an_episode_can_be_created_with_factory()
    {
        $course = Course::factory()->create();
        $episode = Episode::factory()->create(['course_id' => $course->id]);

        $this->assertInstanceOf(Episode::class, $episode);
        $this->assertNotNull($episode->title);
        $this->assertNotNull($episode->description);
        $this->assertNotNull($episode->video_url);
        $this->assertNotNull($episode->course_id);
    }

    /** @test */
    public function an_episode_has_fillable_attributes()
    {
        $episode = new Episode();
        $fillable = ['title', 'description', 'video_url', 'course_id'];

        $this->assertEquals($fillable, $episode->getFillable());
    }

    /** @test */
    public function an_episode_belongs_to_a_course()
    {
        $course = Course::factory()->create();
        $episode = Episode::factory()->create(['course_id' => $course->id]);

        $this->assertInstanceOf(Course::class, $episode->course);
        $this->assertEquals($course->id, $episode->course->id);
    }

    /** @test */
    public function course_counts_episodes_correctly()
    {
        $course = Course::factory()->create();
        Episode::factory()->count(5)->create(['course_id' => $course->id]);

        $this->assertEquals(5, $course->episodes()->count());
    }

    /** @test */
    public function course_has_permission_attribute()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);

        $this->assertArrayHasKey('permission', $course->toArray());
    }

    /** @test */
    public function multiple_episodes_can_be_created_for_a_course()
    {
        $course = Course::factory()->create();
        $episodes = Episode::factory()->count(3)->create(['course_id' => $course->id]);

        $this->assertCount(3, $course->episodes);
    }

    /** @test */
    public function owner_can_update_course_via_policy()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('update', $course));
    }
}

