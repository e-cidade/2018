<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
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
  db_app::load("DBViewAlvaraDocumentos.js");
?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 25px;">
<center>
  <form name="form1" id="form1" methos="post" action="" >
    <? db_input('q120_issalvara',8,'',true,'hidden', 3,"",""); ?>
    <fieldset style="width:400px;">
      <legend><strong>Cancelamento de Movimenta��o de Alvar�:</strong></legend>
      <table width="100%">
        <tr>
          <td nowrap="nowrap"><? db_ancora("<b>Inscri��o:</b>", "js_pesquisaAlvara(true,'');", 1); ?></td>
          <td nowrap="nowrap"><? db_input('q123_inscr',8,'',true,'text', 1,"onchange='js_pesquisaAlvara(false,this.value);'") ?></td>
          <td nowrap="nowrap"><? db_input('z01_nome',40,'',true,'text', 3,"","") ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap"><b>Tipo do movimento:</b></td>
          <td nowrap="nowrap" colspan="2"><? db_input('q121_descr', 52, "", true, 'text', 3); ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap"><b>Data da movimenta��o:</b></td>
          <td nowrap="nowrap" colspan="2"><? db_input('q120_dtmov', 15, "", true, 'text', 3); ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap" colspan="3">
            <fieldset>
              <legend><strong>Resumo do cancelamento:</strong></legend>
              <? db_textarea('q120_obs', 10, 67, '', true, 'text', 1); ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    <br>
    <div id="ctnDocumentos" style="width:560px;"></div><!-- div que vai receber o grid de documentos -->
    <br>
    <input type="button" name="btnSalvar" id="btnSalvar" value="Processar" disabled="disabled" onclick="js_cancelaUltimaMovimentacao();">&nbsp;
    <input type="button" name="btnLimpaCampos" id="btnLimpaCampos" value="Limpar campos" onclick="js_limpaCampos();">&nbsp;
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>

<script language="JavaScript" type="text/javascript">

  var iCodigoMovimentacaoRetorno = new Number();
  /* inst�ncia do objeto que monta a grid de documentos no formul�rio */
  
  var oDocumentos = new DBViewAlvaraDocumentos('oDocumentos', 'ctnDocumentos');
      oDocumentos.show();
  
  /* fun��es que fazem a pesquisa da inscri��o/alvar� */
  
  function js_pesquisaAlvara(lMostra, iCodigo) {
    
    var sUrl = '';
    
    if (iCodigo == '') {
      sUrl = 'func_issalvaracancelamento.php?funcao_js=parent.js_mostraAlvara|q123_inscr|z01_nome|q123_sequencial';
    } else {
      sUrl = 'func_issalvaracancelamento.php?pesquisa_chave='+iCodigo+'&funcao_js=parent.js_mostraAlvara2';
    }
    js_OpenJanelaIframe('top.corpo', 'db_iframe_alvara', sUrl, 'Pesquisa Alvar�s', lMostra);
  }
  
  /** ao mostrar o alvar� o script chama o RPC para buscar as informa���es que ser�o jogadas no formul�rio */
                     
  function js_mostraAlvara(sAlvara, sDetalhe, iCodigoAlvara) {
 
    $('q123_inscr').value     = sAlvara;
    $('z01_nome').value       = sDetalhe;
    $('q120_issalvara').value = iCodigoAlvara;
    oDocumentos.setCodigoAlvara(iCodigoAlvara);
    oDocumentos.carregaDados();
    db_iframe_alvara.hide();
    /* depois de preencher os campos, chama a fun��o que popula o resto do formul�rio */
    buscaUltimaMovimentacao();
  }
  
  function js_mostraAlvara2(sAlvaraOuTextoErro, lErro, sInscricao, sDescr) {
  
    if (lErro == true) {
    
      $('q123_inscr').value     = '';
      $('z01_nome').value       = sAlvaraOuTextoErro;
      $('q120_issalvara').value = '';
    } else {
    
      $('q123_inscr').value     = sInscricao;
      $('z01_nome').value       = sDescr;
      $('q120_issalvara').value = sAlvaraOuTextoErro;
      oDocumentos.setCodigoAlvara(sAlvaraOuTextoErro);
      oDocumentos.carregaDados();
      db_iframe_alvara.hide();
      /* depois de preencher os campos, chama a fun��o que popula o resto do formul�rio */
      buscaUltimaMovimentacao();
    }
  }
  
  /* chamada ao RPC para trazer os dados da �ltima movimenta��o para o formul�rio */
  
  function buscaUltimaMovimentacao() {
    
    js_divCarregando("Aguarde, processando...", "msgBox");
    var oParam            = new Object();
    oParam.exec           = "buscaUltimaMovimentacao";
    oParam.q120_issalvara = $F('q120_issalvara');
    var oAjax = new Ajax.Request("iss4_cancelamentoalvara.RPC.php",
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                                js_removeObj("msgBox");
                                                var aRetorno = eval("("+oAjax.responseText+")");
                                               // alert(oAjax.responseText);
                                                if (aRetorno.status == 1) {
                                                
                                                  with(aRetorno.aUltimaMovimentacao) {
	                                                  $('q121_descr').value      = q121_descr.urlDecode();
	                                                  $('q120_dtmov').value      = js_formatar(q120_dtmov,"d");
                                                    iCodigoMovimentacaoRetorno = q120_isstipomovalvara;
                                                  }
                                                } else {
                                                  alert('Erro: ' + aRetorno.message.urlDecode().replace(/\\n/g,"\n"));
                                                }
                                              }
                                 });
    $('btnSalvar').disabled   = false;
  }
  
  /* efetua o cancelamento da �ltima movimenta��o */
  
  function js_cancelaUltimaMovimentacao() {
  
    js_divCarregando("Aguarde, processando...", "msgBox");
    if ($F('q120_issalvara') == '' || $F('q123_inscr') == '') {
    
      alert('Voc� deve informar alguma inscri��o para cancelar seu �ltimo movimento.');
      return false;
    }
    
    var oParam                   = new Object();
    oParam.exec                  = "cancelaUltimaMovimentacao";
    oParam.q120_issalvara        = $F('q120_issalvara');
    oParam.q120_obs              = $F('q120_obs');
    oParam.q120_isstipomovalvara = iCodigoMovimentacaoRetorno;
    oParam.aDocumentos           = oDocumentos.getDocumentosSelecionados();
    var oAjax = new Ajax.Request("iss4_cancelamentoalvara.RPC.php",
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                                js_removeObj("msgBox");
                                                var aRetorno = eval("("+oAjax.responseText+")");
                                                alert(aRetorno.message.urlDecode());
                                                if (aRetorno.status == 1) {
                                                  window.location = 'iss4_cancelamentoalvara001.php';
                                                }
                                              }
                                 });
  }
  
  /* fun��o que faz a limpeza dos campos */
  
  function js_limpaCampos() {
    
    $('q123_inscr').value     = '';
    $('z01_nome').value       = '';
    $('q120_issalvara').value = '';
    $('q121_descr').value     = '';
    $('q120_dtmov').value     = '';
    $('q120_usuario').value   = '';
    $('q120_obs').value       = '';
  }
      
</script>