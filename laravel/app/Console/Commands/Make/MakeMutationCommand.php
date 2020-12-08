<?php

namespace App\Console\Commands\Make;

class MakeMutationCommand extends ReplaceableGeneratorCommand
{
    protected $signature = 'make:mutation {name} {--model=} {--useCase=} {--useCaseInput=}';

    protected $type = 'Mutation';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/mutation.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\GraphQL\\Mutations";
    }

    protected function getReplaceContents(): array
    {
        return [
            'useCase' => $this->getUseCase(),
            'useCaseInput' => $this->getUseCaseInput(),
            'model' => $this->getModelName(),
        ];
    }

    private function getModelName(): string
    {
        if ($this->option('model')) {
            return $this->option('model');
        }

        $name = class_basename($this->getNameInput());
        $operations = ['Create', 'Update', 'Delete'];
        return str_replace($operations, '', $name);
    }

    private function getUseCase(): string
    {
        if ($this->option('useCase')) {
            return $this->option('useCase');
        }

        return class_basename($this->getNameInput());
    }

    private function getUseCaseInput(): string
    {
        if ($this->option('useCaseInput')) {
            return $this->option('useCaseInput');
        }

        $useCase = $this->getUseCase();
        return "{$useCase}Input";
    }
}
