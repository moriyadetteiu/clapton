<?php

namespace App\Console\Commands\Make;

use Illuminate\Console\Command;

use App\Support\Graphql\InputDefinitionExtractorInterface;

class MakePackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package {inputName} {--model=} {--operation=create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'graphQLのスキーマで定義したinputを元に登録などで使うクラス一式を生成します';

    private InputDefinitionExtractorInterface $extractor;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InputDefinitionExtractorInterface $extractor)
    {
        parent::__construct();

        $this->extractor = $extractor;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->validate();

        $model = $this->getModelName();
        $inputName = $this->argument('inputName');
        $operation = $this->option('operation');
        $operationUpper = ucfirst($operation);

        $this->call('make:useCase', [
            'name' => "{$operationUpper}{$model}",
            '--model' => $model,
            '--operation' => $operation,
        ]);

        $this->call('make:useCaseInput', [
            'name' => "{$operationUpper}{$model}Input",
            '--inputName' => $inputName,
            '--model' => $model,
        ]);

        // TODO: fillableを入れる
        $this->call('make:modelWithInput', [
            'name' => $model,
            '--inputName' => $inputName,
        ]);

        // TODO: definitionを入れる
        $this->call('make:factory', [
            'name' => "{$model}Factory",
        ]);

        $this->call('make:mutation', [
            'name' => "{$operationUpper}{$model}",
            '--model' => $model,
        ]);
    }

    private function getModelName(): string
    {
        $model = $this->option('model');
        if ($model) {
            return $model;
        }

        $inputName = $this->argument('inputName');
        $operation = $this->option('operation');
        $operationUpper = ucfirst($operation);
        return ucfirst(str_replace(['Input', $operationUpper], '', $inputName));
    }

    private function validate(): void
    {
        $inputName = $this->argument('inputName');

        // 事前に抽出できるか確認しておく
        $this->extractor->extract($inputName);
    }
}
