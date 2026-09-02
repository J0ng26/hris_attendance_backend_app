<?php

namespace App\Domain\Generators;

use Illuminate\Support\Str;
use RuntimeException;

class DomainGenerator
{
    public function __construct(private readonly ?string $basePath = null)
    {
    }

    /**
     * Generate a CRUD module using the same structure as the Department module.
     *
     * @param array<int|string, string> $fields Example: ['title' => 'string'] or ['title'].
     * @return array<string, string>
     */
    public function generate(string $name, array $fields = [], bool $force = false): array
    {
        $files = $this->preview($name, $fields);

        if (!$force) {
            $this->assertFilesCanBeWritten(array_keys($files));
        }

        foreach ($files as $path => $contents) {
            $this->writeFile($path, $contents, $force);
        }

        return $files;
    }

    /**
     * Preview generated files without writing to disk.
     *
     * @param array<int|string, string> $fields
     * @return array<string, string>
     */
    public function preview(string $name, array $fields = []): array
    {
        $context = $this->context($name, $this->normalizeFields($fields));
        $files = [];

        foreach ($this->generators() as $generator) {
            $files = array_merge($files, $generator->generate($context));
        }

        return $files;
    }

    /**
     * @return array<int, StabGenerator>
     */
    private function generators(): array
    {
        return [
            new ControllerGenerator($this->basePath),
            new RequestGenerator($this->basePath),
            new ModelGenerator($this->basePath),
            new ServiceGenerator($this->basePath),
            new InterfaceGenerator($this->basePath),
            new RepositoryGenerator($this->basePath),
            new ProviderGenerator($this->basePath),
            new RouteGenerator($this->basePath),
            new MigrationGenerator($this->basePath),
        ];
    }

    /**
     * @param array<string, string> $fields
     * @return array<string, mixed>
     */
    private function context(string $name, array $fields): array
    {
        $model = Str::studly($name);
        $modelVar = Str::camel($model);
        $table = Str::plural(Str::snake($model));
        $prefix = Str::kebab($model);
        $routeFile = Str::snake($model) . '_routes.php';
        return [
            'fields' => $fields,
            'model' => $model,
            'modelVar' => $modelVar,
            'table' => $table,
            'prefix' => $prefix,
            'routeFile' => $routeFile,
            'responseKey' => Str::snake($model),
            'controllerClass' => $model . 'Controller',
            'requestClass' => $model . 'Request',
            'serviceClass' => $model . 'Service',
            'repositoryClass' => $model . 'Repository',
            'interfaceClass' => $model . 'RepositoryInterface',
            'providerClass' => $model . 'Provider',
            'serviceVar' => Str::camel($model . 'Service'),
            'repositoryVar' => Str::camel($model . 'Repository'),
        ];
    }

    /**
     * @param array<int|string, string> $fields
     * @return array<string, string>
     */
    private function normalizeFields(array $fields): array
    {
        $normalized = [];

        foreach ($fields as $name => $type) {
            if (is_int($name)) {
                $name = $type;
                $type = 'string';
            }

            $normalized[Str::snake((string) $name)] = (string) $type;
        }

        return $normalized;
    }

    /**
     * @param array<int, string> $paths
     */
    private function assertFilesCanBeWritten(array $paths): void
    {
        foreach ($paths as $path) {
            if (file_exists($path)) {
                throw new RuntimeException("File already exists: {$path}");
            }
        }
    }

    private function writeFile(string $path, string $contents, bool $force): void
    {
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($path, $contents);
    }
}
