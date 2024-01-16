<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Team;
use App\Models\WantPriority;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class Buy extends Model
{
    protected array $columnMapping = [
        'number' => 'quantity',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'sell_product_id' => 'circle_product_id',
        'check_id' => 'care_about_circle_id',
    ];

    protected function appendAttributes(IdMapper $idMapper): array
    {
        $groupId = $this->sellProduct()
            ->firstOrFail()
            ->circlePlacement()
            ->firstOrFail()
            ->event()
            ->firstOrFail()
            ->group_id;
        $teamId = $idMapper->getModelId(Team::class, $groupId);
        $wantPriority = WantPriority::where('team_id', $teamId)
            ->where('name', $this->priority)
            ->firstOrFail();

        return [
            'want_priority_id' => $wantPriority->id,
        ];
    }

    public function sellProduct(): BelongsTo
    {
        return $this->belongsTo(SellProduct::class);
    }
}
