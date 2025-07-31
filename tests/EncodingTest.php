<?php
use PHPUnit\Framework\TestCase;

use function Pinky\transformString;

class EncodingTest extends TestCase
{
    /**
     * @param string $expected
     * @param string $input
     *
     * @dataProvider provideStrings
     */
    public function testMultiByteContent($expected, $input)
    {
        $this->assertEquals($expected, trim(transformString($input)->saveHTML()));
    }

    public static function provideStrings()
    {
        yield [
            '<html><body><p>ASCII only</p></body></html>',
            '<p>ASCII only</p>',
        ];
        yield [
            '<html><body><p>Twoje zam&oacute;wienie oczekuje na wp&#322;at&#281; zadatku &#127475;&#127473;</p></body></html>',
            '<p>Twoje zamówienie oczekuje na wpłatę zadatku 🇳🇱</p>',
        ];
        yield [
            '<html><body><p>&#1055;&#1088;&#1080;&#1074;&#1077;&#1090; &#1084;&#1080;&#1088;!</p></body></html>',
            '<p>Привет мир!</p>',
        ];
    }
}
