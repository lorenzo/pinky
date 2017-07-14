# Pinky

A PHP Transpiler for ([Inky](https://github.com/zurb/inky)), the templating language made for the  ZURB's Foundation for Email framework.

## Installation

You can install this bundle using composer

    composer require lorenzo/pinky

## Usage and Examples

### Basic Usage

```php
<?php
use Pinky;

$transpiled = Pinky\transformFile('path/to/file.html');

// $transpiled is an instance of DOMDocument
echo $transpiled->saveHTML();
```

You can transpile strings directly:

```php
<?php
use Pinky;

$transpiled = Pinky\transformString('<row>Contents</row>');
echo $transpiled->saveHTML();
```

And you can also transpile many files or strings in batch:

```php
<?php
use Pinky;

$files = [$path1, $path2, $path3];

$transpiled = Pinky\transformManyFiles($files);
foreach ($transpiled as $result) {
    echo $result->saveHTML();
}
```

## License
See the [LICENSE](LICENSE) file for license info (it's the MIT license).

