<?php

namespace App\Console\Commands\Make;

use Illuminate\Support\Str;

class MakeGraphqlDefinitionCommand extends ReplaceableGeneratorCommand
{
    protected $signature = 'make:graphql-definition {name}';

    protected $type = 'graphql definition';

    public function handle()
    {
        $result = parent::handle();

        if ($result !== false) {
            $name = $this->getNameInput();
            $this->info('paste the following into a graphql/schema.graphql');
            $this->info("#import {$name}.graphql");
        }

        return $result;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/graphqlDefinition.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $lowerName = lcfirst($name);
        $basePath = base_path('graphql');

        return "{$basePath}/{$lowerName}.graphql";
    }

    protected function getReplaceContents(): array
    {
        $class = $this->getNameInput();
        $lowerClass = lcfirst($class);
        $upperClass = ucfirst($class);
        $pluralLowerClass = Str::plural($lowerClass);

        return [
            'lowerClass' => $lowerClass,
            'upperClass' => $upperClass,
            'pluralLowerClass' => $pluralLowerClass,
        ];
    }
}
