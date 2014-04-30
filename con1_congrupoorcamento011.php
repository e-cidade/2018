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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$oGet                     = db_utils::postMemory($_GET);
$oRotuloCongrupoorcamento = new rotulo("congrupoorcamento");
$oRotuloCongrupoorcamento->label();

switch ($oGet->iOpcao) {
  
  case '1' :
    
    $iOpcaoCodigo    = 1;
    $iOpcaoDescricao = 1;
    break;
    
  case '2' :
    
    $iOpcaoCodigo    = 3;
    $iOpcaoDescricao = 1;
    break;
    
  case '3' :
    
    $iOpcaoCodigo    = 3;
    $iOpcaoDescricao = 3;
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
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    
    <style type="text/css">
      fieldset {
      
        top:50px;
        width:500px;
        }
        
        .divbody {
        
        margin-top:30px;
        }
    </style>
  </head>
  
  <body bgcolor="#CCCCCC" style="margin-top:25px" >
  <center>
  <div class='divbody' style="display:table;">    
    <form id="form1" name="form1">
      <fieldset>  
        <legend><b>Grupo do Plano Orçamentário</b></legend>
        <table border="0">
          
            <!-- Id Grupo-->
            <tr>
              
              <td>
                <b>
                 <?php 
                 db_ancora('Codigo', "pesquisaGrupo(false);", 1);
                 ?>
                 </b>
              </td>
              
              <td>
                <?
                db_input('c20_sequencial', 10, $Ic20_sequencial, true, 'text', 3);
                ?>
              </td>
            </tr>
            
            <!-- Descrição Grupo-->
            <tr>
              <td>
                <b>Descrição:</b>
              </td>
              
              <td>
                <?
                db_input('c20_descr', 50, $Ic20_descr, true, 'text', $iOpcaoDescricao);
                ?>
              </td>
            </tr>
            
        </table>
      </fieldset>
      <center>
      <br>
      <input name    = "btnSalvar" 
             type    = "button" 
             id      = "btnSalvar" 
             onclick = "js_salvaGrupo()"
             value   = "Salvar"/>
       
      <input name    = "btnNovo" 
              type    = "button" 
              id      = "btnNovo" 
              onclick = "js_novoGrupo()"
              value   = "Novo"/>
      
       <input name    = "btnExcluir" 
              type    = "button" 
              id      = "btnExcluir" 
              onclick = "js_excluirGrupo()"
              value   = "Excluir"/>
      </center>
    </form>
  </div>
  </center>
  </body>
</html>
<script>

//desabilita aba usando js                
parent.document.formaba.conplanogrupoorcamento.disabled=true;
                
var oGet          = js_urlToObject(window.location.search);
var iOpcao        = oGet.iOpcao;
var iCodigoGrupo  = '';
var sUrlRPC       = "con4_grupocontaorcamento.RPC.php";

if (iOpcao > 1) {
  
  var sUrlGrupo = "func_congrupoorcamento.php?funcao_js=parent.js_preencheDadosGrupo|c20_sequencial|c20_descr";
  js_OpenJanelaIframe('', 'db_iframe_congrupoorcamento', sUrlGrupo, 'Pesquisa Grupo', true);
}


function js_novoGrupo() {

  parent.document.formaba.conplanogrupoorcamento.disabled = true;

  $('c20_descr').style.backgroundColor = "#FFFFFF";
  $('c20_descr').readOnly = false;
  $('form1').reset();
} 

function pesquisaGrupo(teste) {

  var sUrlGrupo = "func_congrupo.php?funcao_js=parent.js_preencheDadosGrupo|c20_sequencial|c20_descr";
  js_OpenJanelaIframe('', 'db_iframe_congrupoorcamento', sUrlGrupo, 'Pesquisa Grupo', true);
}

function js_preencheDadosGrupo(iSequencial, sDescricao) {

  db_iframe_congrupoorcamento.hide();
  $('c20_sequencial').value = iSequencial;
  $('c20_descr').value      = sDescricao;

  if (iSequencial > 0 && iSequencial <= 1000) {
    $('c20_descr').style.backgroundColor = "#DEB887";
    $('c20_descr').readOnly = true;
  }
  iCodigoGrupo = iSequencial;
  js_liberaAba();
}


function js_salvaGrupo () {

  if ($F('c20_sequencial') >= 1 && $F('c20_sequencial') <= 1000) {

    alert('Este registro é padrão no sistema, portanto não poderá ser alterado.');
    return false;
  }

  if ($F('c20_descr') == '') {

    alert("Campo Descrição é obrigatório.");
    return false;
  }
  
  var oParam            = new Object();
  oParam.exec           = 'salvarGrupo';
  oParam.c20_sequencial = $F('c20_sequencial');
  oParam.c20_descr      = encodeURIComponent(tagString($F('c20_descr')));
  
  js_divCarregando("Aguarde, salvando situação...", "msgBox");  

  var oAjax = new Ajax.Request(sUrlRPC,
      {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: js_finalizaSalvarGrupo
      });
}

function js_finalizaSalvarGrupo (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  
  $('c20_sequencial').value       = oRetorno.c20_sequencial;
  iCodigoGrupo = oRetorno.c20_sequencial;
  js_liberaAba();
  
}

function js_excluirGrupo() {

  if ($F('c20_sequencial') == "") {

    alert("Nenhum grupo selecionado.");
    return false;
  }

  if ($F('c20_sequencial') >= 1 && $F('c20_sequencial') <= 1000) {

    alert('Este registro é padrão no sistema, portanto não poderá ser alterado.');
    return false;
  }
  
  var oParam            = new Object();
  oParam.exec           = 'excluiGrupo';
  oParam.c20_sequencial = $F('c20_sequencial');

  if (!confirm("Deseja excluir o Grupo?")){
    return false;
  }
  
  js_divCarregando("Aguarde, excluindo grupo...", "msgBox");  

  var oAjax = new Ajax.Request(sUrlRPC,
      {
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete: js_finalizaExcluirGrupo
      });
}

function js_finalizaExcluirGrupo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  parent.document.formaba.conplanogrupoorcamento.disabled=true;
  $('form1').reset();
}

/**
 * Libera aba para acesso, modificando o código do grupo no iframe via URL
 */
function js_liberaAba() {

  //desabilita aba usando js                
  parent.document.formaba.conplanogrupoorcamento.disabled=false;
  top.corpo.iframe_conplanogrupoorcamento.location.href   = "con1_congrupoorcamento012.php?iOpcao="+iOpcao+"&c20_sequencial="+iCodigoGrupo;
}

                
</script>