<?php
use PHPUnit\Framework\TestCase;

use function Pinky\transformString;

class ComponentsTest extends TestCase
{

    public function testCenterAppliesClassToFirstChild()
    {
        $doc =<<<doc
        <center>
            <div></div>
        </center>
doc;
        $expected =<<<doc
        <center>
            <div align="center" class="float-center"></div>
        </center>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCenterDoesRecurse()
    {
        $doc =<<<doc
        <center>
                <center>
                    <p>Hello</p>
                </center>
        </center>
doc;
        $expected =<<<doc
        <center>
            <center align="center" class="float-center">
                <p align="center" class="float-center">Hello</p>
            </center>
        </center>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCenterWithContainerNested()
    {
        $doc =<<<doc
        <center>
                <container>
                    <p>Hello</p>
                </container>
        </center>
doc;
        $expected =<<<doc
        <center>
                <table align="center" class="float-center container">
                    <tbody>
                      <tr><td><p>Hello</p></td></tr>
                    </tbody>
                </table>
        </center>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCenterWithButtonNested()
    {
        $doc =<<<doc
        <center>
            <button href="http://zurb.com">Button</button>
        </center>
doc;
        $expected =<<<doc
        <center>
            <table align="center" class="float-center button">
                <tr>
                  <td>
                    <table>
                      <tr>
                        <td><a href="http://zurb.com">Button</a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
            </table>
        </center>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCenterAppliesClassToMenuItems()
    {
        $doc =<<<doc
        <center>
            <menu>
                <item href="#"></item>
            </menu>
        </center>
doc;
        $expected =<<<doc
        <center>
            <table align="center" class="float-center menu">
              <tr>
                <td>
                  <table>
                    <tr>
                      <th class="float-center menu-item">
                        <a href="#"></a>
                      </th>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </center>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateSimpleButton()
    {
        $doc =<<<doc
        <button href="http://zurb.com">Button</button>
doc;
        $expected =<<<doc
        <table class="button">
            <tr>
              <td>
                <table>
                  <tr>
                    <td><a href="http://zurb.com">Button</a></td>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateButtonWithTarget()
    {
        $doc =<<<doc
        <button href="http://zurb.com" target="_blank">Button</button>
doc;
        $expected =<<<doc
        <table class="button">
            <tr>
              <td>
                <table>
                  <tr>
                    <td><a href="http://zurb.com" target="_blank">Button</a></td>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }


    public function testCreateButtonWithClasses()
    {
        $doc =<<<doc
        <button class="small alert" href="http://zurb.com">Button</button>
doc;
        $expected =<<<doc
        <table class="small alert button">
            <tr>
              <td>
                <table>
                  <tr>
                    <td><a href="http://zurb.com">Button</a></td>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateExpandedButton()
    {
        $doc =<<<doc
        <button class="expand" href="http://zurb.com">Button</button>
doc;
        $expected =<<<doc
        <table class="expand button">
            <tr>
              <td>
                <table>
                  <tr>
                    <td>
                      <center><a href="http://zurb.com" align="center" class="float-center">Button</a></center>
                    </td>
                  </tr>
                </table>
              </td>
              <td class="expander"></td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateMenuWithContent()
    {
        $doc =<<<doc
        <menu>
            <item href="http://zurb.com">Item</item>
        </menu>
doc;
        $expected =<<<doc
        <table class="menu">
            <tr>
              <td>
                <table>
                  <tr>
                    <th class="menu-item"><a href="http://zurb.com">Item</a></th>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }


    public function testCreateMenuWithContentAndTarget()
    {
        $doc =<<<doc
        <menu>
            <item href="http://zurb.com" target="_blank">Item</item>
        </menu>
doc;
        $expected =<<<doc
        <table class="menu">
            <tr>
              <td>
                <table>
                  <tr>
                    <th class="menu-item"><a href="http://zurb.com" target="_blank">Item</a></th>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateMenuWithClasses()
    {
        $doc =<<<doc
        <menu class="vertical">
            <h1>Hey!</h1>
        </menu>
doc;
        $expected =<<<doc
        <table class="vertical menu">
            <tr>
              <td>
                <table>
                  <tr>
                    <h1>Hey!</h1>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateMemuWithoutItemTag()
    {
        $doc =<<<doc
        <menu>
            <th class="menu-item"><a href="http://zurb.com">Item 1</a></th>
        </menu>
doc;
        $expected =<<<doc
        <table class="menu">
            <tr>
              <td>
                <table>
                  <tr>
                    <th class="menu-item"><a href="http://zurb.com">Item 1</a></th>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateCallout()
    {
        $doc =<<<doc
        <callout>Callout</callout>
doc;
        $expected =<<<doc
        <table class="callout">
            <tr>
              <th class="callout-inner">Callout</th>
              <th class="expander"></th>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }


    public function testCreateCalloutWithClasses()
    {
        $doc =<<<doc
        <callout class="primary">Callout</callout>
doc;
        $expected =<<<doc
        <table class="callout">
            <tr>
              <th class="callout-inner primary">Callout</th>
              <th class="expander"></th>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateSpacer()
    {
        $doc =<<<doc
        <spacer size="10"></spacer>
doc;
        $expected =<<<doc
        <table class="spacer">
            <tbody>
              <tr>
                <td height="10" style="font-size:10px;line-height:10px;">&#xA0;</td>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }


    public function testCreateSmallSpacerCorrectly()
    {
        $doc =<<<doc
        <spacer size-sm="10"></spacer>
doc;
        $expected =<<<doc
        <table class="spacer hide-for-large">
            <tbody>
              <tr>
                <td height="10" style="font-size:10px;line-height:10px;">&#xA0;</td>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateSpacerWithDefaultSize()
    {
        $doc =<<<doc
        <spacer></spacer>
doc;
        $expected =<<<doc
        <table class="spacer">
            <tbody>
              <tr>
                <td height="16" style="font-size:16px;line-height:16px;">&#xA0;</td>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateLargeSpacerCorrectly()
    {
        $doc =<<<doc
        <spacer size-lg="10"></spacer>
doc;
        $expected =<<<doc
        <table class="spacer show-for-large">
            <tbody>
              <tr>
                <td height="10" style="font-size:10px;line-height:10px;">&#xA0;</td>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testSpacerGetClassesCopied()
    {
        $doc =<<<doc
        <spacer size="10" class="bgcolor"></spacer>
doc;
        $expected =<<<doc
        <table class="bgcolor spacer">
            <tbody>
              <tr>
                <td height="10" style="font-size:10px;line-height:10px;">&#xA0;</td>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testSpacerElementsWithBothSizes()
    {
        $doc =<<<doc
        <spacer size-sm="10" size-lg="20"></spacer>
doc;
        $expected =<<<doc
        <table class="spacer hide-for-large">
            <tbody>
              <tr>
                <td height="10" style="font-size:10px;line-height:10px;">&#xA0;</td>
              </tr>
            </tbody>
          </table>
          <table class="spacer show-for-large">
            <tbody>
              <tr>
                <td height="20" style="font-size:20px;line-height:20px;">&#xA0;</td>
              </tr>
            </tbody>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateWrapper()
    {
        $doc =<<<doc
        <wrapper class="header"></wrapper>
doc;
        $expected =<<<doc
        <table class="header wrapper" align="center">
            <tr>
              <td class="wrapper-inner"></td>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    public function testCreateHLine()
    {
        $doc =<<<doc
        <h-line class="dotted">
doc;
        $expected =<<<doc
        <table class="dotted h-line">
            <tr>
              <th>&nbsp;</th>
            </tr>
        </table>
doc;
        $this->assertSameDocuments($doc, $expected);
    }

    protected function assertSameDocuments($doc, $expectedS)
    {
        $expected = new DOMDocument();
        $expected->loadHTML($expectedS);
        $result = transformString($doc);
        $result->formatOutput = true;

        $this->assertXmlStringEqualsXmlString($expected->saveXML(), $result->saveXML());
    }
}
