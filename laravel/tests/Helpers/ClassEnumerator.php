<?php

namespace Tests\Helpers;

use ReflectionClass;
use Illuminate\Support\Collection;

class ClassEnumerator
{
    public function enumerateClassesInDirectory(string $directory): Collection
    {
        return collect(glob("{$directory}/*.php"))
            ->map(function ($file) {
                $fileWithoutBasePath = str_replace(base_path() . '/', '', $file);
                $fileWithoutBasePathAndExtension = str_replace('.php', '', $fileWithoutBasePath);
                return '\\' . ucfirst(str_replace('/', '\\', $fileWithoutBasePathAndExtension));
            })->filter(function ($class) {
                return class_exists($class);
            })->filter(function ($class) {
                $reflectionClass = new ReflectionClass($class);
                return $reflectionClass->isInstantiable();
            })
            ->values();
    }
}
