<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Str;
use Illuminate\Database\Eloquent\Model as BaseModel;

use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

abstract class Model extends BaseModel
{
    protected $connection = 'mysql_comic_market_tool';

    protected array $columnMapping = [];
    protected array $relationMapping = [];

    public function convertToAppModelArray(IdMapper $idMapper): array
    {
        $basicAttributes = collect($this->columnMapping)
            ->mapWithKeys(fn ($toColumn, $fromColumn) => [$toColumn => $this->$fromColumn])
            ->toArray();
        $relationAttributes = $this->relationValues($idMapper);
        $appendAttributes = $this->appendAttributes($idMapper);

        return array_merge($basicAttributes, $relationAttributes, $appendAttributes);
    }

    // note: 特殊パターンの場合の追加カラムの値を継承先で定義できる
    protected function appendAttributes(IdMapper $idMapper): array
    {
        return [];
    }

    public function shouldIgnore(IdMapper $idMapper): bool
    {
        return false;
    }

    // note: 通常の処理系ではうまくいかないパターンは個別に継承先に実装している
    //       例: もともと1レコードだったものが増えた場合など
    public function migrateSpecifyPattern(IdMapper $idMapper): void
    {
        // デフォルトでは何もしない
    }

    // note: 登録しようとしているレコードがすでにあるか調べ、あったら該当Modelを返す
    public function findAlreadyCreatedRecord(IdMapper $idMapper): ?BaseModel
    {
        return null;
    }

    private function relationValues(IdMapper $idMapper): array
    {
        return collect($this->relationMapping)->mapWithKeys(function ($column, $fromColumn) use ($idMapper) {
            $fromId = $this->$fromColumn;
            $modelClass =  'App\\Models\\' . ucfirst(Str::camel(str_replace('_id', '', $column)));
            $id = $idMapper->getModelId($modelClass, $fromId);

            return [$column => $id];
        })->toArray();
    }
}
