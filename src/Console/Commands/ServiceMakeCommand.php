<?php

namespace Kerigard\LaravelUtils\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:service')]
class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        if ($this->option('contract') !== false) {
            $this->createContract();
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        if ($this->option('contract') === false) {
            return $stub;
        }

        $contract = $this->getContractName();

        if (! Str::startsWith($contract, [$this->laravel->getNamespace(), '\\'])) {
            $contract = $this->laravel->getNamespace().'Contracts\\'.str_replace('/', '\\', $contract);
        }

        $contract = trim($contract, '\\');
        $contractName = class_basename($contract);

        if ($contractName == class_basename($name)) {
            $contractName .= 'Contract';
            $contract .= " as {$contractName}";
        }

        $replace = [
            '{{ contract }}' => $contractName,
            '{{ contractNamespace }}' => $contract,
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    /**
     * Create a contract file for the action.
     */
    protected function createContract(): void
    {
        $this->call('make:contract', [
            'name' => $this->getContractName(),
        ]);
    }

    /**
     * Get contract interface name.
     */
    protected function getContractName(): string
    {
        return is_string($this->option('contract'))
            ? $this->option('contract')
            : Str::studly(class_basename($this->argument('name')));
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->option('contract') !== false
            ? $this->resolveStubPath('/stubs/service.contract.stub')
            : $this->resolveStubPath('/stubs/service.stub');
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
        return $rootNamespace.'\Services';
    }

    /**
     * Get the console command arguments.
     */
    protected function getOptions(): array
    {
        return [
            ['contract', 'c', InputOption::VALUE_OPTIONAL, 'Create a new contract for the service', false],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the service already exists'],
        ];
    }
}
