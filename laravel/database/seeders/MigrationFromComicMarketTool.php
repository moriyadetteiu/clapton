<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models;
use Database\Seeders\MigrationFromComicMarketTool\Models as FromModels;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MigrationFromComicMarketTool extends Seeder
{
    // note: 上から順番に処理する。外部キー制約があるため、順番に注意が必要
    private const MIGRATION_MODEL_CLASSES = [
        FromModels\User::class => Models\User::class,
        FromModels\Group::class => Models\Team::class,
        FromModels\Event::class => Models\Event::class,
        FromModels\Affiliation::class => Models\UserAffiliationTeam::class,
        FromModels\EventDate::class => Models\EventDate::class,
        FromModels\Participation::class => Models\JoinEvent::class,
        FromModels\ParticipationForJoinEventDate::class => Models\JoinEventDate::class,
        FromModels\Circle::class => Models\Circle::class,
        FromModels\CirclePlacement::class => Models\CirclePlacement::class,
        FromModels\Check::class => Models\CareAboutCircle::class,
        FromModels\SellProduct::class => Models\CircleProduct::class,
        FromModels\Buy::class => Models\WantCircleProduct::class,
        FromModels\Favorite::class => Models\Favorite::class,
    ];

    private IdMapper $idMapper;
    private array $failedRecords = [];

    public function __construct()
    {
        $this->idMapper = new IdMapper();
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('starting php artisan migrate:fresh...');
        \Artisan::call('migrate:fresh');
        $this->command->info('complete migrate:fresh');
        collect(self::MIGRATION_MODEL_CLASSES)->each(function ($modelClass, $fromModelClass) {
            $this->command->info("starting migrate {$fromModelClass} to {$modelClass}...");

            $fromModelClass::all()
                ->reject(fn ($fromModel) => $fromModel->shouldIgnore($this->idMapper))
                ->each(function ($fromModel) use ($modelClass, $fromModelClass) {
                    try {
                        $model = $fromModel->findAlreadyCreatedRecord($this->idMapper);

                        if (is_null($model)) {
                            $values = $fromModel->convertToAppModelArray($this->idMapper);
                            $model = new $modelClass();
                            $model->forceFill($values);
                            $model->save();
                        }

                        $this->idMapper->addMap($model, $fromModel->id);

                        $fromModel->migrateSpecifyPattern($this->idMapper);
                    } catch (ModelNotFoundException $e) {
                        if (!array_key_exists($fromModelClass, $this->failedRecords)) {
                            $this->failedRecords[$fromModelClass] = [];
                        }

                        $this->failedRecords[$fromModelClass][] = [
                            'id' => $fromModel->id,
                            'message' => $e->getMessage()
                        ];
                        return;
                    }
                });

            $this->command->info("complete migrate {$fromModelClass} to {$modelClass}");
        });
        \Log::debug($this->failedRecords);
    }
}
