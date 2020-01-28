<?php
namespace ECidade\V3\Extension;

class Encode {

  /**
   * @param string $string
   * @return string
   */
  static public function toUTF8($string) {
    return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
  }

  /**
   * @param string $string
   * @return string
   */
  static public function toISO($string) {
    return mb_convert_encoding($string, "ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
  }

  static public function bin2hex($string) {
    return bin2hex($string);
  }

  static public function hex2bin($string) {
    return hex2bin($string);
  }

}
