<?php

namespace App\Console\Commands\Make;

class MakeUseCaseCommand extends ReplaceableGeneratorCommand
{
    protected $signature = 'make:useCase {name} {--model=} {--operation=create}';

    protected $type = 'UseCase';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/useCase.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $model = $this->getModelName();
        return "{$rootNamespace}\\UseCase\\{$model}";
    }

    protected function getReplaceContents(): array
    {
        $model = $this->getModelName();
        return [
            'operation' => $this->option('operation'),
            'model' => $model,
            'lowerModel' => lcfirst($model),
        ];
    }

    private function getModelName(): string
    {
        if ($this->option('model')) {
            return $this->option('model');
        }

        $name = class_basename($this->getNameInput());
        $operation = ucfirst($this->option('operation'));
        return str_replace($operation, '', $name);
    }
}
