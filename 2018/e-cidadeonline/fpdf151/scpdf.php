<?
set_time_limit(0);
session_cache_limiter('none');
if ( session_id() == null ) 
   session_start();

if(!defined('DB_BIBLIOT')){
  include_once("libs/db_stdlib.php");
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);
  define('FPDF_FONTPATH','fpdf151/font/');
  require('fpdf151/fpdf.php');
}

class scpdf extends fpdf {
//|00|//scpdf
//|10|//Esta classe � uma extens�o da classe |fpdf|, n�o possui cabe�alho ou rodap�, � classe utilizada
//|10|//na gera��o de formularios tais como: carn�s de parcelamento, recibos, alvar�s, etc
function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
   {
   $h = $this->h;
   $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
   $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
   }
}
//|XX|//
?>
