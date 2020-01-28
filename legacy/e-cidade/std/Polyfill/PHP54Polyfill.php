<?php 
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
class PHP54Polyfill {

  public static function sendHTTPResponseCode($code = NULL) {

    if ($code !== NULL) {

      switch ($code) {

        case 100: $text = 'Continue';                      break;
        case 101: $text = 'Switching Protocols';           break;
        case 200: $text = 'OK';                            break;
        case 201: $text = 'Created';                       break;
        case 202: $text = 'Accepted';                      break;
        case 203: $text = 'Non-Authoritative Information'; break;
        case 204: $text = 'No Content';                    break;
        case 205: $text = 'Reset Content';                 break;
        case 206: $text = 'Partial Content';               break;
        case 300: $text = 'Multiple Choices';              break;
        case 301: $text = 'Moved Permanently';             break;
        case 302: $text = 'Moved Temporarily';             break;
        case 303: $text = 'See Other';                     break;
        case 304: $text = 'Not Modified';                  break;
        case 305: $text = 'Use Proxy';                     break;
        case 400: $text = 'Bad Request';                   break;
        case 401: $text = 'Unauthorized';                  break;
        case 402: $text = 'Payment Required';              break;
        case 403: $text = 'Forbidden';                     break;
        case 404: $text = 'Not Found';                     break;
        case 405: $text = 'Method Not Allowed';            break;
        case 406: $text = 'Not Acceptable';                break;
        case 407: $text = 'Proxy Authentication Required'; break;
        case 408: $text = 'Request Time-out';              break;
        case 409: $text = 'Conflict';                      break;
        case 410: $text = 'Gone';                          break;
        case 411: $text = 'Length Required';               break;
        case 412: $text = 'Precondition Failed';           break;
        case 413: $text = 'Request Entity Too Large';      break;
        case 414: $text = 'Request-URI Too Large';         break;
        case 415: $text = 'Unsupported Media Type';        break;
        case 500: $text = 'Internal Server Error';         break;
        case 501: $text = 'Not Implemented';               break;
        case 502: $text = 'Bad Gateway';                   break;
        case 503: $text = 'Service Unavailable';           break;
        case 504: $text = 'Gateway Time-out';              break;
        case 505: $text = 'HTTP Version not supported';    break;
        default:
          $text = ' ';
          break;
      }

      $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
      header($protocol . ' ' . $code . ' ' . $text);
      $GLOBALS['http_response_code'] = $code;

    } else {
      $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
    }

    return $code;
  }

  public static function hex2bin($data) {

    static $old;
    if ($old === null) {
      $old = version_compare(PHP_VERSION, '5.2', '<');
    }
    $isobj = false;
    if (is_scalar($data) || (($isobj = is_object($data)) && method_exists($data, '__toString'))) {
      if ($isobj && $old) {
        ob_start();
        echo $data;
        $data = ob_get_clean();
      }
      else {
        $data = (string) $data;
      }
    }
    else {
      trigger_error(__FUNCTION__.'() expects parameter 1 to be string, ' . gettype($data) . ' given', E_USER_WARNING);
      return;//null in this case
    }
    $len = strlen($data);
    if ($len % 2) {
      trigger_error(__FUNCTION__.'(): Hexadecimal input string must have an even length', E_USER_WARNING);
      return false;
    }
    if (strspn($data, '0123456789abcdefABCDEF') != $len) {
      trigger_error(__FUNCTION__.'(): Input string must be hexadecimal string', E_USER_WARNING);
      return false;
    }
    return pack('H*', $data);
  }

}

