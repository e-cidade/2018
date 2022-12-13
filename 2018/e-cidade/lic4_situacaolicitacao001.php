<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("model/licitacao.model.php");

$oRotulo = new rotulocampo;
$oRotulo->label("l20_codigo");
$oRotulo->label("l20_edital");

$oGet                = db_utils::postMemory($_GET);
$iCodigoTipoSituacao = $oGet->iCodigoTipoSituacao;
$iOpcao              = $oGet->iOpcao;
unset($oGet);

switch ($iCodigoTipoSituacao) {


  case 3:

    $sTipoSituacao   = "Deserta";
    $sLegendSituacao = "Licitação Deserta";
    break;

  case 4 :

    $sTipoSituacao = "Fracassada";
    $sLegendSituacao = "Licitação Fracassada";
    break;

  case 5 :

    $sTipoSituacao = "Anulada";
    $sLegendSituacao = "Anular Licitação";
    break;
}


switch ($iOpcao) {

  case 1 :

    $sAcao    = 'Salvar';
    $sAcaoRPC = $sAcao;
    break;

  case 2 :

    $sAcao    = 'Salvar';
    $sAcaoRPC = $sAcao.$sTipoSituacao;
    break;

  case 3:

    $sAcao    = 'Salvar';
    $sAcaoRPC = $sAcao;
    break;
  
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 25px;">
<center>
<form id = "form1" name="form1" method="post" action="">
<fieldset style="width: 400px";>
  <legend><b><?php echo $sLegendSituacao; ?></legend>
  
  <table border = '0'>
  
  <!-- Licitação -->
    <tr> 
      <td  align = "left" nowrap title = "<?=$Tl20_codigo?>" width="95">
        <b>
          <?
          $iOpcaoAncora   = 1;
          db_ancora('Licitação:', "js_pequisaLicitacao({$iOpcao});", $iOpcaoAncora);?>
        </b> 
      </td>
      <td align = "left" nowrap>
        <? 
          db_input("l20_codigo", 6, $Il20_codigo, true, "text", 3);
        ?>
      </td>
    </tr>
  
    <!-- Edital -->
    <tr> 
      <td  align = "left" nowrap title = "<?=$Tl20_codigo?>">
        <b>Edital:</b>
      </td>
      <td align = "left" nowrap>
        <? 
          db_input("l20_edital", 6, $Il20_edital, true, "text", 3);
        ?>
      </td>
    </tr>
  
    <!-- Motivo -->
    <tr>
      <td colspan="2">
        <fieldset>
          <legend><b>Motivo</legend>
            <?
              db_textarea("l11_obs", 10, 60, "", true, "text", $iOpcao);
            ?>
          </fieldset>
      </td>
    </tr>
</table>
</fieldset>

<br />
<?php
  db_input("iSituacaoSequencial", 10, null, false, 'hidden', 3); 
?>
<input name    = "btnSalvarSituacao" 
       type    = "button" 
       id      = "db_opcao" 
       onclick = "return js_salvarOperacao()"
       value   = <?php echo $sAcao; ?> />
</form>
</center>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>

<script>

var oGet          = js_urlToObject(window.location.search);
var iTipoSituacao = oGet.iCodigoTipoSituacao;
var iOpcao        = oGet.iOpcao;
var sPesquisaTipo = "situacao=0&";
var sUrlRPC       = "lic4_situacaolicitacao.RPC.php";

if (iOpcao > 1) {

  sPesquisaTipo     = "situacao="+iTipoSituacao+"&";
  var sUrlLicitacao = "func_liclicita.php?"+sPesquisaTipo+"funcao_js=parent.js_getDadosSituacaoLicitacao|l11_sequencial";
  js_OpenJanelaIframe('top.corpo', 'db_iframe_liclicitasituacao', sUrlLicitacao, 'Pesquisa Licitações', true);
}

function js_getDadosSituacaoLicitacao(iCodigoAlteracao) {

  db_iframe_liclicitasituacao.hide();

  var oParam              = new Object();
  oParam.iCodigoAlteracao = iCodigoAlteracao;
  oParam.exec             = "getDadosSituacaoLicitacao";

  js_divCarregando("Aguarde, pesquisando...", "msgBox");
  
  var oAjax = new Ajax.Request(sUrlRPC,
                                    {
                                    method:'post',
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: function (oAjax) {

                                      js_removeObj("msgBox");
                                      var oRetorno = eval("("+oAjax.responseText+")");

                                      if (oRetorno.status == 1) {

                                        $('iSituacaoSequencial').value = oRetorno.l11_sequencial;
                                        $('l20_codigo').value          = oRetorno.iCodigoLicitacao;
                                        $('l11_obs').value             = oRetorno.l11_obs.urlDecode();
                                        $('l20_edital').value          = oRetorno.iCodigoEdital;
                                        
                                      } else {
                                        alert(oRetorno.message.urlDecode());
                                      }
                                    }
                                });
}

function js_pequisaLicitacao(iOpcao) {

  if (iOpcao > 1) {
    
    sPesquisaTipo     = "situacao="+iTipoSituacao+"&";
    var sUrlLicitacao = "func_liclicita.php?"+sPesquisaTipo+"funcao_js=parent.js_getDadosSituacaoLicitacao|l11_sequencial";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_liclicitasituacao', sUrlLicitacao, 'Pesquisa Licitações', true);
    
  } else {

  var sUrlLicitacao = "func_liclicita.php?"+sPesquisaTipo+"funcao_js=parent.js_preencheDados|l20_codigo|l20_edital"
  js_OpenJanelaIframe('top.corpo', 'db_iframe_liclicita', sUrlLicitacao, 'Pesquisa Licitações', true);
  }
}

function js_preencheDados(iCodigoLicitacao, iCodigoEdital, sObservacao) {


  $('l20_codigo').value = iCodigoLicitacao;
  $('l20_edital').value = iCodigoEdital;

  if(sObservacao != undefined) {
    
    $('l11_obs').value    = sObservacao;
  }
  
  db_iframe_liclicita.hide();
}

function js_salvarOperacao() {

  /** 
  *  validar existência licitação
  */
  
  if($F("l20_codigo") == "") {
	
		alert("Seleciona uma licitação");
		return false;
	}
  
  
  if (iOpcao == 1) {
    sAcaoRPC = "incluir";
  } else if (iOpcao == 2) {
    sAcaoRPC = "alterar";
  } else if (iOpcao == 3) {
    sAcaoRPC = "cancelar";
  } else {

    alert("Opção não identificada.");
    return false;
  }

  
  if ($F('l11_obs').trim() == "") {
    
    alert("Informe um motivo.");
    return false;
  }

  var oParam                = new Object();
  oParam.iCodigoEdital      = $F('l20_edital');
  oParam.iCodigoLicitacao   = $F('l20_codigo');
  oParam.sObservacao        = encodeURIComponent(tagString($F('l11_obs')));
  oParam.iTipoSituacao      = iTipoSituacao;
  oParam.exec               = sAcaoRPC;
  oParam.iSituacaoSequencial = $F('iSituacaoSequencial');
  sUrl                      = "lic4_situacaolicitacao.RPC.php";

  //console.log(oParam);
  // return false;

  js_divCarregando("Aguarde, salvando situação...", "msgBox");
  
  var oAjax = new Ajax.Request(sUrl,
                                    {
                                    method:'post',
                                    parameters:'json='+Object.toJSON(oParam),
                                      onComplete: js_retornaLicitacao
                                    });

}

function js_retornaLicitacao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  $("form1").reset();
}

</script>