<?php

namespace App\Support\Graphql;

use Nuwave\Lighthouse\Schema\AST\ASTBuilder;
use GraphQL\Language\AST\NonNullTypeNode;
use Illuminate\Support\Collection;

class InputDefinitionExtractor
{
    private ASTBuilder $builder;

    public function __construct(ASTBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function extract(string $inputName): Collection
    {
        return collect($this->extractFieldsCollection($inputName))
            ->map(function ($field) {
                return $this->makeInputDefinition($field);
            });
    }

    private function extractFieldsCollection(string $inputName): Collection
    {
        $documentAST = $this->builder->documentAST();
        $types = $documentAST->types;
        $type = $types[$inputName];
        return collect($type->fields->getIterator());
    }

    private function makeInputDefinition($field): InputDefinition
    {
        $name = $field->name->value;
        $type = $field->type->type->name->value;
        $canNull = !($field->type instanceof NonNullTypeNode);
        return new InputDefinition($name, $type, $canNull);
    }
}
