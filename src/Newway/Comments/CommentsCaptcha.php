<?php namespace Newway\Comments;

use Newway\Comments\Libraries\DataImage;
use Newway\Comments\Libraries\EasyCaptcha\EasyCaptcha;

class CommentsCaptcha
{
    public static function getCaptchaImage($captchaSessionName = 'newway_comments_captcha') {
        self::initSession();
        if (!isset($_SESSION)) {
          session_start();
        }

        $captcha = new EasyCaptcha();

        ob_start();
        $captcha->EasyCaptcha();
        $content = ob_get_clean();

        $_SESSION[$captchaSessionName] = $captcha->getKeyString();

        return DataImage::getDataURI($content, 'image/png');
      }

    public static function checkCaptcha($inputCatpcha, $captchaSessionName = 'newway_comments_captcha') {
        self::initSession();
        $captcha = isset($_SESSION[$captchaSessionName]) ? $_SESSION[$captchaSessionName] : false;
        if($captcha && strtolower($captcha) == strtolower($inputCatpcha)) {
          return true;
        } else {
          return false;
        }
    }

    public static function unsetCaptcha($captchaSessionName = 'newway_comments_captcha') {
      self::initSession();
      unset($_SESSION[$captchaSessionName]);
      if(function_exists('session_unregister'))
        session_unregister('newway_comments_captcha');
    }

    private static function initSession(){
      if (!isset($_SESSION)) {
        session_start();
      }
    }
}
