<?php

namespace Database\DatasetFactories;

use App\Models\{
    User,
    Team,
    UserAffiliationTeam,
};

class TeamDatasetFactory
{
    public function create(): array
    {
        $user = User::factory()->create([
            'email' => $this->generateUserEmail(),
        ]);
        $team = Team::factory()->create();
        $userAffiliationTeam = UserAffiliationTeam::factory()
            ->create([
                'user_id' => $user->id,
                'team_id' => $team->id,
            ]);
        return compact(['user', 'team', 'userAffiliationTeam']);
    }

    private function generateUserEmail(): string
    {
        $userCount = User::count();
        $userMailSuffix = $userCount > 0 ? $userCount : '';
        return "test{$userMailSuffix}@test.test";
    }
}
