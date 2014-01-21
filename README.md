drupal-bootstrap
================

Lightweight wrapper to bootstrap Drupal in OO context to make native API calls.


**Work-in-progress**

## Usage

```php
    <?php

    require_once 'vendor/autoload.php';
    $drupal = new \Wesnick\DrupalBootstrap\Drupal7("/path/to/drupal");
    $drupal->doBootstrap();

    // Do stuff with Drupal API
    $node = node_load(1);

```

## Installation

Use composer.

## Requirements

PHP 5.3

## Contributing

Fork and issue a Pull Request.

## Running the Tests

```
$ phpunit
```

## Acknowledgements

The basic functionality is borrowed from drupal/drupalextensions

## License

Released under the MIT License. See the bundled LICENSE file for details.
