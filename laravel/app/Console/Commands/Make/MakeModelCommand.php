<?php

namespace App\Console\Commands\Make;

use Illuminate\Foundation\Console\ModelMakeCommand;

class MakeModelCommand extends ModelMakeCommand
{
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/model.stub';
    }
}
