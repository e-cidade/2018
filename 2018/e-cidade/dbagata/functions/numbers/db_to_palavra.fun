<?
/**
 * Exibe um nתmero por extenso.
 * Utilizando a funחדo db_extenso do dbportal arquivo db_stdlib
 * Esta funחדo sף irב funcionar sףmente dentro do sistema dbseller  
 * Andrio Costa 10/05/2012
 * $string_column י a coluna selecionada 
 * $array_row י a linha atual do relatףrio
 */
 
function db_to_palavra($string_column, $array_row) {
  
  $sString = strtoupper(db_extenso($string_column, true)); 
  return strtr($sString ,'ביםףתגךפדץאטלעשח','ֱֹֽ׃ÚֲÊװֳױְָּׂÙַ'); 
}
