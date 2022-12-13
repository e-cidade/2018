<?php 
namespace PHP;
use SqlFormatter;
/**
 * Utilitários para desenvolvimento
 *
 * @abstract
 */
abstract class Utils {

  /**
   * Retorna o SQL Formatado e Destacado
   *
   * @param String $query
   */
  public static function dump_sql($query) {
    echo SqlFormatter::format($query);
  }

  public static function kill() {
    call_user_func_array("dump", func_get_args());
    die("\nFIM\n");
  }

  public static function dump() {
    return call_user_func_array("dump", func_get_args());
  }
}
