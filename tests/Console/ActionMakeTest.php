<?php

namespace Kerigard\LaravelUtils\Tests\Console;

use Illuminate\Support\Facades\File;
use Kerigard\LaravelUtils\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ActionMakeTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        if ($this->name() == 'test_action_creation_disabled') {
            $app->config->set('utils.console_commands.make_action.enabled', false);
        } elseif (in_array($this->name(), [
            'test_action_with_another_method_created',
            'test_action_and_contract_with_another_method_created',
        ])) {
            $app->config->set('utils.console_commands.make_action.method', 'execute');
        }
    }

    public function test_action_creation(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:action', ['name' => 'TestAction'])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Actions;

class TestAction
{
    /**
     * Execute the action.
     */
    public function handle()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_action_creation_disabled(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->artisan('make:action', ['name' => 'TestAction'])->assertFailed();
    }

    public function test_action_with_another_method_created(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:action', ['name' => 'TestAction'])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Actions;

class TestAction
{
    /**
     * Execute the action.
     */
    public function execute()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_action_and_contract_created(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestContract.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:action', ['name' => 'TestAction', '--contract' => 'TestContract'])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Actions;

use App\Contracts\TestContract;

class TestAction implements TestContract
{
    /**
     * Execute the action.
     */
    public function handle()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
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

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_action_and_contract_with_the_same_name_created(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestAction.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:action', ['name' => 'TestAction', '--contract' => 'TestAction'])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Actions;

use App\Contracts\TestAction as TestActionContract;

class TestAction implements TestActionContract
{
    /**
     * Execute the action.
     */
    public function handle()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestAction
{
    /**
     * Execute the action.
     */
    public function handle();
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_action_and_contract_with_another_method_created(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestContract.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:action', ['name' => 'TestAction', '--contract' => 'TestContract'])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Actions;

use App\Contracts\TestContract;

class TestAction implements TestContract
{
    /**
     * Execute the action.
     */
    public function execute()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
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

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_action_and_contract_with_default_name_created(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestAction.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:action', ['name' => 'TestAction', '--contract' => true])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Actions;

use App\Contracts\TestAction as TestActionContract;

class TestAction implements TestActionContract
{
    /**
     * Execute the action.
     */
    public function handle()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestAction
{
    /**
     * Execute the action.
     */
    public function handle();
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_action_with_inline_arguments_creation(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestContract.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:action TestAction --contract TestContract')->assertSuccessful();
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Actions;

use App\Contracts\TestContract;

class TestAction implements TestContract
{
    /**
     * Execute the action.
     */
    public function handle()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
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

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_action_and_contract_with_inline_arguments_and_default_name_created(): void
    {
        $path = app_path('Actions/TestAction.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestAction.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:action TestAction --contract')->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Actions;

use App\Contracts\TestAction as TestActionContract;

class TestAction implements TestActionContract
{
    /**
     * Execute the action.
     */
    public function handle()
    {
        //
    }
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestAction
{
    /**
     * Execute the action.
     */
    public function handle();
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }
}
