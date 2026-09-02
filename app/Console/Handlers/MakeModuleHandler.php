<?php

namespace App\Console\Handlers;

use Illuminate\Console\Command;

class MakeModuleHandler
{
    /**
     * @param array<string, string> $files
     */
    public function display(Command $command, array $files): void
    {
        foreach (array_keys($files) as $path) {
            $command->line('  <info>CREATED</info> ' . $this->relativePath($path));
        }

        $command->newLine();
        $command->line('  <info>INFO</info>  Module structure created successfully.');
    }

    private function relativePath(string $path): string
    {
        $basePath = rtrim(base_path(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $normalizedBasePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $basePath);

        if (str_starts_with($normalizedPath, $normalizedBasePath)) {
            return substr($normalizedPath, strlen($normalizedBasePath));
        }

        return $path;
    }
}
