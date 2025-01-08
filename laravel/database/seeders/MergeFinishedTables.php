<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

use Database\Seeders\MigrationFromComicMarketTool\Models\Buy;
use Database\Seeders\MigrationFromComicMarketTool\Models\Check;
use Database\Seeders\MigrationFromComicMarketTool\Models\CirclePlacement;
use Database\Seeders\MigrationFromComicMarketTool\Models\SellProduct;
use Database\Seeders\MigrationFromComicMarketTool\Models\FinishedTableInformation;

// 旧claptonの過去イベントのレコードを本体テーブルにまとめるSeeder
class MergeFinishedTables extends Seeder
{
    // レコードをまとめる対象のテーブル一覧。外部キー制約に引っかからない順番にすること
    private const MIGRATION_TARGET_MODEL_CLASSES = [
        CirclePlacement::class,
        SellProduct::class,
        Check::class,
        Buy::class,
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        collect(self::MIGRATION_TARGET_MODEL_CLASSES)->each(function ($modelClass) {
            $emptyModel = new $modelClass();
            $table = $emptyModel->getTable();
            FinishedTableInformation::where('table_name', $table)
                ->get()
                ->filter(function ($finishedTable) {
                    return count(DB::connection('mysql_comic_market_tool')->select("show tables like '{$finishedTable->finishedTableName}'")) > 0;
                })
                ->each(function ($finishedTable) use ($modelClass) {
                    DB::connection('mysql_comic_market_tool')
                        ->table($finishedTable->finishedTableName)
                        ->get()
                        ->reject(function ($values) use ($modelClass) {
                            return $modelClass::where('id', $values->id)->exists();
                        })
                        ->each(function ($values) use ($modelClass) {
                            $model = new $modelClass();
                            $model->forceFill((array)$values);
                            $model->save();
                        });
                });
        });
    }
}
