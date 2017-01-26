<?php

namespace Pinky;

use DOMDocument;
use XSLTProcessor;

/**
 * Returns the template processor instance
 *
 * @return XSLTProcessor
 */
function createInkyProcessor()
{
    $xslDoc = new DOMDocument();
    $xslDoc->load(__DIR__ . "/inky.xsl");

    $security = XSL_SECPREF_READ_FILE | XSL_SECPREF_READ_NETWORK | XSL_SECPREF_DEFAULT;
    $proc = new XSLTProcessor();
    $proc->setSecurityPrefs($security);
    $proc->importStylesheet($xslDoc);

    return $proc;
}

/**
 * Processes the provided document using the passed XSLTProcessor instance.
 *
 * @param XSLTProcessor $proc A pre-configured processor to apply to the document
 * @param DOMDocument $doc The document to be processed
 * @return DOMDocument
 */
function transformWithProcessor(XSLTProcessor $proc, DOMDocument $doc)
{
    $doc = $proc->transformToDoc($doc);

    // With current logic, only menu tags can be left unprocessed the first pass
    // Check if there are any left before doing a second pass
    if ($doc->getElementsByTagName('menu')->length === 0) {
        return $doc;
    }

    return $proc->transformToDoc($doc);
}

/**
 * Returns the same document after replacing all the relevant tags from the
 * Inky templating language
 *
 * @param DOMDocument $doc The document to process
 * @return DOMDocument
 */
function transformDOM(DOMDocument $doc)
{
    return transformWithProcessor(createInkyProcessor(), $doc);
}

/**
 * Returns a DOMDocument after replacing all the relevant tags from the
 * Inky templating language in the provided file
 *
 * @param string $filePath The file containing the template to process
 * @return DOMDocument
 */
function transformFile($filePath)
{
    return transformDOM(loadTemplateFile($filePath));
}

/**
 * Returns a DOMDocument after replacing all the relevant tags from the
 * Inky templating language in the provided string
 *
 * @param string $xml The documen to process
 * @return DOMDocument
 */
function transformString($xml)
{
    return transformDOM(loadTemplateString($xml));
}

/**
 * Returns a DOMDocument after parsing the contents of the spedified file
 *
 * @param string $filePath The file to parse
 * @return DOMDocument
 */
function loadTemplateFile($filePath)
{
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTMLFile($filePath);
    libxml_use_internal_errors(false);
    return $doc;
}

/**
 * Returns a DOMDocument after parsing the contents of the spedified string
 *
 * @param string $filePath The string to parse
 * @return DOMDocument
 */
function loadTemplateString($xml)
{
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($xml);
    libxml_use_internal_errors(false);
    return $doc;
}

/**
 * Yields each of the transformed files as a DOMDocument
 *
 * @param array $files List of file paths to process
 * @return Traversable
 */
function transformManyFiles($files)
{
    $proc = createInkyProcessor();
    foreach ($files as $k => $file) {
        yield $k => transformWithProcessor($proc, loadTemplateFile($file));
    }
}

/**
 * Yields each of the transformed strings as a DOMDocument
 *
 * @param array $files List of xml strings to process
 * @return Traversable
 */
function transformManyStrings($xmls)
{
    $proc = createInkyProcessor();
    foreach ($xmls as $k => $xml) {
        yield $k => transformWithProcessor($proc, loadTemplateString($xml));
    }
}

/**
 * Yields each of the transformed DOMDocument objects
 *
 * @param array List of DOMDocument objects to process
 * @return Traversable
 */
function transformManyDocs($docs)
{
    $proc = createInkyProcessor();
    foreach ($docs as $k => $doc) {
        yield $k => transformWithProcessor($proc, $doc);
    }
}
