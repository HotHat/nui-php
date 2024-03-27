<?php

namespace Niu;

class Captcha
{
    use InstanceTrait;

    public function create()
    {
        return $this->genCaptcha('base64');
    }

    public function image() {
        $this->genCaptcha();
    }

    private function genCaptcha($type = 'image') {
        $width = 130;
        $height = 45;
        $xMargin = 2;
        $yMargin = 5;
        $size = floor(($width - $xMargin * 5) / 4);
        // $height = $size + 2 * $yMargin;

        $im = imagecreate($width, $height);

        // 第一次对 imagecolorallocate() 的调用会给基于调色板的图像填充背景色
        imagecolorallocate($im, rand(50, 200), rand(0, 155), rand(0, 155));
        $fontColor = imageColorAllocate($im, 255, 255, 255); //字体颜色
        $path = Application::getInstance()->container()->get('path.base');
        $fontStyle = $path . '/resource/RobotoMonoNerdFont.ttf';
        // 产生随机字符
        $authCode = '';
        for ($i = 0; $i < 4; $i++) {
            $randAsciiNumArray = array(rand(48, 57), rand(65, 90));
            $randAsciiNum = $randAsciiNumArray [rand(0, 1)];
            $randStr = chr($randAsciiNum);
            imagettftext(
                $im,
                $size,
                rand(0, 20) - rand(0, 25),
                2*$xMargin + $i * $size,
                $height - $yMargin + rand(1, 5),
                $fontColor,
                $fontStyle,
                $randStr
            );
            $authCode .= $randStr;
        }
        // 干扰线
        for ($i = 0; $i < 8; $i++) {
            $lineColor = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($im, rand(0, $width), 0, rand(0, $width), $height, $lineColor);
        }
        // 干扰点
        for ($i = 0; $i < 250; $i++) {
            imagesetpixel($im, rand(0, $width), rand(0, $height), $fontColor);
        }
        if ($type == 'base64') {
            ob_start();
            imagepng($im);
            $imageData = ob_get_contents();
            ob_end_clean();
            imagedestroy($im);
            return [
                'data' => sprintf('data:%s;base64,%s', 'image/png', base64_encode($imageData)),
                'code' => $authCode
            ];
        } else {
            imagepng($im);
            imagedestroy($im);
        }
    }
}