<?php

namespace FloatHelper;

class FloatConvertHelper
{
  const RANGES = array(
    '', 'thousand', 'million', 'billion'
  );

  const small_numbers = array(
    '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
    'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
  );

  const big_numbers = array(
    '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
  );

  private static $ranges = array();

  /**
   * Convert float number to string
   *
   * @param float $number
   * @return string
   */
  public static function toText(float $number): string
  {
    $out = '';
    // Diff number by dot
    $diff_left_ang_right_part = explode('.', $number);
    $left_part = reset($diff_left_ang_right_part);
    $right_part = count($diff_left_ang_right_part) == 2 ? (int)array_pop($diff_left_ang_right_part) : 0;

    $left_part_arr = explode(',', number_format($left_part, 0));

    // Remove unused ranges
    self::$ranges = self::RANGES;
    array_splice(self::$ranges, count($left_part_arr));
    self::$ranges = array_reverse(self::$ranges);

    // Left part
    foreach ($left_part_arr as $rang => $item)
    {
      $out .= self::convert($item);
      $out .= ' ' . self::$ranges[$rang] . ' ';
    }

    // Right part
    if ((int)$right_part)
    {
      $out .= $left_part_arr[0] == 0 ? "zero" : '';
      $out .= " and ";
      $out .= self::convert($right_part);
    }

    $out = preg_replace('!\s+!', ' ', $out);

    return $out;
  }

  /**
   * Convert to string
   *
   * @param int $number
   * @return string
   */
  private static function convert(int $number): string
  {
    if ($number < 20)
    {
      $out = self::small_numbers[$number];
    }
    else
    {
      $number_str = (string)$number;

      if ($number < 100)
      {
        $out = self::big_numbers[$number_str[0]];
        $out .= ' ' . self::small_numbers[$number_str[1]];
      }
      else
      {
        $out = self::small_numbers[$number_str[0]];
        $out .= " hundred ";

        $out .= self::convert(substr($number_str, 1, 2));
      }
    }

    return $out;
  }
}