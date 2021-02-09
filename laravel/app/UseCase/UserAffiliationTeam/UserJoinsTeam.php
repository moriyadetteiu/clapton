<?php

namespace App\UseCase\UserAffiliationTeam;

use App\Models\UserAffiliationTeam;
use App\UseCase\UseCase;

class UserJoinsTeam extends UseCase
{
    public function execute(UserJoinsTeamInput $input)
    {
        $userJoinsTeamData = $input->toArray();
        $userJoinsTeam = UserAffiliationTeam::create($userJoinsTeamData);
        return $userJoinsTeam;
    }
}
