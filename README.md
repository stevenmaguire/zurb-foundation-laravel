[![Total Downloads](https://poser.pugx.org/stevenmaguire/foundation/downloads.png)](https://packagist.org/packages/stevenmaguire/foundation)
[![Latest Stable Version](https://poser.pugx.org/stevenmaguire/foundation/v/stable.png)](https://packagist.org/packages/stevenmaguire/foundation)
[![Build Status](https://travis-ci.org/stevenmaguire/foundation.png)](https://travis-ci.org/stevenmaguire/foundation)

Foundation 4x4 (Laravel4 Package)
==========

### tl;dr

Build HTML form elements for Foundation 4 inside Laravel 4

### Required setup

In the `require` key of `composer.json` file add the following

    "stevenmaguire/foundation": "dev-master"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'Stevenmaguire\Foundation\FoundationServiceProvider'` to the end of the `$providers` array

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Stevenmaguire\Foundation\FoundationServiceProvider',

),
```

### Usage

When composing forms using the blade view engine within Laravel 4, this package will intercept basic Form::method requests and compose form elements that are structured for use in a Foundation 4 based presentation.