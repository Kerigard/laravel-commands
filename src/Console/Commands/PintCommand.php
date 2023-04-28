<?php

namespace Kerigard\LaravelUtils\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process as BaseProcess;

#[AsCommand(name: 'pint')]
class PintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pint
                    {paths?* : Run Pint on specific files or directories}
                    {--t|test : Inspect code for style errors without actually changing the files}
                    {--d|dirty : Modify the files that have uncommitted changes according to Git}
                    {--p|preset= : Use preset with rule set to fix code}
                    {--c|config= : Use pint.json config from a specific directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Laravel Pint';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $args = $this->argument('paths');

        if ($this->option('verbose')) {
            $args[] = '-v';
        }

        if ($this->option('test')) {
            $args[] = '--test';
        }

        if ($this->option('dirty')) {
            $args[] = '--dirty';
        }

        if ($this->option('preset')) {
            $args[] = '--preset';
            $args[] = $this->option('preset');
        }

        if ($this->option('config')) {
            $args[] = '--config';
            $args[] = $this->option('config');
        }

        $command = [config('utils.console_commands.pint.path', './vendor/bin/pint'), ...$args];
        Process::run($command, function (string $type, string $output) {
            if (BaseProcess::ERR === $type) {
                $this->error($output);
            } else {
                $this->line($output);
            }
        });
    }
}
