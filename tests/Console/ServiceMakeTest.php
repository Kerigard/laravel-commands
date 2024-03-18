<?php

namespace Kerigard\LaravelCommands\Tests\Console;

use Illuminate\Support\Facades\File;
use Kerigard\LaravelCommands\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ServiceMakeTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        if ($this->name() == 'test_service_creation_disabled') {
            $app->config->set('commands.console_commands.make_service.enabled', false);
        }
    }

    public function test_service_creation(): void
    {
        $path = app_path('Services/TestService.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:service', ['name' => 'TestService'])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Services;

class TestService
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_service_creation_disabled(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->artisan('make:service', ['name' => 'TestService'])->assertFailed();
    }

    public function test_service_and_contract_created(): void
    {
        $path = app_path('Services/TestService.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestContract.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:service', ['name' => 'TestService', '--contract' => 'TestContract'])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Services;

use App\Contracts\TestContract;

class TestService implements TestContract
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestContract
{
    //
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_service_and_contract_with_the_same_name_created(): void
    {
        $path = app_path('Services/TestService.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestService.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:service', ['name' => 'TestService', '--contract' => 'TestService'])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Services;

use App\Contracts\TestService as TestServiceContract;

class TestService implements TestServiceContract
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestService
{
    //
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_service_and_contract_with_default_name_created(): void
    {
        $path = app_path('Services/TestService.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestService.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:service', ['name' => 'TestService', '--contract' => true])->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Services;

use App\Contracts\TestService as TestServiceContract;

class TestService implements TestServiceContract
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestService
{
    //
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_service_with_inline_arguments_creation(): void
    {
        $path = app_path('Services/TestService.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestContract.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:service TestService --contract TestContract')->assertSuccessful();
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Services;

use App\Contracts\TestContract;

class TestService implements TestContract
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestContract
{
    //
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }

    public function test_service_and_contract_with_inline_arguments_and_default_name_created(): void
    {
        $path = app_path('Services/TestService.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $contractPath = app_path('Contracts/TestService.php');

        if (File::exists($contractPath)) {
            unlink($contractPath);
        }

        $this->artisan('make:service TestService --contract')->assertSuccessful();
        $this->assertFileExists($path);
        $this->assertFileExists($contractPath);

        $content = <<<CLASS
<?php

namespace App\Services;

use App\Contracts\TestService as TestServiceContract;

class TestService implements TestServiceContract
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));

        $contractContent = <<<CLASS
<?php

namespace App\Contracts;

interface TestService
{
    //
}

CLASS;

        $this->assertEquals($contractContent, file_get_contents($contractPath));
    }
}
