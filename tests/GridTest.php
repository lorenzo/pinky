<?php
use PHPUnit\Framework\TestCase;

use function Pinky\transformString;

class GridTest extends TestCase
{
    public function testFullDocument()
    {
        $doc =<<<doc
        <!doctype html>
          <html>
            <head></head>
            <body>
              <container class="extra"></container>
            </body>
          </html>
doc;
        $expected =<<<doc
        <!doctype html>
          <html>
            <head></head>
            <body>
              <table align="center" class="extra container">
                <tbody>
                  <tr><td></td></tr>
                </tbody>
              </table>
            </body>
          </html>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateAContainerTable()
    {
        $doc = "<container>Something</container>";
        $expected =<<<doc
        <table align="center" class="container">
            <tbody>
              <tr><td>Something</td></tr>
            </tbody>
        </table>
doc;

        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateARow()
    {
        $doc = "<row></row>";
        $expected =<<<doc
        <table class="row">
            <tbody>
                <tr></tr>
            </tbody>
        </table>
doc;

        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateSingleColumnHasPositionClasses()
    {
        $doc = <<<doc
        <row>
            <columns large="12" small="12">One</columns>
        </row>
doc;
        $expected =<<<doc
        <table class="row">
            <tbody>
                <tr>
                    <th class="small-12 large-12 first last columns">
                        <table>
                            <tr>
                                <th>One</th>
                                <th class="expander"></th>
                            </tr>
                        </table>
                    </th>
                </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateColumnNoExpander()
    {
        $doc = '<columns large="12" small="12" no-expander>One</columns>';
        $expected =<<<doc
        <th class="small-12 large-12 first last columns">
            <table>
              <tr>
                <th>One</th>
              </tr>
            </table>
        </th>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateColumnNoExpanderTrue()
    {
        $doc = '<columns large="12" small="12" no-expander="true">One</columns>';
        $expected =<<<doc
        <th class="small-12 large-12 first last columns">
            <table>
              <tr>
                <th>One</th>
              </tr>
            </table>
        </th>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateColumnNoExpanderFalse()
    {
        $doc = '<columns large="12" small="12" no-expander="false">One</columns>';
        $expected =<<<doc
        <th class="small-12 large-12 first last columns">
            <table>
              <tr>
                <th>One</th>
                <th class="expander"></th>
              </tr>
            </table>
        </th>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateTwoColumns()
    {
        $doc =<<<doc
        <row>
            <columns large="6" small="12">One</columns>
            <columns large="6" small="12">Two</columns>
        </row>
doc;
        $expected =<<<doc
        <table class="row">
            <tbody>
                <tr>
                    <th class="small-12 large-6 first columns">
                        <table>
                        <tr>
                            <th>One</th>
                        </tr>
                        </table>
                    </th>
                    <th class="small-12 large-6 last columns">
                        <table>
                        <tr>
                            <th>Two</th>
                        </tr>
                        </table>
                    </th>
                </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateThreeColumns()
    {
        $doc =<<<doc
        <row>
            <columns large="4" small="12">One</columns>
            <columns large="4" small="12">Two</columns>
            <columns large="4" small="12">Three</columns>
        </row>
doc;
        $expected =<<<doc
        <table class="row">
            <tbody>
                <tr>
                    <th class="small-12 large-4 first columns">
                        <table>
                        <tr>
                            <th>One</th>
                        </tr>
                        </table>
                    </th>
                    <th class="small-12 large-4 columns">
                        <table>
                        <tr>
                            <th>Two</th>
                        </tr>
                        </table>
                    </th>
                    <th class="small-12 large-4 last columns">
                        <table>
                        <tr>
                            <th>Three</th>
                        </tr>
                        </table>
                </th>
            </tr>
        </tbody>
    </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }


    public function testAutomaticLargeSizeWhenMissing()
    {
        $doc =<<<doc
        <columns small="4">One</columns>
        <columns small="8">Two</columns>
doc;
        $expected =<<<doc
        <th class="small-4 large-4 first columns">
            <table>
              <tr>
                <th>One</th>
              </tr>
            </table>
          </th>
          <th class="small-8 large-8 last columns">
            <table>
              <tr>
                <th>Two</th>
              </tr>
            </table>
      </th>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testNestedGrid()
    {

        $doc =<<<doc
        <row><columns><row></row></columns></row>
doc;
        $expected =<<<doc
        <table class="row">
            <tbody>
              <tr>
                <th class="small-12 large-12 first last columns">
                  <table>
                    <tr>
                      <th>
                        <table class="row">
                          <tbody>
                            <tr></tr>
                          </tbody>
                        </table>
                      </th>
                    </tr>
                  </table>
                </th>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testAttributesAreTransferred()
    {

        $doc =<<<doc
        <row dir="rtl"><columns dir="rtl" valign="middle" align="center">One</columns></row>
doc;
        $expected =<<<doc
        <table dir="rtl" class="row">
            <tbody>
              <tr>
                <th class="small-12 large-12 first last columns" dir="rtl" valign="middle" align="center">
                  <table>
                    <tr>
                      <th>One</th>
                      <th class="expander"></th>
                    </tr>
                  </table>
                </th>
              </tr>
            </tbody>
      </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testBlockGridSyntax()
    {

        $doc =<<<doc
        <block-grid up="4"></block-grid>
doc;
        $expected =<<<doc
        <table class="block-grid up-4">
            <tr></tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testBlockCopyClassesToFinalOutput()
    {

        $doc =<<<doc
        <block-grid up="4" class="show-for-large"></block-grid>
doc;
        $expected =<<<doc
        <table class="show-for-large block-grid up-4">
            <tr></tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    protected function assertSameDocuments($doc, $expectedS)
    {
        $expected = new DOMDocument();
        $expected->loadHTML($expectedS);
        $result = transformString($doc);
        $this->assertXmlStringEqualsXmlString($expected->saveXML(), $result->saveXML());
    }
}
