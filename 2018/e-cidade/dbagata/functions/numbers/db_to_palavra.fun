<?
/**
 * Exibe um n�mero por extenso.
 * Utilizando a fun��o db_extenso do dbportal arquivo db_stdlib
 * Esta fun��o s� ir� funcionar s�mente dentro do sistema dbseller  
 * Andrio Costa 10/05/2012
 * $string_column � a coluna selecionada 
 * $array_row � a linha atual do relat�rio
 */
 
function db_to_palavra($string_column, $array_row) {
  
  $sString = strtoupper(db_extenso($string_column, true)); 
  return strtr($sString ,'����������������','����������������'); 
}
