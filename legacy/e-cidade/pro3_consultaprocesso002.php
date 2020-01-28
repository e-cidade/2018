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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_proctransand_classe.php"));
require_once(modification("dbforms/verticalTab.widget.php"));
require_once(modification("model/processoProtocolo.model.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oDaoProcesso = db_utils::getDao("protprocesso");

$sWhere       = " p58_instit = ".db_getsession("DB_instit");
if (isset($oGet->codproc) && !empty($oGet->codproc)  && !isset($oGet->numero) ) {
  $sWhere .= " and p58_codproc = {$oGet->codproc}";
} 

if (isset($oGet->numero) &&  !empty($oGet->numero) ) {
	
	$aNumeroProcesso = explode("/", $oGet->numero);
	$iAno            = db_getsession("DB_anousu");
	$iNumeroProcesso = $aNumeroProcesso[0];
	
	if (count($aNumeroProcesso) > 1) {
		
		$iAno = $aNumeroProcesso[1];
	}
	
	
	$sWhere .= " and p58_numero = '{$iNumeroProcesso}' and p58_ano = {$iAno} ";
}


$sSqlProcessoApensado     = "(select p30_procprincipal ";
$sSqlProcessoApensado    .= "          from processosapensados";
$sSqlProcessoApensado    .= "         where p30_procapensado = p58_codproc limit 1) as processo_principal";
$sSqlBuscaCodigoProcesso = $oDaoProcesso->sql_query(null, "*, {$sSqlProcessoApensado}", " 1 desc", $sWhere);

$rsBuscaCodigoProcesso   = $oDaoProcesso->sql_record($sSqlBuscaCodigoProcesso);

$sProcessoPrincipal = '';
$oProcesso = db_utils::fieldsMemory($rsBuscaCodigoProcesso, 0);
if ($oProcesso->processo_principal != '') {
  
  $sProcessoPrincipal = " - <b>(Apensado ao processo {$oProcesso->processo_principal}) </b>";
}


$oProcessoProtocolo = new processoProtocolo($oProcesso->p58_codproc);
$oLicitacao         = $oProcessoProtocolo->getLicitacao();
$sCodigoLicitacao   = "";
$iCodigoSequencialLicitacao = 0;
if ($oLicitacao) {
  
  $sCodigoLicitacao = "{$oLicitacao->getEdital()}/{$oLicitacao->getAno()}";
  $iCodigoSequencialLicitacao = $oLicitacao->getCodigo();
}


?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php 
    db_app::load('scripts.js, prototype.js');
    db_app::load('estilos.css, tab.style.css');
  ?>
  <style type="text/css">
    .negrito {
      font-weight: bolder;
    }
    .dados{
      background-color: #FFF;
      padding: 1px;
      padding-left: 3px;
    }
  </style>
</head>
<body>
  <fieldset>
    <legend class='negrito'>Dados do Processo</legend>
    <table width="100%" border="0">
      <tr>
        <td class='negrito' width="150">
          Nº Controle do Processo:
        </td>
        <td class='dados' nowrap="nowrap" >
          <?php echo $oProcesso->p58_codproc.$sProcessoPrincipal;?>
        </td>
        <td class='negrito' width="100px">Nº do Processo: </td>
        <td class='dados' width="90">
          <?php echo "{$oProcesso->p58_numero}/{$oProcesso->p58_ano}"?>
        </td>
        <td rowspan="8" id="tdObservacoes" width="800" valign="top">
          &nbsp;
        </td>
      </tr>
      <tr>
        <td class='negrito'>
          Data: 
        </td>
        <td class='dados'>
          <?php echo db_formatar($oProcesso->p58_dtproc, 'd')?>
        </td>
        <td class='negrito'>
          Hora:
        </td>
        <td class='dados'>
          <?php echo $oProcesso->p58_hora?>
        </td>
      </tr>
      <tr>
        <td class='negrito'> 
          Tipo do Processo:
        </td>
        <td class='dados'>
          <?php echo $oProcesso->p51_descr ?>
        </td>
        <td>
          <?php
            db_ancora("Licitação:", "consultaLicitacao({$iCodigoSequencialLicitacao})", 1);
          ?>
        </td>
        <td class='dados'>
          <?php echo "{$sCodigoLicitacao}"; ?>
        </td>
      </tr>
      <tr>
        <td class='negrito'> 
          Departamento:
        </td>
        <td class='dados' colspan="3">
          <?php echo "{$oProcesso->p58_coddepto} - $oProcesso->descrdepto"?>
        </td>
        
      </tr>
      <tr>
        <td class='negrito'> 
          Titular do Processo:
        </td>
        <td class='dados' colspan="3">
          <?php echo $oProcesso->z01_nome ?>
        </td>
      </tr>
      <tr>
        <td class='negrito'> 
          Requerente:
        </td>
        <td class='dados' colspan="3">
          <?php echo $oProcesso->p58_requer?>
        </td>
      </tr>
      <tr>
        <td class='negrito'> 
          Atendente:
        </td>
        <td class='dados' colspan="3">
          <?php echo $oProcesso->nome ?>
        </td>
      </tr>
      <tr>
        <td class='negrito'> 
          Instituição:
        </td>
        <td class='dados' colspan="3" >
          <?php echo "{$oProcesso->p58_instit} - $oProcesso->nomeinst"?>
        </td>
      </tr>
      <tr id="trObservacaoInicial">
        <td class='negrito'> 
          Observações:
        </td>
        <td class='dados' colspan="3">
          <textarea id="textAreaObs" rows="3" style="resize:none;overflow:auto; width: 100%; border: none;" readonly="readonly"><?php echo "{$oProcesso->p58_obs}"?></textarea>
        </td>
      </tr>
    </table>
  </fieldset>
  <fieldset >
    <legend class='negrito'>Detalhes do Processo</legend>
    <?php 
    
      $oVerticalTab = new verticalTab('detalhesProcesso', 350);
      $sGetUrl      = "codigo_processo={$oProcesso->p58_codproc}";
          
      $oVerticalTab->add('dadosMovimentações', 'Movimentações', 
                         "pro3_listamovimentacoesprocesso.php?{$sGetUrl}");  
      
      if ($oProcesso->processo_principal == '') {
        
        $oVerticalTab->add('dadosApensados', 'Apensados',
        									 "pro3_listaprocessoapensado.php?{$sGetUrl}");
      }

      $oVerticalTab->add('dadosDocumentosAdicionados', 'Documentos',
                         "pro3_documentosvinculados.php?{$sGetUrl}");


      $oVerticalTab->add('dadosImprimir', 'Imprimir',
          							 "pro3_imprimirconsultaprocesso.php?{$sGetUrl}&processo_principal={$oProcesso->processo_principal}&p58_numero={$oProcesso->p58_numero}&p58_ano={$oProcesso->p58_ano}");
      
      $oVerticalTab->show();
                         
    ?>
  </fieldset>
</body>
</html>

<script>

/**
 * Função criada para mover o campo observação de acordo com a 
 * resolução do usuário.
 */
function js_verificarResolucaoUsuario() {
  
  var iClientWidth = new Number(document.body.clientWidth);
  
  if (iClientWidth > 800) {

    /**
     * Criamos um fieldset
     */
    var oFieldset          = document.createElement('fieldset');
    oFieldset.id           = 'fieldsetObservacao';
    oFieldset.style.width  = '97%';
    oFieldset.style.marginTop = '0';

    /**
     * Criamos a legenda e adicionamos ela ao fieldset
     */
    var oLegend       = document.createElement('legend');
    oLegend.id        = 'legendObservacao';
    oLegend.innerHTML = "<b>Observações</b>";
    oFieldset.appendChild(oLegend);

    /**
     * Setamos o estilo no textarea e adicionamos ele ao fieldset
     */
    $('textAreaObs').style.width  = '100%';
    $('textAreaObs').rows   = '8';
    oFieldset.appendChild($('textAreaObs'));
    $("tdObservacoes").appendChild(oFieldset);
    $("tdObservacoes").vAlign = 'top';

    /**
     * Removemos a TR que armazenava o campo observação inicialmente.
     */
    $('trObservacaoInicial').remove();
  }
}

function consultaLicitacao(iCodigoLicitacao) {

  if (iCodigoLicitacao == 0) {
    
    alert("Este processo não possui licitação vinculada.");
    return false;
  }
  var sURLLicitacao = "lic3_licitacao002.php?l20_codigo="+iCodigoLicitacao
  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_licitacao'+iCodigoLicitacao, sURLLicitacao, 'Consulta de Licitação', true);
}

js_verificarResolucaoUsuario();
</script>