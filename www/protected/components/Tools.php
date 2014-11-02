<?php
  class Tools
  {
    public static function num_declension($number, $declension1, $declension3, $declension5)
    {
      $declension = $declension5;
      $number = (int) $number;
      $number = "$number";
      if (mb_strlen($number)==1)
        $number = '0'.$number;
      $number = mb_substr($number, mb_strlen($number)-2);
      if ($number[1] > 0 && $number[1] < 5 && $number[0] != '1')
      {
        if ($number[1] == '1')
          $declension = $declension1;
        else
          $declension = $declension3;
      }
      return $declension;
    }

    public static function timeDifference($below_time, $above_time)
    {
      $result = '';

      if ($below_time>0 && $above_time>0 && $below_time < $above_time)
      {
        $diff_s = $above_time - $below_time;
        $diff_d = $diff_h = $diff_m = 0;
        if ($diff_s >= (60*60*24))
        {
          $diff_d = floor($diff_s / (60*60*24));
          $diff_s -= 60*60*24*$diff_d;
          $result .= $diff_d.self::num_declension($diff_d, ' день', ' дня', ' дней');
        }
        if ($diff_s >= (60*60))
        {
          $diff_h = floor($diff_s / (60*60));
          $diff_s -= 60*60*$diff_h;
          if (!empty($result))
            $result .= ', ';
          $result .= $diff_h.self::num_declension($diff_h, ' час', ' часа', ' часов');
        }
        if ($diff_s >= 60)
        {
          $diff_m = floor($diff_s / 60);
          $diff_s -= 60*$diff_m;
          if (!empty($result))
            $result .= ', ';
          $result .= $diff_m.self::num_declension($diff_m, ' минуту', ' минуты', ' минут');
        }
        if ($diff_s > 0)
        {
          if (!empty($result))
            $result .= ', ';
          $result .= $diff_s.self::num_declension($diff_s, ' секунду', ' секунды', ' секунд');
        }
      }

      return $result;
    }

    public static function strLimit($str, $limit = 60, $end = '…')
    {
      if (mb_strlen($str) > $limit)
        $str = trim(mb_substr($str, 0, ($limit-($limit>mb_strlen($end, 'utf-8')?mb_strlen($end, 'utf-8'):0)), 'utf-8')).$end;

      return $str;
    }

    public static function chpu($str)
    {
      $from = array('№', ' ');
      $to = array('no.', '_');
      $unset = array(
        '|', '/', ',', '<', '.', '>', '?', '"', ';', ':', '[', '{', ']', '}', '\\',
        '`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '=', '+', '\'',
      );
      $str = mb_strtolower(self::translit($str), 'utf-8');
      $str = str_replace($from, $to, $str);
      $str = str_replace($unset, '', $str);
      $str = str_replace('___', '_', $str);
      $str = str_replace('__', '_', $str);

      return trim($str, '_');
    }

    public static function translit($str)
    {
      $lit = array(
        'А'=>'A', 'а'=>'a',
        'Б'=>'B', 'б'=>'b',
        'В'=>'V', 'в'=>'v',
        'Г'=>'G', 'г'=>'g',
        'Д'=>'D', 'д'=>'d',
        'Е'=>'E', 'е'=>'e',
        'Ё'=>'E', 'ё'=>'e',
        'Ж'=>'ZH', 'ж'=>'zh',
        'З'=>'Z', 'з'=>'z',
        'И'=>'I', 'и'=>'i',
        'Й'=>'J', 'й'=>'j',
        'К'=>'K', 'к'=>'k',
        'Л'=>'L', 'л'=>'l',
        'М'=>'M', 'м'=>'m',
        'Н'=>'N', 'н'=>'n',
        'О'=>'O', 'о'=>'o',
        'П'=>'P', 'п'=>'p',
        'Р'=>'R', 'р'=>'r',
        'С'=>'S', 'с'=>'s',
        'Т'=>'T', 'т'=>'t',
        'У'=>'U', 'у'=>'u',
        'Ф'=>'F', 'ф'=>'f',
        'Х'=>'H', 'х'=>'h',
        'Ц'=>'C', 'ц'=>'c',
        'Ч'=>'CH', 'ч'=>'ch',
        'Ш'=>'SH', 'ш'=>'sh',
        'Щ'=>'SCH', 'щ'=>'sch',
        'Ъ'=>'', 'ъ'=>'',
        'Ы'=>'Y', 'ы'=>'y',
        'Ь'=>'\'', 'ь'=>'\'',
        'Э'=>'E', 'э'=>'e',
        'Ю'=>'U', 'ю'=>'u',
        'Я'=>'YA', 'я'=>'ya',
      );

      $result = '';
      for ($n=0;$n<mb_strlen($str, 'utf-8');$n++)
      {
        $l = self::charAt($str, $n);
        if ($l !== false && isset($lit[$l]))
          $result .= $lit[$l];
        else
          $result .= $l;
      }

      return $result;
    }

    public static function charAt($str, $pos)
    {
      if (mb_strlen($str, 'utf-8') < ($pos+1))
        return false;

      return mb_substr($str, $pos, 1, 'utf-8');
    }

    public static function price($num, $postfix = ' руб.')
    {
      return number_format($num, 0, ',', ' ').$postfix;
    }

/*
function formatBytes($size, $precision = 2) {
	$base = log($size) / log(1024);
	$suffixes = array('', 'k', 'M', 'G', 'T');

	return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

    public static function fileSize($size, $decs = 2)
    {
      if ($size >= 1000000000000) // Tb
        $div = 1000000000000;
      elseif ($size >= 1000000000) // Gb
        $div = 1000000000;
      elseif ($size >= 1000000) // Mb
        $div = 1000000;
      elseif ($size >= 1000) // Kb
        $div = 1000;
      else // b
        $div = 1;

      return number_format(($size/$div), $decs, ',', ' ').' '.($div == 1000000000000?'Т':($div == 1000000000?'Г':($div == 1000000?'М':($div == 1000?'К':'')))).'б';
    }*/
  }