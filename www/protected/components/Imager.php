<?php
  /**
   * Class Imager
   * v. 1.2
   */
class Imager
{
  const OUT_TYPE_JPEG = 'jpg';

  const OUT_TYPE_PNG = 'png';

  const OUT_TYPE_GIF = 'gif';

  const OUT_TYPE_DEFAULT = self::OUT_TYPE_JPEG;

  /**
   * Варианты расширений файла типа JPEG.
   *
   * @var array
   */
  public $extJPEG = array('jpg', 'jpeg', 'jpe');

  /**
   * Расширение файла типа PNG.
   *
   * @var string
   */
  public $extPNG = 'png';

  /**
   * Расширение файла типа GIF.
   *
   * @var string
   */
  public $extGIF = 'gif';

  /**
   * Разрешает обрезку изображения при масштабировании. Если соотношения сторон у
   * исходного и результирующего изображения не сходятся, то изображение будет
   * максимально вписано в заданную область с сохранением пропорций, а "вылеты"
   * будут обрезаны.
   *
   * При запрете обрезки изображение будет вписано в область полностью, но при
   * несоответствии соотношений изображений пустоты будут заполнены цветом
   * $blank_color, заданным в формате RGB.
   *
   * @var bool
   */
  public $allow_cropping = true;

  /**
   * Основной цвет фона, выходного изображения. Будет виден на изображениях, где
   * вписанное изображение не соответствует пропорциям выходного изображения.
   *
   * @var array
   */
  public $blank_color = array(255, 255, 255);

  /**
   * Качество JPEG изображения при сохранении выходных файлов. Задается в процентах,
   * где 0 - худшее качество и наименьший размер, 100 - лучшее качество и наибольший
   * размер.
   *
   * @var int от 0 до 100
   */
  public $output_quality = 95;

  /**
   * Процент проявления "водяного знака" на выходном изображении. 0 - водяной знак
   * абсолютно прозрачен (не виден), 100 - водяной знак максимально проявлен.
   *
   * @var int от 0 до 100
   */
  public $prot_opacity = 40;

  /**
   * Ширина исходного изображения впикселах.
   *
   * @var int
   */
  public $width = 0;

  /**
   * Высота исходного изображения впикселах.
   *
   * @var int
   */
  public $height = 0;

  /**
   * Код ошибки:
   *    0 - нет ошибок
   *    1 - исходный файл не задан
   *    2 - файл отсутствует, или ошибка в формате
   *    3 - неудалось инициализировать GD поток
   *    4 - неверно задано имя файла защиты, илифайл отсутствует
   *    5 - не удалось получить изображение из файла защиты, формат должен быть png
   *    6 - не загружено изображение защиты
   *
   * @var int
   */
  public $error_code = 0;

  /**
   * Имя файла исходного изображения.
   * @var string
   */
  protected $fn_source;

  /**
   * Имя файла последнего выходного изображения.
   * @var string
   */
  protected $fn_dest;

  /**
   * Ресурс исходного изображения.
   *
   * @var resource
   */
  protected $res_source;

  /**
   * Ресурс последнего выходного изображения.
   * @var resource
   */
  protected $res_dest;

  /**
   * Ресурс изображения защиты (водяные знаки).
   * @var resource
   */
  protected $res_prot;

  /**
   * Конструктор объекта. В качестве аргумента принимает имя файла с исходным
   * изображением.
   *
   * @param string $filename
   */
  public function __construct($filename)
  {
    if (empty($filename))
    {
      $this->error_code = 1;
      return false;
    }

    $ext = pathinfo($filename);
    $ext = mb_strtolower($ext['extension'], 'utf-8');

    if (in_array($ext, $this->extJPEG))
      $this->res_source = @imagecreatefromjpeg($filename);
    elseif ($ext == $this->extPNG)
      $this->res_source = @imagecreatefrompng($filename);
    elseif ($ext == $this->extGIF)
      $this->res_source = @imagecreatefromgif($filename);
    else
      $this->res_source = false;

    if ($this->res_source == false)
    {
      $this->error_code = 2;
      return false;
    }

    $this->fn_source = $filename;
    $this->width = imagesx($this->res_source);
    $this->height = imagesy($this->res_source);
  }

  /**
   * Статичный метод-создатель, задающий начало для текучего интерфейса.
   *
   * @param string $filename
   * @return Imager
   */
  public static function load($filename)
  {
    return new self($filename);
  }

  /**
   * Загрузка изображения защиты для создания "водяных знаков" на выходных
   * изображениях.
   *
   * @param $filename
   * @return $this|bool
   */
  public function loadProtection($filename)
  {
    if (empty($filename) || !file_exists($filename))
    {
      $this->error_code = 4;
      return false;
    }

    $this->res_prot = @imagecreatefrompng($filename);

    if ($this->res_prot == false)
    {
      $this->error_code = 5;
      return false;
    }

    return $this;
  }

  /**
   * Накладывает "водяной знак" на изображение. Степень проявления "водяного
   * знака" регулируется параметром $this->prot_opacity.
   *
   * @return $this
   */
  public function protect()
  {
    if ($this->res_prot)
    {
      $width = imagesx($this->res_prot);
      $height = imagesy($this->res_prot);
      $x = round((imagesx($this->res_dest) - $width) / 2);
      $y = round((imagesy($this->res_dest) - $height) / 2);
      imagecopymerge($this->res_dest, $this->res_prot, $x, $y, 0, 0, $width, $height, $this->prot_opacity);
    }
    else
      $this->error_code = 6;

    return $this;
  }

  /**
   * Точное копирование исходного изображения в выходное. Метод необходим
   * для простого конвертирования изображения из одного формата в другой, так
   * как нельзя сохранить выходное изображение сразу после загрузки исходного -
   * - выходной буфер пуст до тех пор, пока над исходным изображением не будет
   * произведено какое-либо действие. В данном случае в качестве действия
   * выполняется простое копирование изображения.
   *
   * @return $this
   */
  public function copy()
  {
    $this->res_dest = @imagecreatetruecolor($this->width, $this->height);
    imagecopy($this->res_dest, $this->res_source, 0, 0, 0, 0, $this->width, $this->height);

    return $this;
  }

  /**
   * Изменяет размер исходного изображения, помещая результат в буфер выходного
   * изображения. В зависимости от значения параметра allow_cropping, применяется
   * один из двух алгоритмов. Масштабирование выполняется с ресемплированием и
   * сохранением пропорций.
   *
   * @param int $width
   * @param int $height
   * @return $this|bool
   */
  public function resize($width, $height)
  {
    $this->res_dest = @imagecreatetruecolor($width, $height);
    if (!$this->res_dest)
    {
      $this->error_code = 3;
      return false;
    }

    if ($this->allow_cropping)
      return $this->resize_and_crop($width, $height);

    return $this->resize_and_fill($width, $height);
  }

  /**
   * Надстройка над методом resize. Выполяет пропорциональное изменение размера
   * исходного изображения под заданную ширину выходного изображения.
   *
   * @param int $width
   * @return $this|bool
   */
  public function resizeToW($width)
  {
    $height = round(($width/$this->width*$this->height));
    return $this->resize($width, $height);
  }

  /**
   * Надстройка над методом resize. Выполяет пропорциональное изменение размера
   * исходного изображения под заданную высоту выходного изображения.
   *
   * @param int $height
   * @return $this|bool
   */
  public function resizeToH($height)
  {
    $width = round(($height/$this->height*$this->width));
    return $this->resize($width, $height);
  }

  /**
   * Свободное изменение исходного изображения с учетом свободной обрезки. Пропорции
   * задаются также свободно.
   *
   * @param int $src_x
   * @param int $src_y
   * @param int $src_w
   * @param int $src_h
   * @param int $dst_w
   * @param int $dst_h
   * @return $this|bool
   */
  public function freeResize($src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h)
  {
    $this->res_dest = @imagecreatetruecolor($dst_w, $dst_h);
    if (!$this->res_dest)
    {
      $this->error_code = 3;
      return false;
    }

    imagecopyresampled($this->res_dest, $this->res_source, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

    return $this;
  }

  /**
   * Возвращает заданное ($count) количество основных цветов изображения,
   * отсортированных по убыванию. Формат возвращаемых цветов - html (например,
   * #ffffff). Цветовая палитра, возвращаемых цветов сосотоит из 26 цветов,
   * соответственно нет смысла задавать значение $count свыше 26.
   *
   * Метод работает с исходным изображением.
   *
   * @param int $count
   * @return array
   */
  public function mainColors($count = 3)
  {
    $colors =
    $result = array();
    for ($y=0;$y<$this->height;$y++)
      for ($x=0;$x<$this->width;$x++)
      {
        $rgb = imagecolorat($this->res_source, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r <= 85) $r = 0; elseif ($r <= 170) $r = 127; else $r = 255;
        if ($g <= 85) $g = 0; elseif ($g <= 170) $g = 127; else $g = 255;
        if ($b <= 85) $b = 0; elseif ($b <= 170) $b = 127; else $b = 255;
        $htmlColor = '#'.(strlen(dechex($r))<2?'0'.dechex($r):dechex($r)).
                         (strlen(dechex($g))<2?'0'.dechex($g):dechex($g)).
                         (strlen(dechex($b))<2?'0'.dechex($b):dechex($b));
        if (!isset($colors[$htmlColor]))
          $colors[$htmlColor] = 0;
        $colors[$htmlColor]++;
      }

    arsort($colors);
    $colors = array_slice($colors, 0, $count);

    foreach ($colors as $color => $cnt)
      $result[] = $color;

    return $result;
  }

  /**
   * Записывает изображение из выходного буфера в файл указанного формата.
   *
   * @param string $filename
   * @param string $out_type
   * @return $this|bool
   */
  public function write($filename, $out_type = self::OUT_TYPE_DEFAULT)
  {
    if (empty($filename))
      return false;

    if ($out_type == self::OUT_TYPE_JPEG && !imagejpeg($this->res_dest, $filename, $this->output_quality))
      return false;
    elseif ($out_type == self::OUT_TYPE_PNG && !imagepng($this->res_dest, $filename, 9)) // 0..9 - compression level
      return false;
    elseif ($out_type == self::OUT_TYPE_GIF && !imagegif($this->res_dest, $filename))
      return false;

    $this->fn_dest = $filename;

    return $this;
  }

  /**
   * Отправляет изображение из выходного буфера в поток.
   *
   * @return $this|bool
   */
  public function out()
  {
    header('Content-Type: image/jpeg');
    if (!imagejpeg($this->res_dest, null, $this->output_quality))
      return false;

    return $this;
  }

  /**
   * Устанавливет параметр allow_cropping в контексте текучего интерфейса.
   *
   * @param bool $allow
   * @return $this
   */
  public function cropping($allow = true)
  {
    $this->allow_cropping = $allow;
    return $this;
  }

  /**
   * Устанавливет параметр output_quality в контексте текучего интерфейса.
   *
   * @param int $output_quality
   * @return $this
   */
  public function quality($output_quality = 85)
  {
    $this->output_quality = $output_quality;
    return $this;
  }

  /**
   * Устанавливет параметр blank_color в контексте текучего интерфейса.
   *
   * @param array $blank_color
   * @return $this
   */
  public function color($blank_color = array(255, 255, 255))
  {
    $this->blank_color = $blank_color;
    return $this;
  }

  /**
   * Изменение размера исходного изображения с применением обрезки "вылетов".
   *
   * @param int $new_width
   * @param int $new_height
   * @return $this
   */
  private function resize_and_crop($new_width, $new_height)
  {
    $scale =
    $scale_x = $new_width/$this->width*100;
    $scale_y = $new_height/$this->height*100;
    if ($scale_y > $scale_x) $scale = $scale_y;

    $new_width_calc = round($this->width/100*$scale);
    $new_height_calc = round($this->height/100*$scale);

    $diff_width = $new_width_calc-$new_width;
    $diff_height = $new_height_calc-$new_height;

    $offset_x = ($diff_width>0?($diff_width*100/$scale/2):0);
    $offset_y = ($diff_height>0?($diff_height*100/$scale/2):0);

    imagecopyresampled($this->res_dest, $this->res_source, 0, 0, $offset_x, $offset_y, $new_width, $new_height, ($this->width-($offset_x*2)),($this->height-($offset_y*2)));

    return $this;
  }

  /**
   * Изменение размера исходного изображения с применением заливки пустот.
   *
   * @param int $new_width
   * @param int $new_height
   * @return $this
   */
  private function resize_and_fill($new_width, $new_height)
  {
    $scale =
    $scale_x = $new_width/$this->width*100;
    $scale_y = $new_height/$this->height*100;
    if ($scale_y < $scale_x) $scale = $scale_y;

    $new_width_calc = round($this->width/100*$scale);
    $new_height_calc = round($this->height/100*$scale);

    $diff_width = $new_width-$new_width_calc;
    $diff_height = $new_height-$new_height_calc;

    $offset_x = ($diff_width>0?round($diff_width/2):0);
    $offset_y = ($diff_height>0?round($diff_height/2):0);

    $color=imagecolorallocate($this->res_dest, $this->blank_color[0], $this->blank_color[1], $this->blank_color[2]);
    imagefill($this->res_dest, 0, 0, $color);

    imagecopyresampled($this->res_dest, $this->res_source, $offset_x, $offset_y, 0, 0, $new_width_calc, $new_height_calc, $this->width,$this->height);

    return $this;
  }
}