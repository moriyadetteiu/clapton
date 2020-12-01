<?php

namespace App\Console\Commands\Make;

use Illuminate\Console\GeneratorCommand;

abstract class ReplaceableGeneratorCommand extends GeneratorCommand
{
    abstract protected function getReplaceContents(): array;

    private function replaceStubContents(string $stub): string
    {
        $contents = $this->getReplaceContents();
        foreach ($contents as $name => $content) {
            $stub = str_replace(["{{ $name }}", "{{$name}}"], $content, $stub);
        }
        return $stub;
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        return $this->replaceStubContents($stub);
    }
}
