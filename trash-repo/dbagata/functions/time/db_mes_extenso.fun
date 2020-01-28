<?php
# Retorna o nome do mes por extenso
# $string_column é a coluna selecionada
# $array_row é a linha atual do relatório

function db_mes_extenso($string_column, $array_row)
{
  $aMeses = array();
  $aMeses["01"] = "Janeiro";
  $aMeses["02"] = "Fevereiro";
  $aMeses["03"] = "Marco";
  $aMeses["04"] = "Abril";
  $aMeses["05"] = "Maio";
  $aMeses["06"] = "Junho";
  $aMeses["07"] = "Julho";
  $aMeses["08"] = "Agosto";
  $aMeses["09"] = "Setembro";
  $aMeses["10"] = "Outubro";
  $aMeses["11"] = "Novembro";
  $aMeses["12"] = "Dezembro";
  $mes = date("m");
  return strtr($mes, $aMeses);
}
?>
