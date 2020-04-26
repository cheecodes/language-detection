<?php

namespace CheeCodes\LanguageDetection\Tests\Loader;

use CheeCodes\LanguageDetection\Loader\FromHeader;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;

class FromHeaderTest extends TestCase
{
    /** @test */
    public function loads_the_language_from_a_header() {
        /** @var FromHeader $fromHeader */
        $fromHeader = $this->app->make(FromHeader::class);

        $request = Request::create('/', 'GET');
        $request->headers->set('x-lang', 'de');

        $this->assertSame('de', $fromHeader->load($request, []));
    }

    /** @test */
    public function name_can_be_changed_by_config() {
        /** @var FromHeader $fromHeader */
        $fromHeader = $this->app->make(FromHeader::class);

        $request = Request::create('/', 'GET');
        $request->headers->set('x-language', 'de');

        $this->assertSame('de', $fromHeader->load($request, ['header' => ['name' => 'x-language']]));
    }

    /** @test */
    public function returns_null_if_no_corresponding_header_is_found() {
        /** @var FromHeader $fromHeader */
        $fromHeader = $this->app->make(FromHeader::class);

        $request = Request::create('/');

        self::assertNull($fromHeader->load($request, []));
    }
}
