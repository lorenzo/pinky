<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="no"/>

    <xsl:template match="node()|@*">
        <xsl:copy>
            <xsl:apply-templates select="node()|@*"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template match="center/*">
        <xsl:variable name="classes">
            float-center <xsl:value-of select="@class"/>
        </xsl:variable>
        <xsl:copy>
            <xsl:attribute name="align">center</xsl:attribute>
            <xsl:attribute name="class"><xsl:value-of select="normalize-space($classes)" /></xsl:attribute>
            <xsl:apply-templates select="node()|@*" />
        </xsl:copy>
    </xsl:template>

    <xsl:template match="center/menu/item">
        <xsl:copy>
            <xsl:attribute name="class">float-center <xsl:value-of select="@class"/></xsl:attribute>
            <xsl:apply-templates select="node()|@*" />
        </xsl:copy>
    </xsl:template>
</xsl:stylesheet>
