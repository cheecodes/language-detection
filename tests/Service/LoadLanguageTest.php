<?php

namespace CheeCodes\LanguageDetection\Tests\Service;

use CheeCodes\LanguageDetection\Loader\LoaderContract;
use CheeCodes\LanguageDetection\Service\LoadLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;

class LoadLanguageTest extends TestCase
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /** @test */
    public function returns_the_language_to_default_if_no_loader_is_given() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'default' => 'de',
                          'loaders' => [],
                      ]
                  )
              );
        $this->assertEquals('de', $loadLanguage($this->request));
    }

    protected function getConfig(array $override = []): array {
        return array_merge([
            'default' => 'de',
            'allowed' => ['de', 'es'],
            'loaders' => [],
        ], $override);
    }

    /** @test */
    public function runs_the_default_group_if_none_is_given() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'default' => 'de',
                          'allowed' => ['ru', 'es'],
                          'loaders' => [
                              'default' => [
                                  RussianLoader::class,
                              ],
                              'api'     => [
                                  SpanishLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        $this->assertEquals('ru', $loadLanguage($this->request));
    }

    /** @test */
    public function supports_running_different_groups_of_loaders() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'default' => 'de',
                          'loaders' => [
                              'default' => [
                                  NullLoader::class,
                              ],
                              'api'     => [
                                  SpanishLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        $this->assertEquals('es', $loadLanguage($this->request, 'api'));
    }

    /** @test */
    public function returns_the_default_if_no_loader_can_return_a_language() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'default' => 'de',
                          'loaders' => [
                              'default' => [
                                  NullLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        self::assertEquals('de', $loadLanguage($this->request));
    }

    /** @test */
    public function throws_an_exception_if_an_incorrect_loader_is_given() {
        $loadLanguage = $this->app->get(LoadLanguage::class);
        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'loaders' => [
                              'default' => [
                                  InvalidLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        $this->expectException(InvalidArgumentException::class);

        $loadLanguage($this->request);
    }

    /** @test */
    public function returns_the_language_to_the_value_from_the_loaders() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'loaders' => [
                              'default' => [
                                  SpanishLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        self::assertEquals('es', $loadLanguage($this->request));
    }

    /** @test */
    public function skips_loaders_that_return_null() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'loaders' => [
                              'default' => [
                                  NullLoader::class,
                                  SpanishLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        self::assertEquals('es', $loadLanguage($this->request));
    }

    /** @test */
    public function returns_the_value_of_the_first_matching_loader() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'allowed' => ['ru', 'es'],
                          'loaders' => [
                              'default' => [
                                  SpanishLoader::class,
                                  RussianLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        self::assertEquals('es', $loadLanguage($this->request));
    }

    /** @test */
    public function only_respects_languages_which_have_been_allowed() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'allowed' => ['es'],
                          'loaders' => [
                              'default' => [
                                  RussianLoader::class,
                                  SpanishLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        self::assertEquals('es', $loadLanguage($this->request));
    }

    /** @test */
    public function returns_the_default_even_if_not_included_in_allowed_languages() {
        $loadLanguage = $this->app->get(LoadLanguage::class);

        Config::shouldReceive('get')
              ->with('language-detection', [])
              ->andReturn(
                  $this->getConfig([
                          'default' => 'ru',
                          'allowed' => ['es'],
                          'loaders' => [
                              'default' => [
                                  RussianLoader::class,
                              ],
                          ],
                      ]
                  )
              );

        self::assertEquals('ru', $loadLanguage($this->request));
    }

    protected function setUp(): void {
        parent::setUp();

        $this->request = new Request();
    }
}

class RussianLoader implements LoaderContract
{
    public function load(Request $request, array $config): ?string {
        return 'ru';
    }
}

class SpanishLoader implements LoaderContract
{
    public function load(Request $request, array $config): ?string {
        return 'es';
    }
}

class NullLoader implements LoaderContract
{
    public function load(Request $request, array $config): ?string {
        return null;
    }
}

class InvalidLoader
{
}
