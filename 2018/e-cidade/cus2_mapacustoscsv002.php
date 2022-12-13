<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("model/custoPlanilha.model.php");
include ("classes/db_custoplanilhaorigem_classe.php");
include ("classes/db_custoplano_classe.php");

$oParametros = db_utils::postMemory($_GET);
if ($oParametros->cc15_anousu ==  "" || $oParametros->cc15_mesusu == ""){
  die("Informe ano e mes.");
}

$oDaoOrigens    = new cl_custoplanilhaorigem();
$oDaoCustoPlano = new cl_custoplano();
$oPlanilhaCusto = new custoPlanilha($oParametros->cc15_mesusu, $oParametros->cc15_anousu);
$aHeadersCusto  = array();
$sSqlOrigens    = $oDaoOrigens->sql_query(null,"*", "cc14_sequencial");  
$rsOrigens      = $oDaoOrigens->sql_record($sSqlOrigens);
$aDadosOrigem   = db_utils::getColectionByRecord($rsOrigens);
$aCustos = $oPlanilhaCusto->getCustosPlanilha();

$sArquivo      = "tmp/mapa_custo_{$oParametros->cc15_mesusu}_{$oParametros->cc15_anousu}.csv";
$rArquivoCusto = fopen($sArquivo, "w");
/**
 * Montamos o header conforme 
 */
$sCampoOrigem          = "o56_codele";
$sCampoOrigemDescricao = "o56_elemento";
if ($oParametros->nivel == 2) {
  
  $sCampoOrigem          = "cc17_custoplanilhaorigem";
  $sCampoOrigemDescricao = "cc14_descricao";
   
}
foreach ($aCustos as $oOrigem) {
   
  if (!isset($aHeadersCusto[$oOrigem->$sCampoOrigem])) {
    $aHeadersCusto[$oOrigem->$sCampoOrigem] = $oOrigem->$sCampoOrigemDescricao;
  } 
}
/**
 * Montamos o plano de contas
 */
$sSqlCustoPlano = $oDaoCustoPlano->sql_query_analitica(null, 
                                                  "custoplano.*, fc_estrutural_pai(cc01_estrutural) as contapai,
                                                  fc_estrutural_nivel(cc01_estrutural) as nivelconta,
                                                  cc04_sequencial",
                                                  "cc01_estrutural");
$rsCustoPlano   = $oDaoCustoPlano->sql_record($sSqlCustoPlano);
$aPlanoCusto    = array();
for ($iPlano = 0; $iPlano < $oDaoCustoPlano->numrows; $iPlano++) {
  
  $oPlano = db_utils::fieldsMemory($rsCustoPlano, $iPlano);
  $aPlanoCusto[$oPlano->cc01_estrutural] = $oPlano;
   
}

/**
 * Agrupamos os dados da planilha por nivel/Conta na estrutura informada abaixo
 * Conta Custo
 *      |_ Niveis 
 *            |_ Valores do Nivel
 */
$aCustosProcessados = array();
foreach ($aCustos as $oCusto) {

  if (isset($aPlanoCusto[$oCusto->cc01_estrutural]->aOrigens[$oCusto->$sCampoOrigem])) {
    
    $aPlanoCusto[$oCusto->cc01_estrutural]->aOrigens[$oCusto->$sCampoOrigem]->valor += $oCusto->cc17_valor;
    
  } else {
    
    $oCustoProcessado = new stdClass();
    $oCustoProcessado->valor = $oCusto->cc17_valor;
    $aPlanoCusto[$oCusto->cc01_estrutural]->aOrigens[$oCusto->$sCampoOrigem] = $oCustoProcessado;
    
  }
  addValorContaPai($aPlanoCusto[$oCusto->cc01_estrutural], $oCusto->$sCampoOrigem, $oCusto->cc17_valor);
}

/**
 * adiciona os valores nas contas pais
 *
 * @param object $oConta contaa 
 * @param integer $iNivel nivel
 * @param float $nValor valor
 */
function addValorContaPai($oConta, $iNivel, $nValor) {
  
  global $aPlanoCusto;
  if (substr($oConta->contapai,0,2) > 0) {
    if (isset($aPlanoCusto[$oConta->contapai]->aOrigens[$iNivel])){
      $aPlanoCusto[$oConta->contapai]->aOrigens[$iNivel]->valor += $nValor;
    } else {
      
      $oContaProcessado = new stdClass();
      $oContaProcessado->valor = $nValor;
      $aPlanoCusto[$oConta->contapai]->aOrigens[$iNivel]->valor = $nValor;
      
    }
    addValorContaPai($aPlanoCusto[$aPlanoCusto[$oConta->contapai]->cc01_estrutural], $iNivel, $nValor);
  }
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body background="#cccccc">
<center>
<?
db_criatermometro("custos");
$sLinhaHeader  = "Conta;";
$sLinhaHeader .= "Descricao;";
$sVirgula = "";
foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
  
  $sLinhaHeader .= $sDescricao.";";
  
}
$sLinhaHeader  .= "Total\n";
fputs($rArquivoCusto, $sLinhaHeader);
$aTotais = array();
/**
 * Percorremos os Custos
 */

$iLinha = 0;
$iTotal = count($aPlanoCusto);
foreach ($aPlanoCusto as $oPlano) {
  
  $sStringIndenta = "";
  if ($oPlano->nivelconta > 1) {
   $sStringIndenta =  str_repeat(" ",$oPlano->nivelconta*3);
  }
  /**
   * Caso vazio, a conta é sintetica
   */
  $sLinhaArquivo  = "{$oPlano->cc01_estrutural};";
  $sLinhaArquivo .= "{$sStringIndenta}{$oPlano->cc01_descricao};";
  $nValorConta   = 0;
  foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
  
    $nValorNivel = 0;
    if (isset($oPlano->aOrigens[$iIndex])) {
      $nValorNivel = $oPlano->aOrigens[$iIndex]->valor;
    }
    
    $nValorConta += $nValorNivel;
    $sLinhaArquivo .= trim(db_formatar($nValorNivel,"f")).";";
    if ($oPlano->nivelconta == 1) {
      
      if (isset($aTotais[$iIndex])) {
        $aTotais[$iIndex] += $nValorNivel;
      } else {
        $aTotais[$iIndex] = $nValorNivel;
      }
    }
  
  }
  $sLinhaArquivo .= trim(db_formatar($nValorConta, "f"));
  fputs($rArquivoCusto, $sLinhaArquivo."\n");
  db_atutermometro($iLinha, $iTotal,"custos");
  $iLinha++;
} 

/**
 * Totalizadores 
 * 
 */
$sLinhaTotal = "Total;;";
$nValorConta = 0;
foreach ($aHeadersCusto as $iIndex  => $sDescricao) {
   
  $nValorTotal = 0;
  if (isset($aTotais[$iIndex])) {
    $nValorTotal = $aTotais[$iIndex];
  }
  
  $sLinhaTotal .= trim(db_formatar($nValorTotal,"f")).";";
  $nValorConta += $nValorTotal;
  
}
$sLinhaTotal .= trim(db_formatar($nValorConta, "f"));
fputs($rArquivoCusto, $sLinhaTotal);
fclose($rArquivoCusto);
?>
<form name='form1' id='form1'></form>
<script>
js_montarlista("<?=$sArquivo?>#Arquivo gerado em: <?=$sArquivo?>",'form1');</script>
</body>
</html>