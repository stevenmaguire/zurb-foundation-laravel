[![Total Downloads](https://poser.pugx.org/stevenmaguire/zurb-foundation-laravel/downloads.png)](https://packagist.org/packages/stevenmaguire/zurb-foundation-laravel)
[![Latest Stable Version](https://poser.pugx.org/stevenmaguire/zurb-foundation-laravel/v/stable.png)](https://packagist.org/packages/stevenmaguire/zurb-foundation-laravel)
[![Build Status](https://travis-ci.org/stevenmaguire/zurb-foundation-laravel.png)](https://travis-ci.org/stevenmaguire/zurb-foundation-laravel)
[![Coverage Status](https://coveralls.io/repos/stevenmaguire/zurb-foundation-laravel/badge.png)](https://coveralls.io/r/stevenmaguire/zurb-foundation-laravel)
[![ProjectStatus](http://stillmaintained.com/stevenmaguire/zurb-foundation-laravel.png)](http://stillmaintained.com/stevenmaguire/zurb-foundation-laravel)

Foundation Laravel (Laravel4 Package)
==========

### tl;dr

Build HTML form elements for Foundation inside Laravel 4, including validation error handling. Documentation for the respective frameworks can be found at [Foundation website](http://foundation.zurb.com/docs) and [Laravel website](http://laravel.com/docs).

### Required setup

In the `require` key of `composer.json` file add the following

    "stevenmaguire/zurb-foundation-laravel": "dev-master"

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

```html
<div class="large-8 small-12 columns">
	{{ Form::model($user,array('route' => 'route.name','class' => 'custom')) }}
		<fieldset>
			<legend>Create New Account</legend>				
			{{ Form::label('name', 'Full Name') }}
			{{ Form::text('name',$user->name,array('placeholder'=>'Tell us your whole name')) }}
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email',$user->email,array('placeholder'=>'Valid email used to login and receive information from us')) }}
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password',$user->password,array('placeholder'=>'Gimme your password')) }}
			{{ Form::submit('Create Account',array('class'=>'button')) }}
		</fieldset>
	{{ Form::close() }}
</div>
```

will render without errors

```html
<div class="large-8 small-12 columns">
	<form accept-charset="UTF-8" action="http://host/action/from/route" class="custom" method="post">
		<fieldset>
			<legend>Create New Account</legend> 
			<label for="name">Full Name</label> 
			<input id="name" name="name" placeholder="Tell us your whole name" type="text"> 
			<label for="email">Email</label>
			<input id="email" name="email" placeholder="Valid email used to login and receive information from us" type="text"> 
			<label for="password">Password</label> 
			<input id="password" name="password" placeholder="Gimme your password" type="password" value=""> 
			<input class="button" type="submit" value="Create Account">
		</fieldset>
	</form>
</div>
```

and with errors

```php
$rules = array(
	'name' => array('required','min:3','max:32','regex:/^[a-z ,.\'-]+$/i'),
	'email' => array('required','unique:users,email,%%id%%','regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\+\%\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/'),
	'password' => array('required')
	);
```

```html
<div class="large-8 small-12 columns">
	<form accept-charset="UTF-8" action="http://host/action/from/route" class="custom" method="post">
		<fieldset>
			<legend>Create New Account</legend> 
			<label class="error" for="name">Full Name</label> 
			<input class="error" id="name" name="name" placeholder="Tell us your whole name" type="text" value="">
			<small class="error">The name field is required.</small> 
			<label class="error" for="email">Email</label> 
			<input class="error" id="email" name="email" placeholder="Valid email used to login and receive information from us" type="text" value="">
			<small class="error">The email field is required.</small> 
			<label class="error" for="password">Password</label> 
			<input class="error" id="password" name="password" placeholder="Gimme your password" type="password" value="">
			<small class="error">The password field is required.</small> 
			<input class="button" type="submit" value="Create Account">
		</fieldset>
	</form>
</div>
```

### Currently supported methods

* text
* password
* email
* textarea
* select
* selectRange
* selectMonth
* label
