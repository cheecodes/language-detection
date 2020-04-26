<?php

namespace CheeCodes\LanguageDetection\Tests\Loader;

use CheeCodes\LanguageDetection\Loader\FromCookie;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class FromCookieTest extends TestCase
{
    /** @test */
    public function loads_the_language_from_a_cookie() {
        /** @var FromCookie $fromCookie */
        $fromCookie = $this->app->make(FromCookie::class);

        $request = Request::create('/', 'GET', [], ['lang' => 'de']);

        $this->assertSame('de', $fromCookie->load($request, []));
    }

    /** @test */
    public function name_can_be_changed_by_config() {
        /** @var FromCookie $fromCookie */
        $fromCookie = $this->app->make(FromCookie::class);

        $request = Request::create('/', 'GET', [], ['language' => 'de']);

        $this->assertSame('de', $fromCookie->load($request, ['cookie' => ['name' => 'language']]));
    }

    /** @test */
    public function returns_null_if_no_corresponding_cookie_is_found() {
        /** @var FromCookie $fromCookie */
        $fromCookie = $this->app->make(FromCookie::class);

        $request = Request::create('/');

        self::assertNull($fromCookie->load($request, []));
    }

}
