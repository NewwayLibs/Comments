<?php namespace Newway\Comments\Libraries;

class DataImage {
  public static function getDataURI($image, $mime = '') {
    return 'data: '.$mime.';base64,'.base64_encode($image);
  }
}