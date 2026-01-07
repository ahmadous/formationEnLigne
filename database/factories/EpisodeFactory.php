<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>$this->faker->sentence(),
            'description'=>$this->faker->paragraph(3,true),
            'video_url'=>'https://video.com/'.$this->faker->uuid().'.mp4',
            'course_id' =>Course::factory()->create()->id,
        ];
    }
}
