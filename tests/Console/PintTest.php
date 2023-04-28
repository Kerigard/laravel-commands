<?php

namespace Kerigard\LaravelUtils\Tests\Console;

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Kerigard\LaravelUtils\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class PintTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        if ($this->name() == 'test_pint_starting_disabled') {
            $app->config->set('utils.console_commands.pint.enabled', false);
        }
    }

    public function test_pint_started(): void
    {
        Process::fake();

        $this->artisan('pint')->assertSuccessful();

        Process::assertRan(function (PendingProcess $process) {
            return $process->command[0] == config('utils.console_commands.pint.path', './vendor/bin/pint');
        });
    }

    public function test_pint_starting_disabled(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->artisan('pint')->assertFailed();
    }

    public function test_pint_with_arguments_started(): void
    {
        Process::fake();

        $this->artisan('pint', [
            'paths' => ['app/Models', 'routes/api.php'],
            '-v' => true,
            '--test' => true,
            '--dirty' => true,
            '--preset' => 'psr12',
            '--config' => 'pint.json',
        ])->assertSuccessful();

        Process::assertRan(function (PendingProcess $process) {
            return $process->command[0] == config('utils.console_commands.pint.path', './vendor/bin/pint') &&
                $process->command[1] == 'app/Models' &&
                $process->command[2] == 'routes/api.php' &&
                $process->command[3] == '-v' &&
                $process->command[4] == '--test' &&
                $process->command[5] == '--dirty' &&
                $process->command[6] == '--preset' &&
                $process->command[7] == 'psr12' &&
                $process->command[8] == '--config' &&
                $process->command[9] == 'pint.json';
        });
    }

    public function test_pint_with_inline_arguments_started(): void
    {
        Process::fake();

        $this
            ->artisan('pint app/Models routes/api.php -v --test --dirty --preset psr12 --config pint.json')
            ->assertSuccessful();

        Process::assertRan(function (PendingProcess $process) {
            return $process->command[0] == config('utils.console_commands.pint.path', './vendor/bin/pint') &&
                $process->command[1] == 'app/Models' &&
                $process->command[2] == 'routes/api.php' &&
                $process->command[3] == '-v' &&
                $process->command[4] == '--test' &&
                $process->command[5] == '--dirty' &&
                $process->command[6] == '--preset' &&
                $process->command[7] == 'psr12' &&
                $process->command[8] == '--config' &&
                $process->command[9] == 'pint.json';
        });
    }
}
