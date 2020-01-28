<?php

namespace ECidade\V3\Extension;

class Glob {

  /**
   * @param string $pattern
   * @param string $path
   * @param bool $recursive
   * @return array
   */
  public static function find($pattern = '*', $path = null, $recursive = false, $flags = GLOB_BRACE) {

    $files = glob($path . $pattern, $flags);

    if (!$recursive) {
      return $files;
    }

    $paths = glob($path . '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT|$flags);

    foreach ($paths as $path) { 
      $files = array_merge($files, static::find($pattern, $path, $recursive, $flags)); 
    }

    return $files;
  }

  /**
   * Returns a regexp which is the equivalent of the glob pattern.
   *
   * @author Fabien Potencier <fabien@symfony.com> PHP port
   * @author Richard Clamp <richardc@unixbeard.net> Perl version
   * @param string $glob The glob pattern
   * @param bool $strictLeadingDot
   * @param bool $strictWildcardSlash
   * @param string $delimiter Optional delimiter
   *
   * @return string regex The regexp
   */
  public static function toRegex($glob, $strictLeadingDot = true, $strictWildcardSlash = true, $delimiter = '#') {

    $firstByte = true;
    $escaping = false;
    $inCurlies = 0;
    $regex = '';
    $sizeGlob = strlen($glob);
    for ($i = 0; $i < $sizeGlob; ++$i) {
      $car = $glob[$i];
      if ($firstByte) {
        if ($strictLeadingDot && '.' !== $car) {
          $regex .= '(?=[^\.])';
        }
        $firstByte = false;
      }
      if ('/' === $car) {
        $firstByte = true;
      }
      if ('.' === $car || '(' === $car || ')' === $car || '|' === $car || '+' === $car || '^' === $car || '$' === $car) {
        $regex .= "\\$car";
      } elseif ('*' === $car) {
        $regex .= $escaping ? '\\*' : ($strictWildcardSlash ? '[^/]*' : '.*');
      } elseif ('?' === $car) {
        $regex .= $escaping ? '\\?' : ($strictWildcardSlash ? '[^/]' : '.');
      } elseif ('{' === $car) {
        $regex .= $escaping ? '\\{' : '(';
        if (!$escaping) {
          ++$inCurlies;
        }
      } elseif ('}' === $car && $inCurlies) {
        $regex .= $escaping ? '}' : ')';
        if (!$escaping) {
          --$inCurlies;
        }
      } elseif (',' === $car && $inCurlies) {
        $regex .= $escaping ? ',' : '|';
      } elseif ('\\' === $car) {
        if ($escaping) {
          $regex .= '\\\\';
          $escaping = false;
        } else {
          $escaping = true;
        }
        continue;
      } else {
        $regex .= $car;
      }
      $escaping = false;
    }
    return $delimiter.'^'.$regex.'$'.$delimiter;
  }

}
