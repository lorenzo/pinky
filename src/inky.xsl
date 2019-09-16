<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="no"/>
    <xsl:variable name="columnCount" select="12" />
    <xsl:strip-space elements="*" />

    <xsl:template match="node()|@*">
        <xsl:copy>
            <xsl:apply-templates select="node()|@*"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template match="//container">
        <table align="center" class="{normalize-space(concat(@class, ' container'))}">
            <xsl:copy-of select="@*[name()!='class']"/>
            <tbody>
                <tr>
                    <td><xsl:apply-templates/></td>
                </tr>
            </tbody>
        </table>
    </xsl:template>

    <xsl:template match="//row">
        <table class="{normalize-space(concat(@class, ' row'))}">
            <xsl:copy-of select="@*[name()!='class']"/>
            <tbody>
                <tr>
                    <xsl:apply-templates/>
                </tr>
            </tbody>
        </table>
    </xsl:template>

    <xsl:template match="//columns">
        <xsl:variable name="colCount" select="count(../*)"/>
        <xsl:variable name="first">
            <xsl:if test="position() = 1">first</xsl:if>
        </xsl:variable>
        <xsl:variable name="last">
            <xsl:if test="position() = last()">last</xsl:if>
        </xsl:variable>
        <xsl:variable name="smallSize">
            <xsl:choose>
                <xsl:when test="@small"><xsl:value-of select="@small" /></xsl:when>
                <xsl:otherwise><xsl:value-of select="$columnCount" /></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="largeSize">
            <xsl:choose>
                <xsl:when test="@large"><xsl:value-of select="@large" /></xsl:when>
                <xsl:when test="@small"><xsl:value-of select="@small" /></xsl:when>
                <xsl:otherwise><xsl:value-of select="floor($columnCount div $colCount)" /></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <th class="{normalize-space(concat('small-', $smallSize, ' large-', $largeSize, ' ',  $first, ' ', $last, ' ', 'columns', ' ', @class))}">
            <xsl:copy-of select="@*[name()!='class' and name()!='large' and name()!='small' and name()!='no-expander']"/>
            <table>
                <tr>
                    <th>
                        <xsl:apply-templates/>
                    </th>
                    <xsl:if test="not(row) and not(*[contains(@class, '.row')])">
                        <xsl:if test="$largeSize = $columnCount and (not(@no-expander) or @no-expander = 'false')">
                            <th class="expander"></th>
                        </xsl:if>
                    </xsl:if>
                </tr>
            </table>
        </th>
    </xsl:template>

    <xsl:template match="//spacer">
        <xsl:variable name="showOrHide">
            <xsl:choose>
                <xsl:when test="@size-sm">hide-for-large</xsl:when>
                <xsl:when test="@size-lg">show-for-large</xsl:when>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="size">
            <xsl:choose>
                <xsl:when test="@size-sm"><xsl:value-of select="@size-sm" /></xsl:when>
                <xsl:when test="@size-lg"><xsl:value-of select="@size-lg" /></xsl:when>
                <xsl:when test="@size"><xsl:value-of select="@size" /></xsl:when>
                <xsl:otherwise>16</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <table class="{normalize-space(concat(@class, ' spacer ', $showOrHide))}">
            <tbody>
                <tr>
                    <td height="{$size}" style="font-size:{$size}px;line-height:{$size}px;">&#xA0;</td>
                </tr>
            </tbody>
        </table>
        <xsl:if test="@size-lg and @size-sm">
            <table class="{normalize-space(concat(@class, ' spacer show-for-large'))}">
                <tbody>
                    <tr>
                        <td height="{@size-lg}" style="font-size:{@size-lg}px;line-height:{@size-lg}px;">&#xA0;</td>
                    </tr>
                </tbody>
            </table>
        </xsl:if>
    </xsl:template>

    <xsl:template match="//button">
        <table class="{normalize-space(concat(@class, ' button'))}">
            <xsl:copy-of select="@*[name()!='class' and name()!='href' and name()!='target']"/>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <xsl:choose>
                                    <xsl:when test="contains(@class,'expand')">
                                        <center>
                                            <a align="center" class="float-center">
                                                <xsl:copy-of select="@target"/>
                                                <xsl:copy-of select="@href"/>
                                                <xsl:apply-templates />
                                            </a>
                                        </center>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a>
                                            <xsl:copy-of select="@target"/>
                                            <xsl:copy-of select="@href"/>
                                            <xsl:apply-templates />
                                        </a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </td>
                        </tr>
                    </table>
                </td>
                <xsl:if test="contains(@class,'expand')">
                    <td class="expander"></td>
                </xsl:if>
            </tr>
        </table>
    </xsl:template>

    <xsl:template match="//menu">
        <table class="{normalize-space(concat(@class, ' menu'))}">
            <xsl:copy-of select="@*[name()!='class']"/>
            <tr>
                <td>
                    <table>
                        <tr>
                            <xsl:apply-templates />
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </xsl:template>

    <xsl:template match="//menu/item">
        <th class="{normalize-space(concat(@class, ' menu-item'))}">
            <a>
                <xsl:copy-of select="@target"/>
                <xsl:copy-of select="@href"/>
                <xsl:apply-templates />
            </a>
        </th>
    </xsl:template>

    <xsl:template match="//callout">
        <table class="callout">
            <xsl:copy-of select="@*[name()!='class']"/>
            <tr>
                <th class="{normalize-space(concat('callout-inner ', @class))}"><xsl:apply-templates /></th>
                <th class="expander"></th>
            </tr>
        </table>
    </xsl:template>

    <xsl:template match="//wrapper">
        <table class="{normalize-space(concat(@class, ' wrapper'))}" align="center">
            <xsl:copy-of select="@*[name()!='class']"/>
            <tr>
                <td class="wrapper-inner"><xsl:apply-templates /></td>
            </tr>
        </table>
    </xsl:template>

    <xsl:template match="//block-grid">
        <table class="{normalize-space(concat(@class, ' block-grid up-', @up))}">
            <xsl:copy-of select="@*[name()!='class' and name()!='up']"/>
            <tr><xsl:apply-templates /></tr>
        </table>
    </xsl:template>

    <xsl:template match="//h-line">
        <table class="{normalize-space(concat(@class, ' h-line'))}">
            <xsl:copy-of select="@*[name()!='class']"/>
            <tr>
                <th>&#160;</th>
            </tr>
        </table>
    </xsl:template>

</xsl:stylesheet>
