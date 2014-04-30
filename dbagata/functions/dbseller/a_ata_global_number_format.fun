<?
# function a_ata_global_number_format
# $string_column é a coluna selecionada 

function a_ata_global_number_format($string_column)
{
  global $iCasasDecimais;
  $sRetorno = '';
  if (!empty($string_column)) {
    $sRetorno = number_format($string_column,$iCasasDecimais,',','.');
  }
  return $sRetorno;

}
?>
