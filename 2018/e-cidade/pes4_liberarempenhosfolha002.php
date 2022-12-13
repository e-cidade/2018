<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

/**
 * Representa o Iframe da liberação do empenho/slip.
 * 
 * @author I
 * @revision $Author: dbrenan.silva $
 * @version $Revision: 1.29 $
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("classes/db_rhempenhofolhaconfirma_classe.php");

$oRotulo = new rotulocampo();
$oRotulo->label("rh72_projativ");
$oRotulo->label("rh72_codele");
$oRotulo->label("o56_codele");
$oRotulo->label("rh72_recurso");
$oRotulo->label("rh72_coddot");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_nome");

$oPost                  = db_utils::postMemory($_POST);
$oGet                   = db_utils::postMemory($_GET);

$oDaoRhEmpenhoConfirma  = new cl_rhempenhofolhaconfirma;
$oGeradorSql            = new cl_gera_sql_folha;
$oJson                  = new Services_JSON();
$oParam                 = $oJson->decode(str_replace("\\","",$_GET["json"]));

$oGeradorSql->inicio_rh = false;
$oGeradorSql->usar_pes  = true;
$oGeradorSql->usar_rub  = true;
$oGeradorSql->usar_rel  = true;
$oGeradorSql->usar_atv  = true;
$oGeradorSql->usa       = true;
$oGeradorSql->usar_rel  = true;

if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
  $sListaRescisoes = implode(",", $oParam->aRescisoes);
}
/**
 * incluimos a liberacao
 */
if (isset($oPost->liberar)) {
  
  try {
    
    db_inicio_transacao();
    
    $oDaoRhEmpenhoConfirma->rh83_dataliberacao = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoRhEmpenhoConfirma->rh83_id_usuario    = db_getsession("DB_id_usuario");
    $oDaoRhEmpenhoConfirma->rh83_anousu        = $oParam->iAnoFolha;
    $oDaoRhEmpenhoConfirma->rh83_mesusu        = $oParam->iMesFolha;
    $oDaoRhEmpenhoConfirma->rh83_siglaarq      = $oParam->sSigla;
    $oDaoRhEmpenhoConfirma->rh83_instit        = db_getsession("DB_instit");
    $oDaoRhEmpenhoConfirma->rh83_tabprev       = "0";    
    $oDaoRhEmpenhoConfirma->rh83_tipoempenho   = $oParam->iTipo;
    if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
      
      foreach ($oParam->aRescisoes as $iRescisao) {
        $oDaoRhEmpenhoConfirma->rh83_complementar  = $iRescisao;
        $oDaoRhEmpenhoConfirma->incluir(null);
        if ($oDaoRhEmpenhoConfirma->erro_status == "0") {
          throw new Exception("Liberação de Empenhos abortada!\n\n{$oDaoRhEmpenhoConfirma->erro_msg}");
        }
      }
      
    } else { 
    
      $oDaoRhEmpenhoConfirma->rh83_complementar  = $oParam->sSemestre;
      
      if ($oParam->iTipo == 2) {
        
       /*
        * Caso esteja sendo liberada as tabelas da previdencia (iTipo == 2)
        * Incluímos uma liberação para cada tabela da previdencia
        */
        if (strpos($oParam->sPrevidencia, ",") ) {
          $aPrevidencias = explode(",",$oParam->sPrevidencia);  
        } else {
          $aPrevidencias = array($oParam->sPrevidencia);
        }
        for ($iInd = 0; $iInd < count($aPrevidencias); $iInd++) {
          
          $oDaoRhEmpenhoConfirma->rh83_tabprev = $aPrevidencias[$iInd];
          $oDaoRhEmpenhoConfirma->incluir(null);
          if ($oDaoRhEmpenhoConfirma->erro_status == "0") {
            throw new Exception("Liberação de Empenhos abortada!\n\n{$oDaoRhEmpenhoConfirma->erro_msg}");
          }
        }
        
      } else {
        
        $oDaoRhEmpenhoConfirma->incluir(null);
        if ($oDaoRhEmpenhoConfirma->erro_status == "0") {
          throw new Exception("Liberação de Empenhos abortada!\n\n{$oDaoRhEmpenhoConfirma->erro_msg}");
        }
          
      }
      
    }
    
    db_fim_transacao(false);
    
    db_msgbox("Liberação de Empenhos realizada com Sucesso!");
  } catch (Exception $oException) {
    
    db_fim_transacao(true);
    db_msgbox($oException->getMessage());
    
  }
  
} else if (isset($oPost->cancelar)) {
  
  try {
    
    db_inicio_transacao();
    
    $sWhereConfirma   = "rh83_anousu            = {$oParam->iAnoFolha}"; 
    $sWhereConfirma  .= " and rh83_mesusu       = {$oParam->iMesFolha}"; 
    $sWhereConfirma  .= " and rh83_siglaarq     = '{$oParam->sSigla}'";
     
    if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
      $sWhereConfirma  .= "    and rh83_complementar in({$sListaRescisoes})"; 
    } else {
      if ($oParam->iTipo == 2) {
        $sWhereConfirma  .= " and rh83_tabprev in ({$oParam->sPrevidencia})"; 
      }
      $sWhereConfirma  .= " and rh83_complementar = {$oParam->sSemestre}";
    }
    
    $sWhereConfirma  .= " and rh83_tipoempenho  = {$oParam->iTipo}";
    $sWhereConfirma  .= " and rh83_instit       =  ".db_getsession("DB_instit");
    $oDaoRhEmpenhoConfirma->excluir(null, $sWhereConfirma);
    if ($oDaoRhEmpenhoConfirma->erro_status == 0) {
      throw new Exception("Cancelamento de Liberação de Empenho abortada!\n\n{$oDaoRhEmpenhoConfirma->erro_msg}");
    }
    
    db_fim_transacao(false);
    db_msgbox("Liberação de Empenho cancelada com sucesso!");
  } catch (Exception $oException) {
    db_fim_transacao(true);
    db_msgbox($oException->getMessage());
  }
  
}

$sSqlDadosFolha  = " select rh72_sequencial as codigo               ";
$sSqlDadosFolha .= "   from rhempenhofolha                          ";
$sSqlDadosFolha .= "  where rh72_tipoempenho = {$oParam->iTipo}     ";
$sSqlDadosFolha .= "    and rh72_siglaarq    = '{$oParam->sSigla}'  ";
$sSqlDadosFolha .= "    and rh72_anousu      = {$oParam->iAnoFolha} ";
$sSqlDadosFolha .= "    and rh72_mesusu      = {$oParam->iMesFolha} ";

/**
 * Tipo de empenho = Previdencia 
 * String com o id dos empenhos selecionados
 */
if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != null && $oParam->iTipo == 2) {
	$sSqlDadosFolha .= "  and rh72_tabprev in ({$oParam->sPrevidencia}) ";
}

$sSqlDadosFolha .= "  union all                                     ";
$sSqlDadosFolha .= " select rh79_sequencial as codigo               ";
$sSqlDadosFolha .= "   from rhslipfolha                             ";
$sSqlDadosFolha .= "  where rh79_tipoempenho = {$oParam->iTipo}     ";
$sSqlDadosFolha .= "    and rh79_siglaarq    = '{$oParam->sSigla}'  ";
$sSqlDadosFolha .= "    and rh79_anousu      = {$oParam->iAnoFolha} ";
$sSqlDadosFolha .= "    and rh79_mesusu      = {$oParam->iMesFolha} "; 
$sSqlDadosFolha .= "  limit 1 ";

$rsDadosFolha      = db_query($sSqlDadosFolha);
$iLinhasDadosFolha = pg_num_rows($rsDadosFolha);                          

if ( $iLinhasDadosFolha > 0 ) {
	$sDisabled = '';
} else {
	$sDisabled = 'disabled';
}

/**
 * Calculamos o total da folha de de pagamento do periodo 
 */
$iDesconto = 2; 
$iProvento = 1; 
if ($oParam->iTipo != 1) {
  
  $iDesconto = 1;
  $iProvento = 2; 
}
$sCampos  = "round(sum(case when {$oParam->sSigla}_pd = {$iDesconto} and rh23_rubric is not null 
                       then {$oParam->sSigla}_valor end)
                       ,2) as totalempenhadodesconto,";
$sCampos        .= "round(sum(case when {$oParam->sSigla}_pd = {$iProvento}  then {$oParam->sSigla}_valor else 0 end), 2) as total";
$sWhereRubrica   = "";
if ($oParam->iTipo == 1) { 
  $sWhereRubrica  = " < 'R950'";
} else if ($oParam->iTipo == 2) {
  $sWhereRubrica  = " = 'R992'";
} else if ($oParam->iTipo == 3){
  $sWhereRubrica  = " = 'R991'";
}

if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() &&
    dadosEmpenhoFolha::verificarPermissaoFolha($oParam->sSigla)) {
  
  $sWhere             = "";
  $sWhereSuplementar  = "and rh141_codigo = {$oParam->sSemestre} ";
  $sWhereSuplementar .= "and rh143_rubrica {$sWhereRubrica} ";
  
} else {
  
  $sWhere = "";
  if ($oParam->sSigla == 'r48' ) {
    $sWhere .= " r48_semest = {$oParam->sSemestre} and ";
  }
  if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
    $sWhere .=" rh02_seqpes in({$sListaRescisoes}) and ";
  }
  
  $sWhere            = $sWhere." {$oParam->sSigla}_rubric {$sWhereRubrica}";
  $sWhereSuplementar = "";
  
}

$iInstit = db_getsession("DB_instit");
$sSqlTotalBrutoFolha = $oGeradorSql->gerador_sql($oParam->sSigla,
		  			                                     $oParam->iAnoFolha,
						                                     $oParam->iMesFolha,
						                                     "",
						                                     "",
						                                     $sCampos,
												                         "",
						                                     $sWhere,
						                                     $iInstit,
                                                 $sWhereSuplementar
						                                    );

$rsTotalFolha            = db_query($sSqlTotalBrutoFolha);
$nToTalFolhaBruto        = db_utils::fieldsMemory($rsTotalFolha, 0)->total;
$nTotalDescontoEmpenhado = db_utils::fieldsMemory($rsTotalFolha, 0)->totalempenhadodesconto;

if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() &&
    dadosEmpenhoFolha::verificarPermissaoFolha($oParam->sSigla)) {
  
  $sWhere             = "";
  $sWhereSuplementar  = "and rh143_tipoevento = {$iDesconto}";
  $sWhereSuplementar .= "and rh141_codigo = {$oParam->sSemestre} ";
  $sWhereSuplementar .= "and rh143_rubrica {$sWhereRubrica} ";
  
} else {
  
  $sWhere  = "{$oParam->sSigla}_pd = {$iDesconto}";
  if ($oParam->sSigla == 'r48' ) {
    $sWhere .= " and r48_semest = {$oParam->sSemestre} ";
  }
  if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
    $sWhere .=" and rh02_seqpes in({$sListaRescisoes}) ";
  }
  
  $sWhere            = $sWhere." and {$oParam->sSigla}_rubric {$sWhereRubrica}";
  $sWhereSuplementar = "";  
}
$sCampos  = "round(sum({$oParam->sSigla}_valor),2) as total";
$sSqlTotalDescontoFolha = $oGeradorSql->gerador_sql($oParam->sSigla,
		  			                                        $oParam->iAnoFolha,
						                                        $oParam->iMesFolha,
						                                        "",
						                                        "",
						                                        $sCampos,
									   			                          "",
						                                        $sWhere,
						                                        $iInstit,
                                                    $sWhereSuplementar  
						                                       );

$rsTotalDescontoFolha = db_query($sSqlTotalDescontoFolha);
$nToTalFolhaDesconto  = db_utils::fieldsMemory($rsTotalDescontoFolha, 0)->total;
/**
 * Calculamos o total dos Empenho
 */
$sSqlEmpenhos     = "SELECT round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end), 2) as rh73_valor";
$sSqlEmpenhos    .= "  from rhempenhofolha "; 
$sSqlEmpenhos    .= "       inner join rhempenhofolharhemprubrica        on rh81_rhempenhofolha = rh72_sequencial "; 
$sSqlEmpenhos    .= "       inner join rhempenhofolharubrica  on rh73_sequencial     = rh81_rhempenhofolharubrica ";
$sSqlEmpenhos    .= "  where rh72_tipoempenho = {$oParam->iTipo}";
$sSqlEmpenhos    .= "    and rh73_tiporubrica = 1";
$sSqlEmpenhos    .= "    and rh72_anousu      = {$oParam->iAnoFolha}"; 
$sSqlEmpenhos    .= "    and rh72_mesusu      = {$oParam->iMesFolha}"; 
$sSqlEmpenhos    .= "    and rh72_siglaarq    = '{$oParam->sSigla}'";
if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
  $sSqlEmpenhos    .= "    and rh73_seqpes     in({$sListaRescisoes})";
} else {

  $sSqlEmpenhos    .= "    and rh72_seqcompl    = '{$oParam->sSemestre}'";
}

/**
 * Tipo de empenho = Previdencia 
 * String com o id dos empenhos selecionados
 */
if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != null && $oParam->iTipo == 2) {
	$sSqlEmpenhos  .= "  and rh72_tabprev in ({$oParam->sPrevidencia}) ";
}

$sSqlEmpenhos    .= "    and rh73_instit      = {$iInstit}";
$sSqlEmpenhos    .= "    and rh73_rubric {$sWhereRubrica}";
$rsTotalEmpenhos  = db_query($sSqlEmpenhos);
$nTotalLiquidoEmpenhos =  db_utils::fieldsMemory($rsTotalEmpenhos, 0)->rh73_valor;

/**
 * Calculamos o total do Desconto dos empenhos
 */
$sSqlTotalDescontos      = "SELECT sum(round(rh73_valor,2)) as valor ";
$sSqlTotalDescontos     .= "  from rhempenhofolha ";
$sSqlTotalDescontos     .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha        = rh72_sequencial";
$sSqlTotalDescontos     .= "       inner join rhempenhofolharubrica      on rh81_rhempenhofolharubrica = rh73_sequencial";
$sSqlTotalDescontos     .= " where rh72_tipoempenho = {$oParam->iTipo} ";
$sSqlTotalDescontos     .= "   and rh73_pd          = 2 ";
$sSqlTotalDescontos     .= "   and rh73_tiporubrica <> 3";
$sSqlTotalDescontos     .= "  and rh72_siglaarq     = '{$oParam->sSigla}'";
$sSqlTotalDescontos     .= "  and rh72_mesusu       = '{$oParam->iMesFolha}'";
$sSqlTotalDescontos     .= "  and rh72_anousu       = '{$oParam->iAnoFolha}'";
if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
  $sSqlTotalDescontos    .= "    and rh73_seqpes     in({$sListaRescisoes})";
} else {
  $sSqlTotalDescontos     .= "  and rh72_seqcompl     = '{$oParam->sSemestre}'";  
}

/**
 * Tipo de empenho = Previdencia 
 * String com o id dos empenhos selecionados
 */
if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != null && $oParam->iTipo == 2) {
	$sSqlTotalDescontos   .= "  and rh72_tabprev in ({$oParam->sPrevidencia}) ";
}
$sSqlTotalDescontos     .= "  and rh73_instit       = {$iInstit}";
$sSqlTotalDescontos     .= "  and rh73_rubric {$sWhereRubrica}";
$sSqlTotalDescontos     .= "  and rh73_rubric not in (select rh23_rubric from rhrubelemento where rh23_instit = ".db_getsession("DB_instit").")";

$rsTotalDescontos        = db_query($sSqlTotalDescontos);
$nTotalDescontosEmpenhos = db_utils::fieldsMemory($rsTotalDescontos, 0)->valor;

/**
 * Calculamos o total dos Slips
 */
$sSqlSlipLiquido     = "SELECT sum(rh73_valor) as rh73_valor";
$sSqlSlipLiquido    .= "  from rhslipfolha "; 
$sSqlSlipLiquido    .= "       inner join rhslipfolharhemprubrica on rh80_rhslipfolha = rh79_sequencial "; 
$sSqlSlipLiquido    .= "       inner join rhempenhofolharubrica   on rh73_sequencial  = rh80_rhempenhofolharubrica ";
$sSqlSlipLiquido    .= "  where rh79_tipoempenho = {$oParam->iTipo}";
$sSqlSlipLiquido    .= "    and rh73_tiporubrica = 3";
$sSqlSlipLiquido    .= "    and rh79_anousu      = {$oParam->iAnoFolha}"; 
$sSqlSlipLiquido    .= "    and rh79_mesusu      = {$oParam->iMesFolha}"; 
$sSqlSlipLiquido    .= "    and rh79_siglaarq    = '{$oParam->sSigla}'";
if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
  $sSqlSlipLiquido    .= "    and rh73_seqpes     in({$sListaRescisoes})";
} else {
  $sSqlSlipLiquido    .= "    and rh79_seqcompl    = '{$oParam->sSemestre}'";  
}

/**
 * Tipo de empenho = Previdencia 
 * String com o id dos empenhos selecionados
 */
if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != null && $oParam->iTipo == 2) {
	$sSqlSlipLiquido  .= "    and rh79_tabprev in ({$oParam->sPrevidencia}) ";
}
$sSqlSlipLiquido    .= "    and rh73_instit      = {$iInstit}";
$sSqlSlipLiquido    .= "    and rh73_rubric {$sWhereRubrica}";
$rsSlipLiquido       = db_query($sSqlSlipLiquido);
$nTotalSlipLiquido   = db_utils::fieldsMemory($rsSlipLiquido, 0)->rh73_valor; 

/**
 * Calculamos o total do Desconto dos empenhos
 */
$sSqlTotalDescontosSlip      = "SELECT sum(round(rh73_valor,2)) as valor ";
$sSqlTotalDescontosSlip     .= "  from rhslipfolha ";
$sSqlTotalDescontosSlip     .= "       inner join rhslipfolharhemprubrica on rh80_rhslipfolha           = rh79_sequencial";
$sSqlTotalDescontosSlip     .= "       inner join rhempenhofolharubrica   on rh80_rhempenhofolharubrica = rh73_sequencial";
$sSqlTotalDescontosSlip     .= " where rh79_tipoempenho = {$oParam->iTipo}";
$sSqlTotalDescontosSlip     .= "   and rh73_pd          = 2 ";
$sSqlTotalDescontosSlip     .= "  and rh79_siglaarq     = '{$oParam->sSigla}'";
$sSqlTotalDescontosSlip     .= "  and rh79_mesusu       = '{$oParam->iMesFolha}'";
$sSqlTotalDescontosSlip     .= "  and rh79_anousu       = '{$oParam->iAnoFolha}'";
if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) {
  $sSqlTotalDescontosSlip   .= "    and rh73_seqpes     in({$sListaRescisoes})";
} else {
  $sSqlTotalDescontosSlip   .= "  and rh79_seqcompl     = '{$oParam->sSemestre}'";
}
$sSqlTotalDescontosSlip     .= "  and rh73_instit       = {$iInstit}";
$sSqlTotalDescontosSlip     .= "  and rh73_rubric {$sWhereRubrica}";
/**
 * Tipo de empenho = Previdencia 
 * String com o id dos empenhos selecionados
 */
if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != null && $oParam->iTipo == 2) {
	$sSqlTotalDescontosSlip   .= " and rh79_tabprev in ({$oParam->sPrevidencia}) ";
}
$rsTotalDescontosSlip        = db_query($sSqlTotalDescontosSlip);
$nTotalDescontosSlip         = db_utils::fieldsMemory($rsTotalDescontosSlip, 0)->valor;
/**
 * Verificamos se já foi liberado o os empenhos para a folha
 */
$sWhereConfirma   = " rh83_anousu           = {$oParam->iAnoFolha}"; 
$sWhereConfirma  .= " and rh83_mesusu       = {$oParam->iMesFolha}"; 
$sWhereConfirma  .= " and rh83_siglaarq     = '{$oParam->sSigla}'";
if ($oParam->sSigla == 'r20' && $oParam->iTipo == 1) { 
  $sWhereConfirma  .= " and rh83_complementar in ({$sListaRescisoes})";
} else {
  if ($oParam->iTipo == 2){
    $sWhereConfirma  .= " and rh83_tabprev in ({$oParam->sPrevidencia})";
  }
  $sWhereConfirma  .= " and rh83_complementar = {$oParam->sSemestre}";  
}

$sWhereConfirma  .= " and rh83_tipoempenho  = {$oParam->iTipo}"; 
$sWhereConfirma  .= " and rh83_tipoempenho  = {$oParam->iTipo}";
$sWhereConfirma  .= " and rh83_instit       =  ".db_getsession("DB_instit");
$sSqlConfirma    = $oDaoRhEmpenhoConfirma->sql_query_file(null,"*", null, $sWhereConfirma);
$rsConfirma      = $oDaoRhEmpenhoConfirma->sql_record($sSqlConfirma);
$sLabelBotao     = "Liberar Empenhos/Slips";
$sNameBotao      = "liberar";
if ($oDaoRhEmpenhoConfirma->numrows > 0) {

  $sLabelBotao     = "Cancelar Liberação";
  $sNameBotao      = "cancelar";
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?

  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");

?>
<style type="text/css">
 .nivel {
    background-color: white;
    color: black;
    font-weight: bold;
    text-align: center;
 }
 
 .valores {
    color: black;
    font-weight: bold;
    text-align: right;
    border-right: 2px groove white;
 }
</style>
</head>
<body bgcolor="#cccccc" onload='js_init()'style="margin:0">
<div style="border-bottom: 2px groove white; background-color: white;width: 100%;height: 50px;tex-align:left">
      <b>Confira os Valores para cada item da folha.</b>
    </div> 
  <form name='form1' method="post">
   <table cellspacing="0" >
     <tr>
       <td>
         <fieldset>
           <legend>
            <b>Dados da Folha</b>
          </legend>
          <table cellspacing="0"  >
             <tr>
                <td>
                   <b>Mês:</b>
                </td>
                <td style='border:1px solid #999999;background-color: white;width: 50px' id='mesfolha'>
                   &nbsp;
                </td>
                <td>
                   <b>Ano:</b>
                </td>
                <td style='border:1px solid #999999;background-color: white;width: 50px' id='anofolha'>
                   &nbsp;
                </td>
             </tr>
               <td>
                <b>Tipo de Folha:</b>
               </td>
               <td style='border:1px solid #999999;background-color: white;width: 120px' id='tipofolha' colspan="3">
                  &nbsp;
                </td>
             </tr>
          </table>
       </td>
     </tr>
   </table>
   <table width="100%">
     <tr>
       <td>
         <fieldset>
           <legend>
             <b>Resumo da Folha</b>
           </legend>
           <table cellspacing="0" width="100%">
             <tr>
               <td class='nivel' width="10%">
                  &nbsp;
               </td>
               <td class='nivel' width="20%">
                  Total Folha
               </td>
               <td class='nivel' width="20%">
                  Total Empenho
               </td>
               <td class='nivel' width="20%">
                  Total Desconto Empenhado
               </td>
               <td class='nivel' width="20%">
                  Total Slip
               </td>
               <td class='nivel' width="20%">
                 Diferença
               </td>
             </tr>
             <tr> 
               <td class='valores' style='text-align:left'>
                 <b>Bruto:</b>
                 
               </td>
               <td class='valores'>
                 <?=db_formatar($nToTalFolhaBruto, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar($nTotalLiquidoEmpenhos, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar($nTotalDescontoEmpenhado, 'f');?>
               </td>
               
               <td class='valores'>
                 <?=db_formatar($nTotalSlipLiquido, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar(abs($nToTalFolhaBruto -  $nTotalDescontoEmpenhado-$nTotalLiquidoEmpenhos - $nTotalSlipLiquido), 'f');?>
               </td> 
             </tr>
             <tr> 
               <td class='valores' style='text-align:left'>
                 <b>Descontos:</b>
               </td>
               <td class='valores'>
                 <?=db_formatar($nToTalFolhaDesconto, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar($nTotalDescontosEmpenhos, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar($nTotalDescontoEmpenhado, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar($nTotalDescontosSlip, 'f');?>
               </td>
               <td class='valores'>
                <?=db_formatar(abs($nToTalFolhaDesconto - $nTotalDescontosEmpenhos - $nTotalDescontoEmpenhado - $nTotalDescontosSlip) , 'f');?>
               </td> 
             </tr>
             <tr> 
               <td class='valores' style='text-align:left'>
                 <b>Liquido:</b>
               </td>
               <td class='valores'>
               <?=db_formatar($nToTalFolhaBruto - $nToTalFolhaDesconto, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar($nTotalLiquidoEmpenhos - $nTotalDescontosEmpenhos, 'f');?>
               </td>
               <td class='valores'>
                 <?=db_formatar(0, 'f');?>
               </td>
               <td class='valores'>
                <?=db_formatar($nTotalSlipLiquido - $nTotalDescontosSlip, 'f');?>
               </td>
               <td class='valores'>
               <?
               echo db_formatar(abs(($nToTalFolhaBruto - $nToTalFolhaDesconto) -
                                (($nTotalLiquidoEmpenhos - $nTotalDescontosEmpenhos) + 
                                ($nTotalSlipLiquido - $nTotalDescontosSlip))),
                                "f");
                ?>  
               </td> 
             </tr>
           </table>
         </fieldset>
       </td>
     </tr>
     <tr>
       <td colspan="4" align="center">
         <?
           if (isset($oGet->lBotao)) {
             echo "<input  type='submit' value='{$sLabelBotao}'  name='{$sNameBotao}' {$sDisabled} />";
           }
         ?>
       </td>
     </tr>
   </table>
   <? 
     if ($oParam->sSigla == "r20" && $oParam->iTipo == 1) {
       
       $oDaoPessoalMov = db_utils::getDao("rhpessoal");
       $sSqlPessoal    = $oDaoPessoalMov->sql_query_cgmmov(null,
                                                   "z01_nome, rh02_regist",
                                                    "rh02_regist",
                                                   "rh02_seqpes in({$sListaRescisoes})"
                                                   );
       $rsPessoal = db_query($sSqlPessoal);
       $aListaPessoas = db_utils::getCollectionByRecord($rsPessoal);                                                   
       echo "<fieldset><legend><b>Rescisões Escolhidas</b></legend>";
       echo "  <table cellspacing = '0' style='width:80%;border:2px inset white'>";
       echo "    <tr>";
       echo "    <th class='table_header' style='width:20%'>Matricula";
       echo "    </th>";
       echo "    <th class='table_header'>Nome";
       echo "    </th>";
       echo "    <th class='table_header' width='17px'>&nbsp;";
       echo "    </th>";
       echo "    </tr>";
       echo "    <tbody style='height:150px;background-color:white;overflow:scroll; overflow-x:hidden'>";
       foreach ($aListaPessoas as $oPessoa) {
         
         echo "<tr style='height:1em'>";
         echo "  <td class='linhagrid' style='text-align:right'>{$oPessoa->rh02_regist}</td>";
         echo "  <td class='linhagrid' style='text-align:left'>{$oPessoa->z01_nome}</td>";
         echo "</tr>";
       }
       echo "      <tr style='height:auto'><td colspan=2>&nbsp;</td></tr>";
       echo "    </tbody>";
       echo "  </table>";
       echo "</fieldset>";
     }
   ?>
   
 </form>
</body>
</html>
<script>
function js_init() {
   
  /**
   * consultamos os empenhos que deve ser gerados
   */
  oParametros = eval("("+parent.js_getQueryTela('consultarEmpenhos')+")");
  $('mesfolha').innerHTML = oParametros.iMesFolha;
  $('anofolha').innerHTML = oParametros.iAnoFolha;
  var sNomeFolha = new String();
  switch (oParametros.sSigla) {
    
    case 'r14' :
      
      sNomeFolha = 'Salário'; 
      break;
    
    case 'r48' :
      
      sNomeFolha = 'Complementar'; 
      break;  
    
    case 'r35' :
     
      sNomeFolha = '13º Salário'; 
      break;   
    
    case 'r20' :
     
      sNomeFolha = 'Rescisão'; 
      break;
      
    case 'r22' :
     
      sNomeFolha = 'Adiantamento'; 
      break;
      
    case 'sup' :
        
      sNomeFolha = 'Suplementar';
      break;
  }
  $('tipofolha').innerHTML = sNomeFolha;
}
</script>
