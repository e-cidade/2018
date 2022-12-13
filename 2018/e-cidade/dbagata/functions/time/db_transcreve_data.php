<?php
/**
 * Transcreve uma data 
 * @param  string  $sData     data no formato "Y-m-d"
 * @param  integer $array_row é a linha atual do relatório
 * @return string             data transcrita
 */
function db_transcreve_data($sData, $array_row) {

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

  $sDia = "";
  $sMes = "";
  $sAno = "";

  $aData = explode("-", $sData);
  $sDia = db_numero_to_palavra($aData[2]);
  $sMes = $aMeses[$aData[1]];
  $sAno = db_numero_to_palavra($aData[0]); 


  if (strtoupper($sDia) == "UM") {
    $sDia = "primeiro";
  }
 
 return " $sDia de $sMes do ano de $sAno ";
}


