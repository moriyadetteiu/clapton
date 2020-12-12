<?php

namespace App\Support\Graphql;

use GraphQL\Language\AST\NodeKind;

class InputDefinition
{
    private $type;
    private $name;
    private $canNull;

    public function __construct(string $name, string $type, bool $canNull)
    {
        $this->type = $type;
        $this->name = $name;
        $this->canNull = $canNull;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCanNull(): bool
    {
        return $this->canNull;
    }
}
