<?php
namespace EuMatheusGomes\Barcode;

class InterleavedTwoOfFive
{
    const START_CODE = '1010';
    const START_SIZE = 'nnnn';

    const STOP_CODE = '101';
    const STOP_SIZE = 'wnn';

    const BAR = '#000';
    const BAR_CODE = '1';

    const SPACE = '#fff';
    const SPACE_CODE = '0';

    private $encoding = [
        0 => 'nnwwn',
        1 => 'wnnnw',
        2 => 'nwnnw',
        3 => 'wwnnn',
        4 => 'nnwnw',
        5 => 'wnwnn',
        6 => 'nwwnn',
        7 => 'nnnww',
        8 => 'wnnwn',
        9 => 'nwnwn'
    ];

    private $images = [];
    private $sizes = [];
    private $height;

    public function __construct($narrow = 1, $wide = 3, $height = 50)
    {
        $this->sizes = ['n' => $narrow, 'w' => $wide];
        $this->height = $height;
    }

    public function render($numberStr)
    {
        if (strlen($numberStr) % 2 != 0) {
            throw new \Exception('The barcode number must have an even amount of characters.');
        }

        $drawing = $this->guard('start');
        $charsArray = str_split($numberStr);

        for ($i = 0; $i < count($charsArray); $i += 2) {

            $num1 = $this->encode($charsArray[$i], self::BAR_CODE);
            $num2 = $this->encode($charsArray[$i + 1], self::SPACE_CODE);

            $drawing .= $this->draw($num1, $num2);
        }

        $drawing .= $this->guard('stop');
        return $drawing;
    }

    public function guard($edge)
    {
        if ($edge == 'start') {
            $code = str_split(self::START_CODE);
            $size = str_split(self::START_SIZE);
        }

        if ($edge == 'stop') {
            $code = str_split(self::STOP_CODE);
            $size = str_split(self::STOP_SIZE);
        }

        $html = '';

        foreach ($code as $i => $v) {
            $num = str_repeat($v, $this->sizes[$size[$i]]);
            $num = str_split($num);

            foreach ($num as $n) {
                $html .= $this->html($n);
            }
        }

        return $html;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    private function draw($num1, $num2)
    {
        $drawing = '';

        for ($i = 0; $i < 5; $i++) {

            $num1[$i] = str_split($num1[$i]);
            foreach ($num1[$i] as $char) {

                $drawing .= $this->html($char);
            }

            $num2[$i] = str_split($num2[$i]);
            foreach ($num2[$i] as $char) {

                $drawing .= $this->html($char);
            }
        }

        return $drawing;
    }

    private function html($char)
    {
        $format = $this->divFormat();
        $param = $this->divParam($char);

        if (isset($this->images['bar']) && isset($this->images['space'])) {
            $format = $this->imgFormat();
            $param = $this->imgParam($char);
        }

        return sprintf($format, $param);
    }

    private function encode($char, $barOrSpace)
    {
        $encoding = str_split($this->encoding[$char]);

        foreach ($encoding as $i => $v) {
            $encoding[$i] = str_repeat($barOrSpace, $this->sizes[$v]);
        }

        return $encoding;
    }

    private function divFormat()
    {
        $style = 'style="display:inline-block; width:1px; height:' . $this->height . 'px; background:%s"';
        return '<div '. $style .'></div>';
    }

    private function divParam($char)
    {
        if ($char == 0) {
            return self::SPACE;
        }

        return self::BAR;
    }

    private function imgFormat()
    {
        $style = 'style="display:inline-block; width:1px; height:' . $this->height . 'px"';
        return '<img src="%s" '. $style .'>';
    }

    private function imgParam($char)
    {
        if ($char == 0) {
            return $this->images['space'];
        }

        return $this->images['bar'];
    }
}
