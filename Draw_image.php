<?php


class Draw_image
{
    const COLORS = [
        'bold' => [0x8B, 0x83, 0x86],
        'purple' => [0xB6, 0xB8, 0xFD],
        'blue' => [0x87, 0xCE, 0xFF],
        'yellow' => [0xEE, 0xEE, 0x00],
        'red' => [0xFF, 0x30, 0x30],
        'green' => [0x40, 0xE0, 0xD0],
    ];

    public static function fill_pic_logo($filename, $new_width, $new_height)
    {

        // $filename = $_GET('filename');
        // $new_width = $_GET('w');
        // $new_height = $_GET('h');

        $img = getimagesize($filename);
        if (!isset($img[2])) {
            return true;
        }elseif ($img[2] == IMAGETYPE_PNG) {
            $current_image = imagecreatefrompng($filename);
        } elseif ($img[2] == IMAGETYPE_JPEG) {
            $current_image = imagecreatefromjpeg($filename);
        } elseif ($img[2] == IMAGETYPE_GIF) {
            $current_image = imagecreatefromgif($filename);
        } else {
            return true;
        }

        $rgb = imagecolorat($current_image, 10, 10);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        $padding_left = ($new_width - $current_width) / 2;
        $padding_top = ($new_height - $current_height) / 2;


        $im = imagecreate($new_width, $new_height);
        imagecolorallocate($im, $r, $g, $b);
        imagecopy($im, $current_image, $padding_left, $padding_top, 0, 0, $img[0], $img[1]);

        header('Content-type: image/png');
        // ob_start();
        imagepng($im, null, 2);
        // $data = ob_get_clean();
        imagedestroy($im);
        // return $data;
    }

    /*
     * 文字居中生成logo
     *
     * @param $word
     * @param $width
     * @param $height
     * @param $color
     * @param $font_size
     */
    public static function draw_word_logo($word, $width = 200, $height = 200, $color = 'purple', $font_size = 88)
    {
        // $word = $_GET('word');
        // $width = $_GET('w');
        // $height = $_GET('h');
        // $color = $_GET('color');
        // $font_size = $_GET('f_size');

        $word = mb_substr($word, 0, 1);
        $font_angle = 0;

        if (!file_exists($font_file = './yahei-bold.ttf')) {
            throw new \Exception(sprintf('%s: yahei-bold.ttf not find', __METHOD__), -1);
        }

        $the_box = self::calculate_text_box($font_size, 0, $font_file, $word);
        if ($the_box === false) {
            throw new \Exception(sprintf('%s: imagettfbbox fail', __METHOD__), -1);
        }

        $x = abs(($width - $the_box['max_x']) / 2);
        $y = abs(($height - $the_box['min_y']) / 2);

        $img = imagecreate($width, $height);
        imagecolorallocate($img, ...self::COLORS[trim($color)]);
        $text_color = imagecolorallocate($img, 255, 255, 255);

        imagettftext(
            $img,
            $font_size,
            $font_angle,
            $x,
            $y,
            $text_color,
            $font_file,
            $word
        );

        header('Content-type: image/png');
        // ob_start();
        imagepng($img);
        // $data = ob_get_clean();
        imagedestroy($img);
        // return $data;
    }

    protected static function calculate_text_box($font_size, $font_angle, $font_file, $text)
    {
        $box = imagettfbbox($font_size, $font_angle, $font_file, $text);
        if (!$box) {
            return false;
        }

        $min_x = min([$box[0], $box[2], $box[4], $box[6]]);
        $max_x = max([$box[0], $box[2], $box[4], $box[6]]);
        $min_y = min([$box[1], $box[3], $box[5], $box[7]]);
        $max_y = max([$box[1], $box[3], $box[5], $box[7]]);

        return ['max_x' => $max_x, 'max_y' => $max_y, 'min_x' => $min_x, 'min_y' => $min_y];
    }
}
