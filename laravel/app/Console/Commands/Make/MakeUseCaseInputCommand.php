<?php

namespace App\Console\Commands\Make;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

use App\Support\Graphql\InputDefinitionExtractorInterface;
use App\Support\Graphql\InputDefinition;

class MakeUseCaseInputCommand extends ReplaceableGeneratorCommand
{
    protected $signature = 'make:useCaseInput {name} {--inputName=} {--model=}';

    protected $type = 'UseCaseInput';

    private InputDefinitionExtractorInterface $extractor;

    public function __construct(Filesystem $files, InputDefinitionExtractorInterface $extractor)
    {
        parent::__construct($files);

        $this->extractor = $extractor;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/useCaseInput.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $model = $this->getModelName();
        return "{$rootNamespace}\\UseCase\\{$model}";
    }

    protected function getReplaceContents(): array
    {
        $indent = str_replace("\t", '    ', "\t\t\t");

        $requiredRules = $this
            ->getRequiredFields()
            ->map(function (string $fieldName) {
                return "'{$fieldName}' => 'required'";
            })
            ->implode("\n{$indent}");

        return [
            'requiredRules' => $requiredRules,
        ];
    }

    private function getRequiredFields(): Collection
    {
        $inputName = $this->option('inputName');
        if (!$inputName) {
            return collect([]);
        }

        return $this
            ->extractor
            ->extract($inputName)
            ->reject(function (InputDefinition $field) {
                return $field->getCanNull();
            })
            ->map(function (InputDefinition $field) {
                return $field->getName();
            });
    }

    private function getModelName(): string
    {
        if ($this->option('model')) {
            return $this->option('model');
        }

        $name = class_basename($this->getNameInput());
        return str_replace('Input', '', $name);
    }
}
