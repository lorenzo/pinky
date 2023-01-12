<?php

namespace Pinky;

use DOMDocument;
use XSLTProcessor;

/**
 * Returns the template processors instances
 *
 * @return XSLTProcessor[]
 */
function createInkyProcessor()
{
    /*
     * Enable libxml extensions (such as DOM, XMLWriter and XMLReader) to load external entities.
     * Used to prevent such type of warning:
     * Warning: DOMDocument::load(): I/O warning : failed to load external entity "/path/to/vendor/lorenzo/pinky/src/inky.xsl"
     * Deprecated since PHP 8.0
     */
    if (\PHP_VERSION_ID < 80000) {
        libxml_disable_entity_loader(false);
    }

    $general = new DOMDocument();
    $general->load(__DIR__ . "/inky.xsl");

    $centering = new DOMDocument();
    $centering->load(__DIR__ . "/inky-center.xsl");

    $security = \XSL_SECPREF_READ_FILE | \XSL_SECPREF_READ_NETWORK | \XSL_SECPREF_DEFAULT;
    $centerProcessor = new XSLTProcessor();
    $centerProcessor->setSecurityPrefs($security);
    $centerProcessor->importStylesheet($centering);

    $generalProcessor = new XSLTProcessor();
    $generalProcessor->setSecurityPrefs($security);
    $generalProcessor->importStylesheet($general);

    return [$centerProcessor, $generalProcessor];
}

/**
 * Processes the provided document using the passed XSLTProcessor instance.
 *
 * @param XSLTProcessor[] $processors A pre-configured list of processors to apply to the document
 * @param DOMDocument $doc The document to be processed
 * @return DOMDocument
 */
function transformWithProcessor(array $processors, DOMDocument $doc)
{
    foreach ($processors as $processor) {
        $doc = $processor->transformToDoc($doc);
    }
    return $doc;
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
 * @param string $xml The document to process
 * @return DOMDocument
 */
function transformString($xml)
{
    return transformDOM(loadTemplateString($xml));
}

/**
 * Returns a DOMDocument after parsing the contents of the specified file
 *
 * @param string $filePath The file to parse
 * @return DOMDocument
 */
function loadTemplateFile($filePath)
{
    return loadTemplateString(file_get_contents($filePath));
}

/**
 * Returns a DOMDocument after parsing the contents of the specified string
 *
 * @param string $html The string to parse
 * @return DOMDocument
 */
function loadTemplateString($html)
{
    $document = new DOMDocument('1.0', 'UTF-8');
    $internalErrors = libxml_use_internal_errors(true);
    $document->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8'));
    libxml_use_internal_errors($internalErrors);
    $document->formatOutput = true;
    return $document;
}

/**
 * Yields each of the transformed files as a DOMDocument
 *
 * @param string[] $files List of file paths to process
 * @return \Traversable
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
 * @param string[] $xmls List of xml strings to process
 * @return \Traversable
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
 * @param DOMDocument[] $docs List of DOMDocument objects to process
 * @return \Traversable
 */
function transformManyDocs($docs)
{
    $proc = createInkyProcessor();
    foreach ($docs as $k => $doc) {
        yield $k => transformWithProcessor($proc, $doc);
    }
}
