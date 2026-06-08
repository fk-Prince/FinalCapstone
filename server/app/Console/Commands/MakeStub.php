<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeStub extends Command
{
    protected $signature = 'make:stub {name}';

    protected $description = 'Create a new stub file';

    public function handle()
    {
        $name = $this->argument('name');

        $name = str_replace('\\', '/', $name);

        $parts = collect(explode('/', $name))
            ->filter()
            ->values();

        $className = Str::studly($parts->last());

        $namespaceParts = $parts->slice(0, -1);

        $namespace = $namespaceParts
            ->map(fn($p) => Str::studly($p))
            ->implode('\\');

        $relativePath = $parts
            ->map(fn($p) => Str::studly($p))
            ->implode('/');

        $path = base_path("app/{$relativePath}.php");

        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists(dirname($path));

        if ($filesystem->exists($path)) {
            $this->error('Stub already exists.');
            return self::FAILURE;
        }

        $content = <<<PHP
        <?php

        namespace App\{$namespace};

        class {$className}
        {
            public function __construct()
            {
            
            }
        }
        PHP;

        $filesystem->put($path, $content);

        $this->info("Stub created: {$path}");

        return self::SUCCESS;
    }
}
