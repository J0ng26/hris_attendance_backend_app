<?php

namespace App\Domain\Generators;

use Illuminate\Support\Str;

abstract class StabGenerator
{
    public function __construct(protected readonly ?string $basePath = null)
    {
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, string>
     */
    abstract public function generate(array $context): array;

    /**
     * @param array<string, string> $replacements
     */
    protected function render(string $stub, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            $stub = str_replace('{{ ' . $key . ' }}', $value, $stub);
        }

        return rtrim($stub) . PHP_EOL;
    }

    protected function path(string $path): string
    {
        $basePath = $this->basePath ?? base_path();

        return rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param array<string, string> $fields
     */
    protected function controllerCreateArguments(array $fields): string
    {
        $arguments = collect(array_keys($fields))
            ->map(fn (string $field) => "                \$validated['{$field}']")
            ->implode(",\n");

        return $arguments === '' ? '' : "\n" . $arguments . "\n            ";
    }

    /**
     * @param array<string, string> $fields
     */
    protected function controllerUpdateArguments(array $fields): string
    {
        $arguments = collect(array_keys($fields))
            ->map(fn (string $field) => "\n                \$validated['{$field}']")
            ->implode(',');

        return $arguments === '' ? '' : ',' . $arguments;
    }

    /**
     * @param array<string, string> $fields
     */
    protected function serviceCreateParameters(array $fields): string
    {
        return collect($fields)
            ->map(fn (string $type, string $field) => $this->phpType($type) . ' $' . Str::camel($field))
            ->values()
            ->implode(', ');
    }

    /**
     * @param array<string, string> $fields
     */
    protected function serviceUpdateParameters(array $fields): string
    {
        $parameters = $this->serviceCreateParameters($fields);

        return $parameters === '' ? '' : ', ' . $parameters;
    }

    /**
     * @param array<string, string> $fields
     */
    protected function repositoryData(array $fields): string
    {
        $data = collect(array_keys($fields))
            ->map(fn (string $field) => "            '{$field}' => $" . Str::camel($field))
            ->implode(",\n");

        return $data === '' ? '' : "\n" . $data . "\n        ";
    }

    /**
     * @param array<string, string> $fields
     */
    protected function validationRules(array $fields, string $table): string
    {
        return collect($fields)
            ->map(fn (string $type, string $field) => "            '{$field}' => " . $this->validationRule($field, $type, $table) . ',')
            ->implode("\n");
    }

    /**
     * @param array<string, string> $fields
     */
    protected function fillable(array $fields): string
    {
        return collect(array_keys($fields))
            ->map(fn (string $field) => "        '{$field}'")
            ->implode(",\n");
    }

    /**
     * @param array<string, string> $fields
     */
    protected function migrationColumns(array $fields): string
    {
        $columns = collect($fields)
            ->map(fn (string $type, string $field) => "            \$table->{$this->migrationType($type)}('{$field}');")
            ->implode("\n");

        return $columns === '' ? '' : $columns . "\n";
    }

    protected function validationRule(string $field, string $type, string $table): string
    {
        if ($field === 'name') {
            return "['required', 'string', 'unique:{$table},name']";
        }

        return match ($this->normalizedType($type)) {
            'uuid' => "['required', 'uuid']",
            'integer', 'bigInteger', 'smallInteger', 'tinyInteger' => "['required', 'integer']",
            'boolean' => "['required', 'boolean']",
            'date' => "['required', 'date']",
            'dateTime', 'timestamp' => "['required', 'date']",
            'decimal', 'double', 'float' => "['required', 'numeric']",
            default => "['required', 'string']",
        };
    }

    protected function phpType(string $type): string
    {
        return match ($this->normalizedType($type)) {
            'integer', 'bigInteger', 'smallInteger', 'tinyInteger' => 'int',
            'boolean' => 'bool',
            'decimal', 'double', 'float' => 'float',
            default => 'string',
        };
    }

    protected function migrationType(string $type): string
    {
        return match ($this->normalizedType($type)) {
            'uuid' => 'uuid',
            'integer', 'bigInteger', 'smallInteger', 'tinyInteger' => $this->normalizedType($type),
            'boolean' => 'boolean',
            'date' => 'date',
            'dateTime' => 'dateTime',
            'timestamp' => 'timestamp',
            'decimal' => 'decimal',
            'double' => 'double',
            'float' => 'float',
            'text' => 'text',
            default => 'string',
        };
    }

    protected function normalizedType(string $type): string
    {
        return str_replace([' ', '_', '-'], '', lcfirst(Str::camel($type)));
    }
}
