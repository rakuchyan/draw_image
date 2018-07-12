<?php


class Draw_image
{
    const COLOR = [
        'black' => [
            'r' => 0,
            'g' => 0,
            'b' => 0,
        ],
        'purple' => [
            'r' => 182,
            'g' => 184,
            'b' => 253,
        ],
        'blue' => [
            'r' => 0,
            'g' => 0,
            'b' => 255,
        ],
        'white' => [
            'r' => 255,
            'g' => 255,
            'b' => 255,
        ],
        'yellow' => [
            'r' => 255,
            'g' => 255,
            'b' => 0,
        ],
    ];

    public static function fill_pic_logo($filename, $new_width, $new_height)
    {

        // $filename = $_GET('filename');
        // $new_width = $_GET('width');
        // $new_height = $_GET('height');

        $mime = '';
        $img = getimagesize($filename);
        if (!empty($img[2])) {
            $mime = image_type_to_mime_type($img[2]);
        }

        if ($mime == 'image/png') {
            $current_image = imagecreatefrompng($filename);
        } elseif ($mime == 'image/jpeg') {
            $current_image = imagecreatefromjpeg($filename);
        } else {
            return true;
        }

        $rgb = imagecolorat($current_image, 10, 10);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        list($current_width, $current_height) = getimagesize($filename);
        $left = 0;
        $top = 0;
        $padding_left = ($new_width - $current_width) / 2;
        $padding_top = ($new_height - $current_height) / 2;


        $im = imagecreate($new_width, $new_height);
        imagecolorallocate($im, $r, $g, $b);
        imagecopy($im, $current_image, $padding_left, $padding_top, $left, $top, $current_width, $current_height);

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
        imagecolorallocate($img, self::COLOR[trim($color)]['r'], self::COLOR[trim($color)]['g'], self::COLOR[trim($color)]['b']);
        $text_color = imagecolorallocate($img, self::COLOR['white']['r'], self::COLOR['white']['g'], self::COLOR['white']['b']);

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