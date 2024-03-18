<?php

namespace Kerigard\LaravelCommands;

use Illuminate\Support\ServiceProvider;
use Kerigard\LaravelCommands\Console\Commands\ActionMakeCommand;
use Kerigard\LaravelCommands\Console\Commands\ContractMakeCommand;
use Kerigard\LaravelCommands\Console\Commands\EnumMakeCommand;
use Kerigard\LaravelCommands\Console\Commands\PintCommand;
use Kerigard\LaravelCommands\Console\Commands\ServiceMakeCommand;
use Kerigard\LaravelCommands\Console\Commands\TraitMakeCommand;

class CommandsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/commands.php', 'commands');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishFiles();
            $this->registerCommands();
        }
    }

    /**
     * Publish files for package.
     */
    private function publishFiles(): void
    {
        $this->publishes([
            __DIR__.'/../config/commands.php' => config_path('commands.php'),
        ], 'commands-config');

        $this->publishes([
            __DIR__.'/Console/Commands/stubs' => base_path('stubs'),
        ], 'commands-stubs');
    }

    /**
     * Register custom Artisan commands for package.
     */
    private function registerCommands(): void
    {
        $commands = [];

        if (config('commands.console_commands.pint.enabled')) {
            $commands[] = PintCommand::class;
        }
        if (config('commands.console_commands.make_enum.enabled')) {
            $commands[] = EnumMakeCommand::class;
        }
        if (config('commands.console_commands.make_trait.enabled')) {
            $commands[] = TraitMakeCommand::class;
        }
        if (config('commands.console_commands.make_contract.enabled')) {
            $commands[] = ContractMakeCommand::class;
        }
        if (config('commands.console_commands.make_action.enabled')) {
            $commands[] = ActionMakeCommand::class;
        }
        if (config('commands.console_commands.make_service.enabled')) {
            $commands[] = ServiceMakeCommand::class;
        }

        if (! empty($commands)) {
            $this->commands($commands);
        }
    }
}
