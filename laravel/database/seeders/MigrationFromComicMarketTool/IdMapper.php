<?php

namespace Database\Seeders\MigrationFromComicMarketTool;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IdMapper
{
    private $mapping = [];

    public function addMap(Model $model, int $fromId): void
    {
        $modelClass = get_class($model);
        if (!array_key_exists($modelClass, $this->mapping)) {
            $this->mapping[$modelClass] = [];
        }

        $this->mapping[$modelClass][$fromId] = $model->id;
    }

    public function getModelId(string $modelClass, ?int $fromId): string
    {
        if (is_null($fromId)) {
            throw new ModelNotFoundException("{$fromId} is null");
        }

        $id = $this->mapping[$modelClass][$fromId] ?? null;

        if (!$id) {
            throw new ModelNotFoundException("{$modelClass}[{$fromId}] is null");
        }

        return $id;
    }
}
