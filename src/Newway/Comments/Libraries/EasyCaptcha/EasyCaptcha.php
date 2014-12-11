<?php namespace Newway\Comments\Libraries\EasyCaptcha;

class EasyCaptcha
{
    public $use_symbols = "012345679"; // Здесь Только те буквы, которые Вы хотите выводить
    public $use_symbols_len;

    public $amplitude_min=3; // Минимальная амплитуда волны
    public $amplitude_max=6; // Максимальная амплитуда волны

    public $font_width=18; // Приблизительная ширина символа в пикселях

    public $rand_bsimb_min=5; // Минимальное расстояние между символами (можно отрицательное)
    public $rand_bsimb_max=7; // Максимальное расстояние между символами

    public $margin_left=23;// отступ слева
    public $margin_top=18; // отступ сверху

    public $font_size=15; // Размер шрифта

    public $jpeg_quality = 100; // Качество картинки
    //$back_count = 1; // Количество фоновых рисунков в папке DMT_captcha_fonts идущих по порядку от 1 до $back_count
    public $length = 6;
    public $width = 171;
    public $height = 21;
    public $keystring;

    public function __construct() {
        $this->use_symbols_len = strlen($this->use_symbols);
    }
    function EasyCaptcha()
    {
        $this->keystring = '';
        for ($i = 0; $i < $this->length; $i++) {
            $this->keystring .= $this->use_symbols{mt_rand(0, $this->use_symbols_len - 1)};
        }
        $im = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($im, 222,223,224);
        imagefill($im, 0, 0, $color);

        $rc = mt_rand(120, 140);
        $font_color = imagecolorresolve($im, $rc, $rc, $rc);
        $px = $this->margin_left;
        For ($i = 0; $i < $this->length; $i++) {
            imagettftext(
                $im,
                $this->font_size,
                0,
                $px,
                $this->margin_top,
                $font_color,
                dirname(__FILE__) . "/fonts/novitonova_regular.otf",
                $this->keystring[$i]
            );
            $px += $this->font_width + mt_rand($this->rand_bsimb_min, $this->rand_bsimb_max);
        }

        $rand = mt_rand(0, 1);
        if ($rand) {
            $rand = -1;
        } else {
            $rand = 1;
        }
        $this->wave_region($im, 0, 0, $this->width, $this->height, $rand * mt_rand($this->amplitude_min, $this->amplitude_max), mt_rand(30, 40));
        if (function_exists("imagepng")) {
            header("Content-Type: image/png");
            imagepng($im);
        } else {
          throw new \Exception('Function "imagepng" doesn`t exists');
        }
    }

    function getKeyString()
    {
        return $this->keystring;
    }
    function wave_region($img, $x, $y, $width, $height, $amplitude = 4.5, $period = 30)
    {
      $mult = 2;
      $img2 = imagecreatetruecolor($width * $mult, $height * $mult);
      $color = imagecolorallocate($img2, 0, 0, 0);
      imagefill($img2, 0, 0, $color);

      imagecopyresampled($img2, $img, 0, 0, $x, $y, $width * $mult, $height * $mult, $width, $height);
      for ($i = 0; $i < ($width * $mult); $i += 2) {
        imagecopy($img2, $img2, $x + $i - 2, $y + sin($i / $period) * $amplitude, $x + $i, $y, 2, ($height * $mult));
      }
      imagecopyresampled($img, $img2, $x, $y, 0, 0, $width, $height, $width * $mult, $height * $mult);
      imagedestroy($img2);
    }

    function image_make_pomexi(&$im, $size, $colch)
    {
      $max_x = imagesx($im);
      $max_y = imagesy($im);
      for ($i = 0; $i <= $colch; $i++) {
        $size = mt_rand(10, $size);
        $px1 = mt_rand(0, $max_x);
        $py1 = mt_rand(0, $max_y);
        $col = imagecolorresolve($im, 255, 255, 255);
        $pk1 = mt_rand(-1, 1);
        $pk2 = mt_rand(-1, 1);
        imageline($im, $px1, $py1, $px1 + $size * $pk1, $py1 + $size * $pk2, $col);
      }
    }
}



?>