<?php

namespace Database\Factories;

use App\Models\Social;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SocialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Social::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sector = Sector::all()->random(1)->first();
        $user = User::all()->random(1)->first();
        return [
            'title' => $this->faker->text(),
            'description' => $this->faker->numberBetween(20, 5000),
            'sector_id' => $sector->id,
            'author_id' => $user->id,
            'is_approved_by_post_author' => rand(0,1) == 1,
            'is_approved_by_guest_user' => rand(0,1) == 1,

        ];
    }
}
