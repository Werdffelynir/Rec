<?php

namespace rec;

class Request {

    public static $expireTime = 3600;
    public static $cookieDecode = false;

    public static function post($name=null, $clear=false)
    {
        if(!empty($_POST[$name]))
        {
            if($clear)
                return self::clear($_POST[$name]);
            else
                return trim($_POST[$name]);
        }
        else
        {
            return null;
        }
    }


    public static function get($name=null, $clear=false)
    {
        if(!empty($_GET[$name]))
        {
            if($clear)
                return self::clear($_GET[$name]);
            else
                return trim($_GET[$name]);
        }
        else
        {
            return null;
        }
    }


    public static function value($name, $clear=false)
    {
        if(!empty($_POST[$name]))
        {
            return self::post($name, $clear);
        }else if(!empty($_GET[$name]))
        {
            return self::get($name, $clear);
        }else
            return null;
    }


    public static function clear($dataClear)
    {
        if(is_array($dataClear)){
            foreach ($dataClear as $_dataClear)
                self::clear($_dataClear);
        } else {
            return trim( strip_tags( html_entity_decode( $dataClear ) ) );
        }
    }

    public static function session($name=null, $setValue=null, $clear=false)
    {
        if(!isset($_SESSION))
            session_start();

        if($setValue===null)
        {
            if(!empty($_SESSION[$name]))
            {
                if($clear)
                    return self::getSession($_SESSION[$name], $clear);
                else
                    return trim($_SESSION[$name]);
            }
        }
        else
        {
            self::getSession($name, $clear);
            if(isset($_SESSION[$name]))
                return true;
            else
                return false;
        }

        return null;
    }

    public static function setSession($name, $setValue=null, $clear=false)
    {
        if(!isset($_SESSION))
            session_start();

        if($clear)
            $setValue = self::clear($setValue);

        if(is_array($setValue))
            $_SESSION = $setValue;
        else
            $_SESSION[$name] = $setValue;
    }

    public static function getSession($name=null, $clear=false)
    {
        if(!isset($_SESSION))
            session_start();

        if(!empty($_SESSION[$name]))
        {
            if($clear)
                return self::clear($_SESSION[$name]);
            else
                return trim($_SESSION[$name]);
        }
        else if($name === null)
        {
            if($clear)
                return self::clear($_SESSION);
            else
                return $_SESSION;
        }
    }

    public static function cookie($key, $value=false, $expire = null, $domain = null, $path = null)
    {
        if($value === false)
            return self::getCookie($key);
        else
            return self::setCookie($key, $value, $expire, $domain, $path);
    }


    public static function setCookie($key, $value, $expire = null, $domain = null, $path = null) {

        if ($expire === null)
            $expire = time() + self::$expireTime;

        if ($domain === null)
            $domain = $_SERVER['HTTP_HOST'];

        if ($path === null);
        $path = str_replace(basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['PHP_SELF']);



        if(self::$cookieDecode)
            $value = base64_encode($value);

        return setcookie($key, $value, $expire, $path, $domain);
    }

    public static function getCookie($key)
    {
        if (!empty($_COOKIE[$key]))
        {
            if(self::$cookieDecode)
                return base64_decode($_COOKIE[$key]);
            else
                return $_COOKIE[$key];
        } else {
            return null;
        }
    }


    public static function deleteCookie($key, $domain = null, $path = null) {

        if ($domain === null)
            $domain = $_SERVER['HTTP_HOST'];

        if ($path === null)
            $path = str_replace(basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['PHP_SELF']);

        return setcookie($key, false, time() - 3600, $path, $domain);
    }


    /**
     * Check if request is an ajax request
     * @since  3.3.0
     * @return bool true if ajax
     */
    public static function isAjax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);
    }


    public static function redirect($url=null, $delayForce = 0, $code = 302)
    {

        if(self::isAjax()){
            header('HTTP/1.1 401 Unauthorized', true, 401);
            header('WWW-Authenticate: FormBased');
            die();
        }

        if( !(strpos($url,'http://') > -1) )
            $url = Rec::$url.$url;

        if($delayForce===true){
            if (!headers_sent()) {
                header('Location: ' . $url);
            } else {
                echo "<html><head><title>REDIRECT</title></head><body>";
                echo '<script type="text/javascript">';
                echo 'window.location.href="' . $url . '";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0; url=' . $url . '" />';
                echo '</noscript>';
                echo "</body></html>";
            }
            echo "<!--Headers already!\n-->";
            echo "</body></html>";
            exit;
        }

        if (!headers_sent($file, $line)) {
            if ($delayForce)
                header('Refresh: ' . $delayForce . '; url=' . $url, true);
            else
                header('Location: ' . $url, true, $code);
        } else {
            return true;
        }
    }


    protected static $headerCodes = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );


    public static function sendHeaderCode($code=200)
    {
        $message = self::$headerCodes[$code];
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' ' . $code . ' ' . $message);
    }

} 