<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use App\Models\User as AppUser;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class User extends Model
{
    protected array $columnMapping = [
        'name' => 'name',
        'kana' => 'name_kana',
        'handle_name' => 'handle_name',
        'handle_kana' => 'handle_name_kana',
        'email' => 'email',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];


    protected function appendAttributes(IdMapper $idMapper): array
    {
        return [
            // note: パスワードをそのまま持ってこれないっぽいので、デフォルトパスワードに変えてる。
            // TODO: ランダム生成、メール送信など
            'password' => AppUser::encryptPassword('password'),
        ];
    }
}
