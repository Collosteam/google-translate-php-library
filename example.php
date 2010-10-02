<?php
require 'GTranslate.php';

$translateString = "Hola Mundo!";
try{
    echo Gtranslate::translate('es', 'en', $translateString);
} catch (Exception $e) {
    echo $e->getMessage();
}