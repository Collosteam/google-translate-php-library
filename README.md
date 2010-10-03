Description
===========

This is a PHP library as a wrapper to the Google Translate API and it's based on the work of Jose da Silva (http://blog.josedasilva.net/)
The original code can be found on http://code.google.com/p/gtranslate-api-php/

To be able to use this library the file_get_contents PHP function have to be available

Usage
=====

`require 'GTranslate.php'`
`GTranslate::translate($from_iso, $to_iso, 'TEXT TO TRANSLATE');`