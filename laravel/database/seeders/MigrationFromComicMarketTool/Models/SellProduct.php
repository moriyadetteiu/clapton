<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use App\Models\CircleProductClassification;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class SellProduct extends Model
{
    protected array $columnMapping = [
        'name' => 'name',
        'price' => 'price',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'circle_placement_id' => 'circle_placement_id',
    ];

    public function shouldIgnore(IdMapper $idMapper): bool
    {
        return $this->name === '';
    }

    protected function appendAttributes(IdMapper $idMapper): array
    {
        $groupId = $this->circlePlacement()->firstOrFail()->event()->firstOrFail()->group_id;
        $teamId = $idMapper->getModelId(Team::class, $groupId);
        $circleProductClassification = CircleProductClassification::where('team_id', $teamId)
            ->where('name', $this->fixed_classification)
            ->firstOrFail();

        return [
            'circle_product_classification_id' => $circleProductClassification->id,
        ];
    }

    public function getFixedClassificationAttribute(): string
    {
        if ($this->classification === 'グッツ類') {
            return 'グッズ類';
        }
        if ($this->classification === 'グッツセット') {
            return 'グッズセット';
        }
        return $this->classification;
    }

    public function circlePlacement(): BelongsTo
    {
        return $this->belongsTo(CirclePlacement::class);
    }
}
