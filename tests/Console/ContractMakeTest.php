<?php

namespace Kerigard\LaravelCommands\Tests\Console;

use Illuminate\Support\Facades\File;
use Kerigard\LaravelCommands\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ContractMakeTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        if ($this->name() == 'test_contract_creation_disabled') {
            $app->config->set('commands.console_commands.make_contract.enabled', false);
        } elseif ($this->name() == 'test_action_contract_with_another_method_created') {
            $app->config->set('commands.console_commands.make_action.method', 'execute');
        }
    }

    public function test_contract_creation(): void
    {
        $path = app_path('Contracts/TestContract.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:contract', ['name' => 'TestContract'])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Contracts;

interface TestContract
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_contract_creation_disabled(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->artisan('make:contract', ['name' => 'TestContract'])->assertFailed();
    }

    public function test_action_contract_created(): void
    {
        $path = app_path('Contracts/TestContract.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:contract', ['name' => 'TestContract', '--action' => true])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Contracts;

interface TestContract
{
    /**
     * Execute the action.
     */
    public function handle();
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_action_contract_with_another_method_created(): void
    {
        $path = app_path('Contracts/TestContract.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:contract', ['name' => 'TestContract', '--action' => true])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Contracts;

interface TestContract
{
    /**
     * Execute the action.
     */
    public function execute();
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_contract_with_inline_arguments_creation(): void
    {
        $path = app_path('Contracts/TestContract.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:contract TestContract --action')->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Contracts;

interface TestContract
{
    /**
     * Execute the action.
     */
    public function handle();
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }
}
