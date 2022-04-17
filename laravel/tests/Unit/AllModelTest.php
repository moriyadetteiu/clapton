<?php

namespace Tests\Unit;

use ReflectionClass;
use ReflectionMethod;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

use Tests\TestCase;
use Tests\Helpers\ClassEnumerator;

class AllModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider hasFactoryModelClasses
     */
    public function testModelFactory($model)
    {
        $factory = $model::factory();
        $instance = $factory->create();
        $this->assertTrue($instance instanceof $model);
    }

    /**
     * @dataProvider hasFactoryModelClasses
     */
    public function testModelRelations($modelClass)
    {
        $model = $modelClass::factory()->create();

        $this->extractRelationMethodReflections($modelClass)
            ->each(function (ReflectionMethod $reflectionMethod) use ($model) {
                $relation = $reflectionMethod->invoke($model);
                $relatedModel = $relation->getRelated();

                $relationName = $reflectionMethod->getName();
                $relationResult = $model->$relationName;
                $relationResultFirst = $relationResult instanceof EloquentCollection ? $relationResult->first() : $relationResult;

                // note: リレーション先のレコードが存在しない場合があるので、nullは成功として扱っている
                //       とりあえず問題なくクエリが叩けているかどうかのテストを行っている
                $isNull = is_null($relationResultFirst);
                $isRelated = $relationResultFirst instanceof $relatedModel;
                $this->assertTrue($isNull || $isRelated);
            });
    }

    private function extractRelationMethodReflections(string $modelClass): Collection
    {
        $reflectionClass = new ReflectionClass($modelClass);
        return collect($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))
            ->reject(function (ReflectionMethod $reflectionMethod) {
                $isAbstract = $reflectionMethod->isAbstract();
                $hasRequiredArgument = $reflectionMethod->getNumberOfRequiredParameters() > 0;
                return $isAbstract || $hasRequiredArgument;
            })
            ->filter(function (ReflectionMethod $reflectionMethod) {
                $returnType = $reflectionMethod->getReturnType();
                return !is_null($returnType) && is_subclass_of($returnType->getName(), Relation::class);
            });
    }

    public function hasFactoryModelClasses(): array
    {
        $this->createApplication();

        $classEnumerator = new ClassEnumerator();
        return $classEnumerator->enumerateClassesInDirectory(app_path('Models'))
            ->filter(function ($model) {
                $useTraits = trait_uses_recursive($model);
                return in_array(HasFactory::class, $useTraits);
            })
            ->values()
            ->mapWithKeys(function ($model) {
                return [$model => [$model]];
            })->toArray();
    }
}
