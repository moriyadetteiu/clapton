<?php

namespace App\Support\Validation;

use ReflectionClass;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\UseCase\UseCaseInput;

class UseCaseInputValidationExtractor implements ValidationExtractorInterface
{
    private const USE_CASE_DIR = 'UseCase';
    private const USE_CASE_NAMESPACE = 'App\\UseCase\\';

    public function extract(): Collection
    {
        return $this
            ->allUseCaseInputReflectionClasses()
            ->mapWithKeys(function ($class) {
                $name = $class->getShortName();
                $rules = $this->extractRules($class)
                    ->filter(fn ($rules) => is_string($rules))
                    ->mapWithKeys(fn ($rules, $name) => [$name => ['rules' => $rules]]);
                $attributes = $this->extractAttributes($class)
                    ->mapWithKeys(fn ($attribute, $name) => [$name => ['attribute' => $attribute]]);
                return [$name => $rules->mergeRecursive($attributes)];
            });
    }

    private function allUseCaseInputReflectionClasses(): Collection
    {
        $storage = Storage::createLocalDriver([
            'driver' => 'local',
            'root' => app_path(self::USE_CASE_DIR),
        ]);

        return collect($storage->allFiles())
            ->filter(fn ($filename) => Str::is('*.php', $filename))
            ->map(fn ($filename) => str_replace('.php', '', $filename))
            ->map(fn ($filename) => str_replace('/', '\\', $filename))
            ->map(function ($filename) {
                $class = self::USE_CASE_NAMESPACE . $filename;
                return new ReflectionClass($class);
            })
            ->filter(function ($class) {
                return $class->isSubclassOf(UseCaseInput::class) && !$class->isAbstract();
            });
    }

    private function extractAttributes(ReflectionClass $class): Collection
    {
        $reflectionMethod = $class->getMethod('attributes');
        $reflectionMethod->setAccessible(true);

        $instance = $class->newInstanceWithoutConstructor();
        return collect($reflectionMethod->invoke($instance));
    }

    private function extractRules(ReflectionClass $class): Collection
    {
        $reflectionMethod = $class->getMethod('rules');
        $reflectionMethod->setAccessible(true);

        $instance = $class->newInstanceWithoutConstructor();
        return collect($reflectionMethod->invoke($instance));
    }
}
