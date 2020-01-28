<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_libpessoal.php");
require_once("libs/db_utils.php");
require_once("classes/db_ipe_classe.php");
$clipe    = new cl_ipe;

$oGet = db_utils::postmemory($HTTP_GET_VARS);

db_sel_cfpess($oGet->iAno, $oGet->iMes, "r11_codipe, r11_percentualipe");

if ($oGet->sTipo == 'm') {

  $head2 = "RELATÓRIO DE MANUTENÇÃO DO IPE";
    
} elseif($oGet->sTipo == 'i') {

  $head2 = "RELATÓRIO DE INCLUSÃO DO IPE";
  
} else {

  $head2 = "RELATÓRIO DO IPE - TODOS";
}

$head3 = "COMPETÊNCIA : {$oGet->iMes} / {$oGet->iAno}";
$head4 = "ÓRGÃO : {$r11_codipe}";
$head5 = "Percentual: {$r11_percentualipe}%";

$sSql = $clipe->sql_query_relatorio_ipergs($oGet->iAno, $oGet->iMes, db_getsession("DB_instit"), $oGet->sTipo, $oGet->lUnificado, $oGet->sListaLotacoes);
$rsDados = db_query($sSql);
$iLinhas = pg_numrows($rsDados);

if ($iLinhas == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para o IPE no período de '.$oGet->mes.' / '.$oGet->ano);
}

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);
$nValorTotal     = 0;
$nTotalRegistros = 0;

if ($oGet->sTipo == "m") {
  $iDs = 4;  
} else {
  $iDs = 7;
}
   
for ($iInd = 0; $iInd < $iLinhas; $iInd++) {

  $oDados = db_utils::fieldsMemory($rsDados,$iInd);
  
  if ($oPdf->gety() > $oPdf->h - 30 || $iInd == 0 ) {
    fc_cabecalho($oPdf, $oGet->sTipo, ($oGet->sListaLotacoes!=""?true:false), 4);
    $lPreencheCelula = 1;
    
  }
  
  if ($lPreencheCelula == 1) {
    $lPreencheCelula = 0;
  } else {
    $lPreencheCelula = 1;
  }
  
  $oPdf->setfont('arial','',7);
  if (isset($oGet->sListaLotacoes) && $oGet->sListaLotacoes != ''){
    $oPdf->cell(20, 4, $oDados->r70_estrut, 0, 0, "L", $lPreencheCelula);
    $oPdf->cell(60, 4, $oDados->z01_nome  , 0, 0, "L", $lPreencheCelula);
    
  } else {
    $oPdf->cell(80, 4, $oDados->z01_nome  , 0, 0, "L", $lPreencheCelula);
    
  }
  
  if ($oGet->sTipo == "m") {

    $oPdf->cell(30, 4, $oDados->r36_matric                                , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(30, 4, db_formatar($oDados->r36_valorc,'f')               , 0, 0, "R", $lPreencheCelula);
    $oPdf->cell(10, 4, $iDs                                               , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(17, 4, ($oDados->r36_estado==22?"10":$oDados->r36_estado) , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(20, 4, db_formatar($oDados->r36_dtalt,'d')                , 0, 1, "L", $lPreencheCelula);
    $iDs = digman_170($iDs);
      
  } else { 

    $oPdf->cell(10, 4, ($oDados->z01_sexo=='M'?1:2)         , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(12, 4, $oDados->z01_estciv                  , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(15, 4, db_formatar($oDados->z01_nasc,'d')   , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(20, 4, $oDados->z01_ident                   , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(25, 4, db_formatar($oDados->r36_dtalt,'d')  , 0, 0, "C", $lPreencheCelula);
    $oPdf->cell(20, 4, db_formatar($oDados->r36_valorc,'f') , 0, 0, "R", $lPreencheCelula);
    $oPdf->cell(10, 4, $iDs                                 , 0, 1, "C", $lPreencheCelula);
    $iDs = diginc_170($iDs);
    
  }  
  
  $nValorTotal    += $oDados->r36_valorc;
  $nTotalRegistros++;

}

$oPdf->setfont('arial','b',8);
$oPdf->cell(190, 1, ''                                    ,"T", 1, "L", 0);
$oPdf->cell(50 , 4, "TOTAL DE REGISTROS"                  ,  1, 0, "L", 1);
$oPdf->cell(30 , 4, $nTotalRegistros                      ,  1, 1, "R", 0);
                                                          
$oPdf->cell(50 , 4, "TOTAL CONTRIBUIÇÃO"                  ,  1, 0, "L", 1);
$oPdf->cell(30 , 4, db_formatar($nValorTotal,'f')         ,  1, 1, "R", 0);

$nValorTotalPerc = ($nValorTotal*$r11_percentualipe)/100;
$oPdf->cell(50 , 4, "TOTAL PERC. ({$r11_percentualipe}%)" ,  1, 0, "L", 1);
$oPdf->cell(30 , 4, db_formatar($nValorTotalPerc,'f')     ,  1, 1, "R", 0);

$oPdf->Output();

function fc_cabecalho($oPdf, $sTipo, $lLotacao) {
  
  $oPdf->addpage();
  $oPdf->setfont('arial','b',8);
  if ($lLotacao) {
    $oPdf->cell(20, 4, 'LOTAÇÃO',            1, 0, "C", 1);
    $oPdf->cell(60, 4, 'NOME DO CONVENIADO', 1, 0, "C", 1);
  } else {                         
    $oPdf->cell(80, 4, 'NOME DO CONVENIADO', 1, 0, "C", 1);
  }
  
  if ($sTipo == "m") {

   $oPdf->cell(30, 4, 'MATRÍCULA'       , 1, 0, "C", 1);
   $oPdf->cell(30, 4, 'SALÁRIO CONTRIB.', 1, 0, "C", 1);
   $oPdf->cell(10, 4, 'DS'              , 1, 0, "C", 1);
   $oPdf->cell(17, 4, 'SITUAÇÃO'        , 1, 0, "C", 1);
   $oPdf->cell(20, 4, 'DATA ALT.SIT.'   , 1, 1, "C", 1);
   
  } else {

   $oPdf->cell(10, 4, 'SEXO'    , 1, 0, "C", 1);
   $oPdf->cell(12, 4, 'EST.CIV.', 1, 0, "C", 1);
   $oPdf->cell(15, 4, 'DT.NASC.', 1, 0, "C", 1);
   $oPdf->cell(20, 4, 'IDENT.'  , 1, 0, "C", 1);
   $oPdf->cell(25, 4, 'DT.VINC.', 1, 0, "C", 1);
   $oPdf->cell(20, 4, 'CONTRIB.', 1, 0, "C", 1);
   $oPdf->cell(10, 4, 'DS'      , 1, 1, "C", 1);
    
  } 
  
}

function digman_170($iDs) {
  
  if ($iDs == 4) {
    $iDs = 8;
  } else if ($iDs == 8) {
    $iDs = 2;
  } else if ($iDs == 2) {
    $iDs = 6;
  } else if ($iDs == 6) {
    $iDs = 3;
  } else if ($iDs == 3) {
    $iDs = 9;
  } else if ($iDs == 9) {
    $iDs = 4;
  }
    
  return $iDs;
}

function diginc_170($iDs) {

  if ($iDs == 7) {
    $iDs = 3;
  } else if ($iDs == 3) {
    $iDs = 8;
  } else if ($iDs == 8) {
    $iDs = 1;
  } else if ($iDs == 1) {
    $iDs = 9;
  } else if ($iDs == 9) {
    $iDs = 7;
  }
  return $iDs;
}

?>