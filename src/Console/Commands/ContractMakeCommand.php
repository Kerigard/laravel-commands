<?php

namespace Kerigard\LaravelUtils\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:contract')]
class ContractMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new contract interface';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Contract';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     */
    protected function buildClass($name): string
    {
        return str_replace(
            '{{ method }}',
            config('utils.console_commands.make_action.method', 'handle'),
            parent::buildClass($name)
        );
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->option('action')
            ? $this->resolveStubPath('/stubs/contract.action.stub')
            : $this->resolveStubPath('/stubs/contract.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Contracts';
    }

    /**
     * Get the console command arguments.
     */
    protected function getOptions(): array
    {
        return [
            ['action', 'a', InputOption::VALUE_NONE, 'Create a contract for an action'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the interface even if the contract already exists'],
        ];
    }
}
