<?php

//require '../autoloader.php';

//use EuMatheusGomes\Barcode\InterleavedTwoOfFive;

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

if (isset($_POST) && count($_POST) == 6) {
    $itf = new InterleavedTwoOfFive($_POST['narrow'], $_POST['wide'], $_POST['height']);

    if ($_POST['bar'] != '' && $_POST['space']) {
        $itf->setImages([
            'bar' => $_POST['bar'],
            'space' => $_POST['space']
        ]);
    }

    $barcode = $itf->render($_POST['number']);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interleaved Two of Five</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <br>
      <div class="panel panel-default">
        <div class="panel-body">
          <form method="post">
            <div class="form-group">
              <label for="number">Type any number (must be an even number of characters):</label>
              <?php $value = isset($_POST['number']) ? $_POST['number'] : '' ?>
              <input type="text" name="number" id="number" class="form-control" value="<?= $value ?>">
            </div>

            <div class="form-group">
              <label for="narrow">Set the narrow bar width (in px):</label>
              <?php $value = isset($_POST['narrow']) ? $_POST['narrow'] : '1' ?>
              <input type="text" name="narrow" id="narrow" class="form-control" value="<?= $value ?>">
            </div>

            <div class="form-group">
              <label for="wide">Set the wide bar width (in px):</label>
              <?php $value = isset($_POST['wide']) ? $_POST['wide'] : '3' ?>
              <input type="text" name="wide" id="wide" class="form-control" value="<?= $value ?>">
            </div>

            <div class="form-group">
              <label for="height">Set the bars height (in px):</label>
              <?php $value = isset($_POST['height']) ? $_POST['height'] : '50' ?>
              <input type="text" name="height" id="height" class="form-control" value="<?= $value ?>">
            </div>

            <div class="form-group">
              <label for="bar">Bar image path (leave it blank to use divs):</label>
              <?php $value = isset($_POST['bar']) ? $_POST['bar'] : 'img/bar.png' ?>
              <input type="text" name="bar" id="bar" class="form-control" value="<?= $value ?>">
            </div>

            <div class="form-group">
              <label for="space">Space image path (leave it blank to use divs):</label>
              <?php $value = isset($_POST['space']) ? $_POST['space'] : 'img/space.png' ?>
              <input type="text" name="space" id="space" class="form-control" value="<?= $value ?>">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>

      <?php if (isset($_POST) && count($_POST) > 0): ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Usage:</h4>
<pre>
$itf = new InterleavedTwoOfFive($_POST['narrow'], $_POST['wide'], $_POST['height']);

if ($_POST['bar'] != '' && $_POST['space']) {
    $itf->setImages([
        'bar' => $_POST['bar'],
        'space' => $_POST['space']
    ]);
}

echo $itf->render($_POST['number']);
</pre>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Result:</h4>

          <?= $barcode ?>
        </div>
      </div>
      <?php endif ?>

    </div>
  </body>
</html>
