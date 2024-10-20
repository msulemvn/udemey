<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class DTOMakeCommand extends GeneratorCommand
{
    protected $signature = 'make:dto {name}';
    protected $description = 'Create a new Data Transfer Object (DTO) class';

    protected function getStub()
    {
        return __DIR__ . '/stubs/dto.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\DTOs';
    }

    public function handle()
    {
        return parent::handle();
    }
}
