<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\UserAffiliationTeam;
use App\Models\User;
use App\Models\Team;

class UserAffiliationTeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAffiliationTeam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $team = Team::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ];
    }
}
