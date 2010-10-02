<?php

/**
* GTranslate - A class to comunicate with Google Translate(TM) Service
* Google Translate(TM) API Wrapper
* More info about Google(TM) service can be found on http://code.google.com/apis/ajaxlanguage/documentation/reference.html
* This code has o affiliation with Google (TM) , its a PHP Library that allows to comunicate with public a API
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @author  Christopher Valles <info@christophervalles.com> based on the work of Jose da Silva <jose@josedasilva.net>
* @since   2010/10/02
* @version 0.1
* @licence LGPL v3
*          
*           <code>
*           <?
*               require 'GTranslate.php';
*               
*               Gtranslate::translate($from_iso, $to_iso);
*           </code>
*/

class GTranslate{
   /**
    * Google Translate(TM) Api endpoint
    * 
    * @access private
    * @var string 
    */
    private static $serviceUrl = 'http://ajax.googleapis.com/ajax/services/language/translate';
    
   /**
    * Google Translate (TM) Api Version
    * 
    * @access private
    * @var string 
    */
    private static $apiVersion = '1.0';
    
   /**
    * Holder to the parse of the ini file
    * 
    * @access private
    * @var Array
    */
    private static $availableLanguages = array(
        'af', 'sq', 'am', 'ar', 'hy', 'az', 'eu', 'be', 'bn', 'bh', 'bg', 'my', 'ca', 'chr', 
        'zh', 'zh-CN', 'zh-TW', 'hr', 'cs', 'da', 'dv', 'nl', 'en', 'eo', 'et', 'tl', 'fi', 
        'fr', 'gl', 'ka', 'de', 'el', 'gn', 'gu', 'iw', 'hi', 'hu', 'is', 'id', 'iu', 'ie', 
        'it', 'ja', 'kn', 'kk', 'km', 'ko', 'ku', 'ky', 'lo', 'lv', 'lt', 'mk', 'ms', 'ml', 
        'mt', 'mr', 'mn', 'ne', 'or', 'ps', 'fa', 'pl', 'pt-PT', 'pa', 'ro', 'ru', 'sa', 'sr', 
        'sd', 'si', 'sk', 'sl', 'es', 'sw', 'sv', 'tg', 'ta', 'tl', 'te', 'th', 'bo', 'tr', 
        'uk', 'ur', 'uz', 'ug', 'vi');
    
   /**
    * Google Translate api key
    * 
    * @access public 
    * @var string
    */
    public static $apiKey = NULL;
    
   /**
    * URL Formater to use on request
    * 
    * @access private
    * @param string $from
    * @param tring $to
    * @param string $string
    * @return string $url
    */
    private static function _urlFormat($from, $to, $string){
        $parameters = array(
            'v' => self::$apiVersion,
            'q' => $string,
            'langpair' => sprintf('%s|%s', $from, $to)
        );
        
        if (!empty(self::$apiKey)) {
            $parameters['key'] = self::$apiKey;
        }
        
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $parameters['userip'] = $_SERVER['REMOTE_ADDR'];
        }
        
        $url = '';
        foreach ($parameters as $k => $p) {
            $url .= $k . '=' . urlencode($p) . '&';
        }
        
        return $url;
    }
    
   /**
    * Query the Google(TM) endpoint
    * 
    * @access private
    * @param string $from
    * @param string $to
    * @param string $string
    * @return string $response
    */
    private static function _query($from, $to, $string) {
        $queryUrl = self::_urlFormat($from, $to, $string);
        $response = self::_requestHttp($queryUrl);
        return $response;
    }
    
   /**
    * Query Wrapper for Http Transport
    * 
    * @access private
    * @param string $url
    * @return string $response
    */
    private static function _requestHttp($url) {
        return self::_evalResponse(json_decode(file_get_contents(self::$serviceUrl . '?' . $url)));
    }
    
   /**
    * Response Evaluator, validates the response
    * Throws an exception on error
    * 
    * @access private
    * @param string $json_response
    * @return string $response
    */
    private static function _evalResponse($json_response) {
        switch ($json_response->responseStatus) {
            case 200:
                return $json_response->responseData->translatedText;
                break;
            default:
                throw new Exception('Unable to perform Translation:' . $json_response->responseDetails);
                break;
        }
    }
    
   /**
    * Translate the gived text
    * 
    * @access public
    * @param string $from
    * @param string $to
    * @param string $strinf
    * @return string $response Translated Text
    */
    public static function translate($from, $to, $string) {
        if(in_array($from, self::$availableLanguages) && in_array($to, self::$availableLanguages)){
            return self::_query($from, $to, $string);
        }else{
            throw new Exception('Invalid language used');
        }
    }
}