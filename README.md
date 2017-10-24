# FileLock
[![Latest Stable Version](https://poser.pugx.org/dorantor/filelock/v/stable)](https://packagist.org/packages/dorantor/filelock)
[![License](https://poser.pugx.org/dorantor/filelock/license)](https://packagist.org/packages/dorantor/filelock)

Simple php library providing OOP-interface to file locks.

## Installation
`composer require dorantor/filelock`

## Usage

```php
<?php
 
// create lock object
$lock = new \Dorantor\FileLock('path/to/file');

// ..and work with it
if ($lock->acquire()) {
    // file is locked
    $lock->release();
}
 
// ..or it could be
if (!$lock->acquire()) {
    // failed with lock
    return;
}
// file was locked
$lock->release();
```

## Credits
Idea, interfaces and some code shamelessly taken from [benconstable/lock](https://github.com/BenConstable/lock).