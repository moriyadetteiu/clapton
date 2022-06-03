<?php

namespace App\UseCase\Team;

use App\Models\Team;
use App\UseCase\UseCase;
use App\UseCase\UserAffiliationTeam\JoinTeam;
use App\UseCase\UserAffiliationTeam\JoinTeamInput;

class CreateTeam extends UseCase
{
    public function execute(CreateTeamInput $input)
    {
        $teamData = $input->toArray();
        $code = Team::generateCode();
        $teamData['code'] = $code;
        $team = Team::create($teamData);

        // note: チームを作成した人はそのチームに所属するのが自然なので、自動で所属させる
        $this->affiliateOwnerToTeam($team, $input);

        return $team;
    }

    private function affiliateOwnerToTeam(Team $team, CreateTeamInput $teamInput): void
    {
        $joinTeamInput = new JoinTeamInput([
            'team_id' => $team->id,
            'user_id' => $teamInput->toArray()['owner_user_id'],
        ]);
        (new JoinTeam())->execute($joinTeamInput);
    }
}
