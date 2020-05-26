<?php

namespace CheeCodes\LanguageDetection\Tests;

use CheeCodes\LanguageDetection\Http\Middleware\DetectLanguage;
use CheeCodes\LanguageDetection\LanguageDetectionServiceProvider;
use CheeCodes\LanguageDetection\Service\LoadLanguage;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class DetectLanguageMiddlewareTest extends TestCase
{

    /** @test */
    public function has_a_handle_method() {
        $middleware = $this->app->get(DetectLanguage::class);

        self::assertTrue(method_exists($middleware, 'handle'));
    }

    /** @test */
    public function calls_the_load_language_service_with_the_default_group_if_none_is_given() {
        $middleware = $this->app->get(DetectLanguage::class);
        $request    = new Request();

        $this->mock(LoadLanguage::class, static function (MockInterface $mock) use (&$request) {
            $mock->shouldReceive('__invoke')
                 ->with($request, 'default')
                 ->once();
        });

        $middleware->handle($request, static function () {
        });
    }

    /** @test */
    public function calls_the_load_language_service_with_a_different_group_if_given() {
        $middleware = $this->app->get(DetectLanguage::class);
        $request    = new Request();

        $this->mock(LoadLanguage::class, static function (MockInterface $mock) use (&$request) {
            $mock->shouldReceive('__invoke')
                 ->with($request, 'api')
                 ->once();
        });

        $middleware->handle($request, static function () {
        }, 'api');
    }

    /** @test */
    public function sets_the_locale_to_the_return_value_of_the_language_loader() {
        $middleware = $this->app->get(DetectLanguage::class);
        $this->mock(LoadLanguage::class, static function ($mock) {
            $mock->shouldReceive('__invoke')->andReturn('de');
        });

        $middleware->handle(new Request(), static function () {
        });

        self::assertSame('de', app()->getLocale());
    }

    protected function getPackageProviders($app) {
        return [LanguageDetectionServiceProvider::class];
    }
}
