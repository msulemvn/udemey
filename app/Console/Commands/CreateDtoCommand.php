<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDtoCommand extends Command
{
    // Define the command name and description
    protected $signature = 'make:dto {name}';
    protected $description = 'Create a new Data Transfer Object (DTO)';

    public function handle()
    {
        // Get the DTO name from the command argument
        $name = $this->argument('name');

        // Replace slashes with directory separators to create subdirectories
        $namespacePath = str_replace('\\', '/', $name);
        $dtoPath = app_path("DTOs/{$namespacePath}.php");

        // Check if the DTO already exists
        if (File::exists($dtoPath)) {
            $this->error("DTO {$name} already exists!");
            return;
        }

        // Create the DTO directory if it doesn't exist
        $dtoDirectory = dirname($dtoPath);
        if (!File::exists($dtoDirectory)) {
            File::makeDirectory($dtoDirectory, 0755, true);
        }

        // Create the DTO file content
        $content = $this->generateDtoContent($name);

        // Write the content to the DTO file
        File::put($dtoPath, $content);

        $this->info("DTO {$name} created successfully!");
    }

    private function generateDtoContent($name)
    {
        // Extract the base name for the class
        $className = class_basename($name);
        // Extract the namespace for use in the file
        $namespace = str_replace('/', '\\', dirname($name));

        return <<<EOT
<?php

namespace App\DTOs{$namespace};

class {$className}
{
    // Define your properties here

    public function __construct(array \$data)
    {
        // Initialize properties from the provided data array
    }

    // Add any methods to manipulate the DTO if necessary
}
EOT;
    }
}
