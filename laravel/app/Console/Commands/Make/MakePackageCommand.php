<?php

namespace App\Console\Commands\Make;

use Illuminate\Console\Command;

use App\Support\Graphql\InputDefinitionExtractor;

class MakePackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '登録などで使うクラス一式を生成します';

    private InputDefinitionExtractor $extractor;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InputDefinitionExtractor $extractor)
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
        dd($this->extractor->extract('UserInput'));
    }
}
