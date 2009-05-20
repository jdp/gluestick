# Gluestick

Gluestick is a simple interface to the Glue API for Ruby.
Rather than learning a new set of methods, you just use Glue's native methods.

## Get your construction paper out: A Tutorial

To get started, include the **gluestick** gem and instantiate a Glue class.
Every method in the Glue API requires authentication, so you'll also need to use a valid username and password.

    require 'rubygems'
    require 'gluestick'
    glue = Glue.new('username', 'password')

To actually use methods, call the same ones you would as if you were calling them right through HTTP.
If you wanted to use the `user/friends` method, you would do this:

    friends = glue.user.friends(:userId => 'jdp')

See the correspondence? `glue.user.friends` maps itself to `http://api.getglue.com/v1/user/friends`.

Query string parameters are passed through a hash as the first argument. **That's all you need to know.**

## About

2009 [Justin Poliey](http://justinpoliey.com)
