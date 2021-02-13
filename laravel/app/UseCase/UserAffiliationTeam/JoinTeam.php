<?php

namespace App\UseCase\UserAffiliationTeam;

use App\Models\UserAffiliationTeam;
use App\UseCase\UseCase;

class JoinTeam extends UseCase
{
    public function execute(JoinTeamInput $input)
    {
        $joinTeamData = $input->toArray();
        $joinTeam = UserAffiliationTeam::create($joinTeamData);
        return $joinTeam;
    }
}
