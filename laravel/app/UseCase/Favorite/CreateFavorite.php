<?php

namespace App\UseCase\Favorite;

use App\Models\Favorite;
use App\UseCase\UseCase;

class CreateFavorite extends UseCase
{
    public function execute(CreateFavoriteInput $input)
    {
        $favoriteData = $input->toArray();
        $favorite = Favorite::create($favoriteData);
        return $favorite;
    }
}
