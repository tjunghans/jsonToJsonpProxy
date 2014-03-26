jsonToJsonpProxy
================

A simple php script that can be used as a proxy to send a http request to a json api and provide a jsonp api. I wrote this script for development purposes to be able to access public apis without having to bother about security barriers.

Requirements
============
You will need CURL. The CURL extension will probably just need to be uncommented in the php.ini: http://www.php.net/manual/en/curl.installation.php

How To Use
==========
Create a VHost or use an existing one to access proxy.php.

You should be able to use the proxy like this:

    http://localhost/proxy.php?url=www.example.com/jsonapi?foo=bar&callback=myCallbackFunc
    
If you are using jQuery you would substitue *myCallbackFunc* with *?* as described on http://api.jquery.com/jquery.getjson/#jsonp
