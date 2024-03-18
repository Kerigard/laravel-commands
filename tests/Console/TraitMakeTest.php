<?php

namespace Kerigard\LaravelCommands\Tests\Console;

use Illuminate\Support\Facades\File;
use Kerigard\LaravelCommands\Tests\TestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class TraitMakeTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        if ($this->name() == 'test_trait_creation_disabled') {
            $app->config->set('commands.console_commands.make_trait.enabled', false);
        }
    }

    public function test_trait_creation(): void
    {
        $path = app_path('Traits/TestTrait.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:trait', ['name' => 'TestTrait'])->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Traits;

trait TestTrait
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }

    public function test_trait_creation_disabled(): void
    {
        $this->expectException(CommandNotFoundException::class);
        $this->artisan('make:trait', ['name' => 'TestTrait'])->assertFailed();
    }

    public function test_trait_with_inline_arguments_creation(): void
    {
        $path = app_path('Traits/TestTrait.php');

        if (File::exists($path)) {
            unlink($path);
        }

        $this->artisan('make:trait TestTrait')->assertSuccessful();
        $this->assertFileExists($path);

        $content = <<<CLASS
<?php

namespace App\Traits;

trait TestTrait
{
    //
}

CLASS;

        $this->assertEquals($content, file_get_contents($path));
    }
}
