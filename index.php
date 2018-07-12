<?php
include 'Draw_image.php';

$filename = "https://uimg.cheng95.com/2b/company/profile/logo/1500867446958769.png?h=200&w=200";
// $filename = "http://local.tob.ifchange.com/logo/2b/company/profile/logo/15295738812806.jpg?h=200&w=200";
// $filename = 'http://test2dev.cheng95.com/admin--ll/support/draw_image/draw_word_logo?word=%E9%80%B8&w=200&h=200&color=purple&f_size=88';

Draw_image::draw_word_logo('逸', 200, 200, 'blue', 88);
// Draw_image::fill_pic_logo($filename, 720, 400);