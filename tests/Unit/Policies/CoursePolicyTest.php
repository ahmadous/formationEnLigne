<?php

namespace Tests\Unit\Policies;

use App\Models\Course;
use App\Models\User;
use App\Policies\coursePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function owner_can_update_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $user->id]);

        $policy = new coursePolicy();

        $this->assertTrue($policy->update($user, $course));
    }

    /** @test */
    public function non_owner_cannot_update_course()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $owner->id]);

        $policy = new coursePolicy();

        $this->assertFalse($policy->update($otherUser, $course));
    }
}

