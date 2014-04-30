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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("model/configuracao/UsuarioSistema.model.php");

$oGet = db_utils::postMemory($_GET);

if (!USE_PCASP) {
	db_redireciona("db_erros.php?fechar=true&db_erro=Este menu só é acessível com o PCASP ativado.");
}

//c39 - inscricaopassivaanulada
$oRotuloInscricaoPassivaAnulada = new rotulo("inscricaopassivaanulada");
$oRotuloInscricaoPassivaAnulada->label();

//c36 - inscricaopassivo
$oRotuloInscricaoPassivo = new rotulo("inscricaopassivo");
$oRotuloInscricaoPassivo->label();
$oUsuario = new UsuarioSistema(db_getsession("DB_id_usuario"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px" >
  <center>
  <div style="display: table;">    
  <form id="form1" name="form1">
 
    <fieldset style="width:500;">  
      <legend><b>Baixa</b></legend>
      <table border="0">
        
        <tr>
          <td>
            <b>Código Anulação:</b>
          </td>
            
          <td>
            <?php 
            db_input('c39_sequencial', 10, $Ic39_sequencial, true, 'text', 3);
            ?>
          </td>
        </tr>
      
        <!-- Inscricao Passivo -->
        <tr>
          <td>
            <?
            db_ancora("<b>Inscricao Passivo:<b>", "js_pesquisaInscricaoPassivo(true);", 1);
            ?>
          </td>
          
          <td>
            <?
            $funcaoJsInscricaoPassivo = "onchange = 'js_pesquisaInscricao(false);'";
            db_input('c36_sequencial', 10, $Ic36_sequencial, true, 'text', 3, $funcaoJsInscricaoPassivo);
            ?>
          </td>
        </tr>
        
        <!-- Usuario -->
        <tr>
          <td>
          <b>Usuário:</b>
          </td>
          <td>
          
            <? 
            $nome_usuario    = $oUsuario->getIdUsuario() . " - " . $oUsuario->getNome();
            db_input('nome_usuario', 47, "", true, 'text', 3);
            ?>
          </td>
        </tr>
        
        <!-- Data -->
        <tr>
          <td>
          <b>Data:</b>
          </td>
          <td>
            <? 
            $c39_data = date("d/m/Y", db_getsession("DB_datausu"));
            db_input('c39_data', 10, $Ic39_data, true, 'text', 3);
            ?>
          </td>
        </tr>
  </table>
  
  <!-- Observacao da  Baixa -->
   <fieldset>  
      <legend><b>Observação</b></legend>
       <textarea id="c39_observacao" name="c92_regra" rows="10" cols="50" style="width: 476px; height: 100px;"></textarea>
    </fieldset>    
  </fieldset>
  <br />
  <center>
    <input id = "btnSalvar"  type = 'button' name = "btnSalvar"  value = 'Salvar'  onclick = "js_salvarAnulacao();">
  </center>
 
  </form>
  </div>
  
  </center>
</body>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>      

var sUrlRPC = 'con4_inscricaopassivoorcamento.RPC.php';
$("btnSalvar").disabled  = false;

/*
 * Pesquisa pela Inscricao Passivo
 */
function js_pesquisaInscricaoPassivo(lMostra) {

  var sUrlLookUp = "func_inscricaopassivobaixapagamento.php?funcao_js=parent.js_preencheInscricao|c36_sequencial";
  js_OpenJanelaIframe('', 'db_iframe_inscricaopassivo', sUrlLookUp, 'Pesquisa Inscricao Passivo', lMostra); 
  
}

/*
 * Função que preenche a Inscricao de acordo com a pesquisa da lookup
 */
function js_preencheInscricao(iSequencial) {

  $('c36_sequencial').value = iSequencial;
  db_iframe_inscricaopassivo.hide();
}


/*
 * Salva Formulario, enviando ao RPC 
 */
function js_salvarAnulacao() {

  if ($F('c36_sequencial') == "") {

		alert("Selecione a inscrição que deseja anular.");
		return false;
  }
  
  if ($F('c39_observacao') == "") { 
    
    alert('Preencha a Observação referente a baixa');
    return false;
  }
  var oParam                 = new Object();
      oParam.exec            = "anularInscricao";
      oParam.c39_sequencial  = $("c39_sequencial");
      oParam.c39_db_usuarios = $("c39_db_usuarios");
      oParam.c39_data        = $("c39_data").value;
      oParam.c39_observacao  = encodeURIComponent(tagString($("c39_observacao").value));
      oParam.c36_sequencial  = $("c36_sequencial").value;

  js_divCarregando("Aguarde, salvando dados da anulação...", "msgBox");
  var oAjax = new Ajax.Request(sUrlRPC,{
                               method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete: js_finalizaSalvarAnulacao});
}

/*
 * Finaliza o processo de salvar, efetuando retorno do Ajax e resetando formulario
 */
function js_finalizaSalvarAnulacao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
	alert(oRetorno.message.urlDecode());
	$('form1').reset();
}

</script>