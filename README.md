# license-detector
A License information detector, inspired by [Licensee](https://github.com/licensee/licensee) and relies on data from [`choosealicense.com`](https://choosealicense.com/)

![Travis (.org) branch](https://img.shields.io/travis/SychO9/license-detector/master?style=flat-square)
![php](https://img.shields.io/badge/php->=7.2-red.svg?style=flat-square&color=blue)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square&color=green)

## Installation
Using Composer run the following

```gitattributes
$ composer require sycho/license-detector
```

## Problem
The code uses php's `similar_text()` function to tell which license is the one used, the function is quiet expensive and takes at best one second for the results.

## Usage
Using the `Detector` class's `parse()` or `parseByPath()` methods, you get a `License` object containing data about the license

```php
require '...\vendor\autoload.php';

use LicenseDetector\Detector;

$detector = new Detector();

// By license contents
$license = $detector->parse($contents);

// By file path
$license = $detector->parseByPath($path_to_license);
```

## Contributing
Sign-off your commits, to acknowledge your submission under the license of the project.

Example: `Signed-off-by: Your Name <youremail@example.com>`

## License
This package is released under the MIT License. A full copy of this license is included in the package file.