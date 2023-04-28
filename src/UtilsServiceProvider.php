<?php

namespace Kerigard\LaravelUtils;

use Illuminate\Support\ServiceProvider;
use Kerigard\LaravelUtils\Console\Commands\ActionMakeCommand;
use Kerigard\LaravelUtils\Console\Commands\ContractMakeCommand;
use Kerigard\LaravelUtils\Console\Commands\EnumMakeCommand;
use Kerigard\LaravelUtils\Console\Commands\PintCommand;
use Kerigard\LaravelUtils\Console\Commands\ServiceMakeCommand;
use Kerigard\LaravelUtils\Console\Commands\TraitMakeCommand;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/utils.php', 'utils');
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
            __DIR__.'/../config/utils.php' => config_path('utils.php'),
        ], 'utils-config');

        $this->publishes([
            __DIR__.'/Console/Commands/stubs' => base_path('stubs'),
        ], 'utils-stubs');
    }

    /**
     * Register custom Artisan commands for package.
     */
    private function registerCommands(): void
    {
        $commands = [];

        if (config('utils.console_commands.pint.enabled')) {
            $commands[] = PintCommand::class;
        }
        if (config('utils.console_commands.make_enum.enabled')) {
            $commands[] = EnumMakeCommand::class;
        }
        if (config('utils.console_commands.make_trait.enabled')) {
            $commands[] = TraitMakeCommand::class;
        }
        if (config('utils.console_commands.make_contract.enabled')) {
            $commands[] = ContractMakeCommand::class;
        }
        if (config('utils.console_commands.make_action.enabled')) {
            $commands[] = ActionMakeCommand::class;
        }
        if (config('utils.console_commands.make_service.enabled')) {
            $commands[] = ServiceMakeCommand::class;
        }

        if (! empty($commands)) {
            $this->commands($commands);
        }
    }
}
