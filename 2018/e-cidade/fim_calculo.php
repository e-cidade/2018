<?php 

/**
 * Deletamos as rubricas R913, R914 e R815 das tabelas de calculo, quando o servidor possuir moléstia e o seu
 * vinculo for INATIVO ou PENSIONISTA
 */

LogCalculoFolha::write('Deletando R919, R914 ou R915 para servidores que possuem moléstia e são Inativos ou Pensionistas $opcao_geral:'.$opcao_geral);

$aFolhasNoCalculo = array(1, 3, 4, 5, 8); // Folhas

switch ($opcao_geral) {
  case 1:

    $sTabela = 'gerfsal';
    $sSigla  = 'r14';
    break;
  case 3:
  
    $sTabela = 'gerffer';
    $sSigla  = 'r31';
    break;
  case 4:
  
    $sTabela = 'gerfres';
    $sSigla  = 'r20';
    break;
  
  case 5:
  
    $sTabela = 'gerfs13';
    $sSigla  = 'r35';
    break;
  case 8:
  
    $sTabela = 'gerfcom';
    $sSigla  = 'r48';
    break;
}

if(in_array($opcao_geral, $aFolhasNoCalculo)) { // Não executa para o cálculo de fixo e adiantamento e provisões

  $iInstituicao        = db_getsession("DB_instit");
  $sSqlDeletaMolestia  = "delete from {$sTabela} ";
  $sSqlDeletaMolestia .= "      where {$sSigla}_rubric in ('R913', 'R914', 'R915', 'R997', 'R999')";
  $sSqlDeletaMolestia .= "        and {$sSigla}_regist in ( select rh02_regist ";
  $sSqlDeletaMolestia .= "                                    from rhpessoalmov";
  $sSqlDeletaMolestia .= "                                   inner join  rhregime on rh30_codreg = rh02_codreg ";
  $sSqlDeletaMolestia .= "                                   where rh30_vinculo <> 'A'";
  $sSqlDeletaMolestia .= "                                     and rh02_anousu = {$anousu}";
  $sSqlDeletaMolestia .= "                                     and rh02_mesusu = {$mesusu}";
  $sSqlDeletaMolestia .= "                                     and rh02_instit = {$iInstituicao}";
  $sSqlDeletaMolestia .= "                                     and rh02_portadormolestia = true ";
  $sSqlDeletaMolestia .= "                                 )";
  $sSqlDeletaMolestia .= "        and {$sSigla}_anousu = $anousu";
  $sSqlDeletaMolestia .= "        and {$sSigla}_mesusu = $mesusu";
  $sSqlDeletaMolestia .= "        and {$sSigla}_instit = $iInstituicao";
  $rsDeletaMolestia    = db_query($sSqlDeletaMolestia);
  if (pg_affected_rows($rsDeletaMolestia) > 0 ) {
    LogCalculoFolha::write("Removido ".pg_affected_rows($rsDeletaMolestia)." Registros de IRRF.");
  }
  if (!$rsDeletaMolestia) {
    throw new DBException("Ocorreu um erro ao deletar as rubricas de IRRF para portadores de moléstia. =>".pg_num_rows($rsDeletaMolestia)." ");
  }
}