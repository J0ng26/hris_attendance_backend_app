<?php

namespace App\Console;

use App\Console\Handlers\MakeModuleHandler;
use App\Domain\Generators\DomainGenerator;
use Illuminate\Console\Command;
use InvalidArgumentException;
use RuntimeException;

class MakeModule extends Command
{
    protected $signature = 'make:module
        {name : The module/model name}
        {--field=* : Field definitions in name:type format. Example: --field=name:string,description:text}
        {--force : Overwrite existing generated files}';

    protected $description = 'Generate a CRUD module structure from domain stabs.';

    public function handle(DomainGenerator $generator, MakeModuleHandler $handler): int
    {
        $name = (string) $this->argument('name');

        try {
            $files = $generator->generate(
                $name,
                $this->fields(),
                (bool) $this->option('force')
            );
        } catch (InvalidArgumentException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        } catch (RuntimeException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        $handler->display($this, $files);

        return self::SUCCESS;
    }

    /**
     * @return array<string, string>
     */
    private function fields(): array
    {
        $fields = $this->option('field');

        if ($fields === []) {
            return [];
        }

        $parsed = [];

        foreach ($this->fieldDefinitions($fields) as $field) {
            if (!is_string($field) || !str_contains($field, ':')) {
                throw new InvalidArgumentException('Fields must use name:type format. Example: --field=name:string,description:text');
            }

            [$name, $type] = array_map('trim', explode(':', $field, 2));

            if ($name === '' || $type === '') {
                throw new InvalidArgumentException('Fields must use name:type format. Example: --field=name:string,description:text');
            }

            $parsed[$name] = $type;
        }

        return $parsed;
    }

    /**
     * @param array<int, string> $fields
     * @return array<int, string>
     */
    private function fieldDefinitions(array $fields): array
    {
        $definitions = [];

        foreach ($fields as $field) {
            foreach (explode(',', $field) as $definition) {
                $definition = trim($definition);

                if ($definition !== '') {
                    $definitions[] = $definition;
                }
            }
        }

        return $definitions;
    }
}
