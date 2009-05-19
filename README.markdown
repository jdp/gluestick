# Gluestick

Gluestick is a simple object-oriented implementation of the Glue API for PHP.
It takes advantage of PHP's magic methods to make a more intuitive interface for developers:
rather than learning a new set of methods, you just use Glue's native methods.

## Get your construction paper out: A Tutorial

To get started, include the **glue.php** file and instantiate a Glue class.
Every method in the Glue API requires authentication, so you'll also need to use a valid username and password.

    require 'glue.php';
    $glue = new Glue('username', 'password');

To actually use methods, call the same ones you would as if you were calling them right through HTTP.
If you wanted to use the `user/friends` method, you would do this:

    $friends = $glue->user->friends(array('userId'=>'jdp'));

See the correspondence? `$glue->user->friends` maps itself to `http://api.getglue.com/v1/user/friends`.

Query string parameters are passed through an array as the first argument.

## About

2009 [Justin Poliey](http://justinpoliey.com)
