<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function courses_index_is_accessible_to_all_users()
    {
        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function courses_index_displays_courses()
    {
        Course::factory()->count(3)->create();

        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->has('courses'));
    }

    /** @test */
    public function course_show_is_accessible_to_all_users()
    {
        $course = Course::factory()->create();
        Episode::factory()->count(3)->create(['course_id' => $course->id]);

        $response = $this->get(route('courses.show', $course));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->has('course'));
    }

    /** @test */
    public function authenticated_users_can_access_create_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('courses.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_access_create_page()
    {
        $response = $this->get(route('courses.create'));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_create_course_with_episodes()
    {
        $user = User::factory()->create();
        $episodesData = [
            [
                'title' => 'Episode 1',
                'description' => 'First episode',
                'video_url' => 'https://video.com/1.mp4',
            ],
            [
                'title' => 'Episode 2',
                'description' => 'Second episode',
                'video_url' => 'https://video.com/2.mp4',
            ],
        ];

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'title' => 'My Course',
            'description' => 'Course description',
            'episodes' => $episodesData,
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'title' => 'My Course',
            'description' => 'Course description',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('episodes', [
            'title' => 'Episode 1',
            'course_id' => Course::where('title', 'My Course')->first()->id,
        ]);
        $this->assertDatabaseHas('episodes', [
            'title' => 'Episode 2',
        ]);
    }

    /** @test */
    public function course_creation_requires_title()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'description' => 'Course description',
            'episodes' => [
                [
                    'title' => 'Episode 1',
                    'description' => 'First episode',
                    'video_url' => 'https://video.com/1.mp4',
                ],
            ],
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function course_creation_requires_at_least_one_episode()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'title' => 'My Course',
            'description' => 'Course description',
            'episodes' => [],
        ]);

        $response->assertSessionHasErrors('episodes');
    }

    /** @test */
    public function owner_can_edit_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);
        Episode::factory()->count(2)->create(['course_id' => $course->id]);

        $response = $this->actingAs($user)->get(route('courses.edit', $course));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->has('course'));
    }

    /** @test */
    public function non_owner_cannot_edit_course()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->get(route('courses.edit', $course));

        $response->assertForbidden();
    }

    /** @test */
    public function owner_can_update_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);
        Episode::factory()->count(2)->create(['course_id' => $course->id]);

        $response = $this->actingAs($user)->patch(route('courses.update', $course), [
            'title' => 'Updated Course',
            'description' => 'Updated description',
            'episodes' => [
                [
                    'title' => 'New Episode',
                    'description' => 'New description',
                    'video_url' => 'https://video.com/new.mp4',
                ],
            ],
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Course',
        ]);
    }

    /** @test */
    public function authenticated_user_can_toggle_progress()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $episode = Episode::factory()->create(['course_id' => $course->id]);

        $response = $this->actingAs($user)->post(route('courses.toggle'), [
            'episodeId' => $episode->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('completions', [
            'user_id' => $user->id,
            'episode_id' => $episode->id,
        ]);
    }

    /** @test */
    public function toggling_progress_removes_completion_if_already_completed()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $episode = Episode::factory()->create(['course_id' => $course->id]);

        // First toggle - add completion
        $this->actingAs($user)->post(route('courses.toggle'), [
            'episodeId' => $episode->id,
        ]);

        $this->assertDatabaseHas('completions', [
            'user_id' => $user->id,
            'episode_id' => $episode->id,
        ]);

        // Second toggle - remove completion
        $response = $this->actingAs($user)->post(route('courses.toggle'), [
            'episodeId' => $episode->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('completions', [
            'user_id' => $user->id,
            'episode_id' => $episode->id,
        ]);
    }

    /** @test */
    public function guest_cannot_toggle_progress()
    {
        $course = Course::factory()->create();
        $episode = Episode::factory()->create(['course_id' => $course->id]);

        $response = $this->post(route('courses.toggle'), [
            'episodeId' => $episode->id,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('completions', [
            'episode_id' => $episode->id,
        ]);
    }

    /** @test */
    public function course_show_includes_episodes()
    {
        $course = Course::factory()->create();
        $episodes = Episode::factory()->count(5)->create(['course_id' => $course->id]);

        $response = $this->get(route('courses.show', $course));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('course.episodes', 5)
        );
    }
}

