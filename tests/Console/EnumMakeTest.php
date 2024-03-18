<?php

namespace Kerigard\LaravelCommands\Tests\Console;

use Illuminate\Support\Facades\File;
use Kerigard\LaravelCommands\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class EnumMakeTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        if ($this->name() == 'test_enum_creation_disabled') {
            $app->config->set('commands.console_commands.make_enum.enabled', false);
        }
    }

    public function test_enum_creation(): void
    {
        $path = app_path('Enums/TestEnum.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:enum', ['name' => 'TestEnum'])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Enums;

enum TestEnum
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_enum_creation_disabled(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->artisan('make:enum', ['name' => 'TestEnum'])->assertFailed();
    }

    public function test_enum_with_inline_arguments_creation(): void
    {
        $path = app_path('Enums/TestEnum.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:enum TestEnum')->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Enums;

enum TestEnum
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }
}
