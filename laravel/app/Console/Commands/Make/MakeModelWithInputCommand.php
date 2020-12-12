<?php

namespace App\Console\Commands\Make;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

use App\Support\Graphql\InputDefinitionExtractorInterface;
use App\Support\Graphql\InputDefinition;

class MakeModelWithInputCommand extends ReplaceableGeneratorCommand
{
    protected $signature = 'make:modelWithInput {name} {--inputName=}';

    protected $type = 'model(with input)';

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
        return __DIR__ . '/stubs/modelWithInput.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\Models";
    }

    protected function getReplaceContents(): array
    {
        return [
            'fillable' => $this->getFillable()->implode(', '),
        ];
    }

    private function getFillable(): Collection
    {
        $inputName = $this->option('inputName');
        if (!$inputName) {
            return collect([]);
        }

        return $this
            ->extractor
            ->extract($inputName)
            ->map(function (InputDefinition $field) {
                return "'{$field->getName()}'";
            });
    }
}
