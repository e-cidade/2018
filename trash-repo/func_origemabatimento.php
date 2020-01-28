<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_abatimento_classe.php");
require_once("classes/db_abatimentoregracompensacao_classe.php");
require_once("dbforms/verticalTab.widget.php");

$oGet           = db_utils::postMemory($_GET);
$iCodigoCredito = $oGet->iAbatimento;

$oDaoAbatimento                 = db_utils::getDao('abatimento');
$oDaoAbatimentoRegraCompensacao = db_utils::getDao('abatimentoregracompensacao');
$oDaoAbatimentoRecibo           = db_utils::getDao('abatimentorecibo');

$sSqlCreditos   = $oDaoAbatimento->sql_queryDadosCreditos($iCodigoCredito);
$rsDadosCredito = $oDaoAbatimento->sql_record($sSqlCreditos);

if ( $oDaoAbatimento->numrows == 0 ) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
  exit;
} 

$oCredito = db_utils::fieldsMemory($rsDadosCredito, 0);

$sLabelProcesso   = null;
$sProcesso        = null;
$sTitularProcesso = null;
$dProcesso        = null;

$oDaoAbatimentoProcessoExterno  = db_utils::getDao('abatimentoprocessoexterno');
$oDaoAbatimentoProtProcesso     = db_utils::getDao('abatimentoprotprocesso');
$oDaoAbatimentoRegraCompensacao = db_utils::getDao('abatimentoregracompensacao');

/**
 * Processo externo
 */   
$sSqlProcessoExterno = $oDaoAbatimentoProcessoExterno->sql_query_file(null, '*', null, "k160_abatimento = {$oCredito->k125_sequencial}");
$rsProcessoExterno   = $oDaoAbatimentoProcessoExterno->sql_record($sSqlProcessoExterno);

if ( $oDaoAbatimentoProcessoExterno->numrows > 0 ) {

  $oProcessoExterno = db_utils::fieldsMemory($rsProcessoExterno, 0);

  $sLabelProcesso   = 'Processo externo';
  $sProcesso        = $oProcessoExterno->k160_numeroprocesso;
  $sTitularProcesso = $oProcessoExterno->k160_nometitular;
  $dProcesso        = db_formatar($oProcessoExterno->k160_data, 'd');
}

/**
 * Processo sistema  
 */
if ( $oDaoAbatimentoProcessoExterno->numrows == 0 ) {

  $sSqlProcessoSistema = $oDaoAbatimentoProtProcesso->sql_query(null, '*', null, "k159_abatimento = {$oCredito->k125_sequencial}");
  $rsProcessoSistema   = $oDaoAbatimentoProtProcesso->sql_record($sSqlProcessoSistema);

  if ( $oDaoAbatimentoProtProcesso->numrows > 0 ) {
  
    $oProcessoSistema = db_utils::fieldsMemory($rsProcessoSistema, 0);

    $sLabelProcesso   = 'Processo do sistema';
    $sProcesso        = $oProcessoSistema->p58_codproc;
    $sTitularProcesso = $oProcessoSistema->p58_requer;
    $dProcesso        = db_formatar($oProcessoSistema->p58_dtproc, 'd');
  }
}

/**
 * Regra compensacao 
 */
$sSqlRegraCompensacao = $oDaoAbatimentoRegraCompensacao->sql_query(null, 'min(k155_tempovalidade) as diasvalidade, k156_observacao', null, "k156_abatimento = {$iCodigoCredito} group by k156_observacao");
$rsRegraCompensacao   = $oDaoAbatimentoRegraCompensacao->sql_record($sSqlRegraCompensacao);
$dDataVencimento      = null;
$iDiasTempoValidade   = 0;
$sObservacao          = null;

if ( $oDaoAbatimentoRegraCompensacao->numrows > 0 ) {

  $oRegraCompensacao    = db_utils::fieldsMemory($rsRegraCompensacao, 0);
  $sObservacao          = $oRegraCompensacao->k156_observacao;
  $iDiasTempoValidade   = $oRegraCompensacao->diasvalidade;
}

if ($iDiasTempoValidade > 0) {
  
  $aDataLancamento = explode("-", $oCredito->k125_datalanc);
  $dDataVencimento = date("d/m/Y", strtotime("+{$iDiasTempoValidade} days", mktime(0, 0, 0, $aDataLancamento[1], $aDataLancamento[2], $aDataLancamento[0]))); 
}


/**
 * Abatimentos
 */
$sSqlAbatimentoRecibos = $oDaoAbatimentoRecibo->sqlNumpresOrigemCredito($oCredito->k125_sequencial, "distinct recibopaga.k00_numpre as numpre_original" );
$rsAbatimentoRecibos   = $oDaoAbatimentoRecibo->sql_record($sSqlAbatimentoRecibos);
$sNumpres = "";
$sVirgula = "";
if ($oDaoAbatimentoRecibo->numrows > 0) {
  $aNumpres   = db_utils::getCollectionByRecord($rsAbatimentoRecibos);
  foreach ($aNumpres as $oNumpre){
    $sNumpres .= $sVirgula.$oNumpre->numpre_original;
    $sVirgula = ", ";
  }
}

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php

  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css, tab.style.css");
  
?>
<style type="text/css">
table.linhaZebrada {
  width: 100%;
}

table.linhaZebrada tr td:nth-child(even) {
  background-color: #FFF;
}

table.linhaZebrada tr td:nth-child(odd) {
  font-weight:bold;
  width:200px;
}
fieldset {
  margin: 10px auto 0 auto;
  width: 1150px;
}
</style>
</head>
<body bgcolor=#CCCCCC>
<fieldset >
  <legend><strong>Detalhes do Crédito</strong></legend>

  <table class="linhaZebrada">
    <tr>
      <td align="right">
        Código Abatimento : 
      </td>
      <td>
        <?php 
          echo $oCredito->k125_sequencial;
        ?>
      </td>
    </tr>

    <tr>
      <td align="right">
        Origem
      </td>
      <td>
        <?php 
          echo $oCredito->origem;                
        ?>
      </td>
    </tr>
    
    <tr>
      <td align="right">
        Data/Hora do Lançamento : 
      </td>
      <td>
        <?php 
          echo db_formatar($oCredito->k125_datalanc,'d') . ' - ' . $oCredito->k125_hora;; 
        ?>
      </td>
    </tr>
    
    <tr>
      <td align="right">
        Valor :              
      </td>
      <td>
        <?php echo db_formatar($oCredito->k125_valor,'f'); ?>
      </td>
    </tr>

    <tr>
      <td align="right">
        Valor Disponível:              
      </td>
      <td>
        <?php echo db_formatar($oCredito->k125_valordisponivel,'f');  ?>
      </td>
    </tr>
    
    <?php if ($dDataVencimento != null) : ?>

      <tr>
        <td align="right">
          Data de Vencimento:
        </td>
        <td>
        <?php echo $dDataVencimento; ?>
        </td>
      </tr>
        
    <?php endif; ?>

    <?php if ( !empty($sProcesso) ) : ?>

    <tr>
      <td align="right"><?php echo $sLabelProcesso; ?></td>
      <td>
        <?php echo $sProcesso; ?>
      </td>
    </tr>

    <tr>
      <td align="right">Titular processo</td>
      <td>
        <?php echo $sTitularProcesso; ?>
      </td>
    </tr>
    
    <tr>
      <td align="right">Data processo</td>
      <td>
        <?php echo $dProcesso; ?>
      </td>
    </tr>

    <?php endif; ?>

    <?php
      if (! empty($sNumpres) ) { 
    ?>
          <tr>
            <td align="right">Numpre(s): </td>
            <td>
              <?php echo $sNumpres; ?>
            </td>
          </tr>
    <?php
      }
    ?>


    <tr>
      <td align="right">Observação</td>
      <td>
        <?php echo $sObservacao; ?>
      </td>
    </tr>

    
  </table>
</fieldset>  

<fieldset>
<?php

  $oDetalhesCredito = new verticalTab("oTabsDetalhesCredito", 300);
  $oDetalhesCredito->add("regrasCompensacao", "Regras de Compensação do Crédito", "func_creditodetalhesregracompensacao.php?iCodigoCredito={$iCodigoCredito}");
  $oDetalhesCredito->add("origemCredito", "Origem do Crédito", "func_creditodetalhesorigem.php?iCodigoCredito={$iCodigoCredito}");
  $oDetalhesCredito->add("destinoCredito", "Uso do Crédito", "func_creditodetalhesdestino.php?iCodigoCredito={$iCodigoCredito}");
  $oDetalhesCredito->show();
?>
</fieldset>

</body>
</html>