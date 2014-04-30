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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$oRotuloOrcreceita = new rotulo("orcreceita");
$oRotuloOrcreceita->label();

$oRotuloOrcfontes = new rotulo("orcfontes") ;
$oRotuloOrcfontes->label();

$oRotuloOrcreceita = new rotulo("orcreceita") ;
$oRotuloOrcreceita->label();

$oRotuloConlancamval = new rotulo("conlancamval") ;
$oRotuloConlancamval->label();

$oRotuloConhist = new rotulo("conhist") ;
$oRotuloConhist->label();

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
      <fieldset style="width: 650px;height:300; margin-top:50px;" > 
        <legend><b>Reconhecimento de Receita pelo Fator Gerador</b></legend>
        <table border="0">
         <!-- Receita orçamento @tabela: orcreceita -->
          <tr>
             <td>
              <?
              db_ancora("<b>Receita Orçamento:</b>", 'js_receitas(true)', 1);
              ?>
            </td>
            <td>
              <?
              db_input('o70_codrec', 8, $Io70_codrec, true, 'text', 1, "onchange='js_receitas(false)'");
              db_input('o57_descr', 60, $Io57_descr, true, 'text', 3);
              ?>
            </td>
          </tr>
          <!-- Conta Débito-->
          <tr>
             <td>
              <b>Conta Débito:</b>
            </td>
            <td>
              <?
              db_input('o57_codfon_debito', 8, $Io70_codrec, true, 'text', 3);
              db_input('c57_descr_debito', 60, $Io70_codrec, true, 'text', 3);
              ?>
            </td>
          </tr>
          <!-- Conta Crédito @tabela: orcfontes -->
          <tr>
             <td>
              <strong>Conta Crédito:</strong>
            </td>
            <td>
              <?
              //c60
              db_input('o57_codfon_credito', 8, $Io70_codrec, true, 'text', 3);
              db_input('c57_descr_credito', 60, $Io70_codrec, true, 'text', 3);
              ?>
            </td>
          </tr>
          <!-- Histórico @tabela: conlancamval @campo:c69_codhist-->
          <tr>
             <td>
              <strong>Histórico:</strong>
            </td>
            <td>
              <?
              db_input('c50_codhist', 8, $Ic50_codhist, true, 'text', 3);
              db_input('c50_descr', 60, $Ic50_descr, true, 'text', 3);
              ?>
            </td>
          </tr>
          <!-- Valor Orçado  -->
          <tr>
            <td>
              <strong>Valor Orçado:</strong>
            </td>
            
            <td>
              <?
              db_input('o70_valor', 8, $Io70_valor, true, 'text', 3);
              ?>
            </td>
          </tr>
          <!-- Valor Orçado @tabela: orcreceita -->
          <tr>
            <td>
              <strong>Valor Lançado:</strong>
            </td>
            
            <td>
              <?
              db_input('valor_lancado', 8, $Io70_valor, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <fieldset>
              <legend><b>Motivo</b></legend>
                <textarea style="width: 100%;" rows="8" id='motivo'></textarea>
              </fieldset>
            </td>
          </tr>
          
        </table>
      </fieldset>
    </br>
    <center>
    <input type='button' name='btnSalvar' id='btnSalvar' value='Salvar' onclick='js_salvarDados()'/>
    <input type='reset' name='btnReset' id='btnReset' value='Novo'/>
    </center>
  </div>
  </form>
  
  </div>
  </center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>  
  
  </body>
  </html>
<script>

var sUrlRPC = 'con4_reconhecimentoreceitafatogerador.RPC.php';


/*
 * função será responsavel por gravar os dados "salvar"
 */

function js_salvarDados() {

  var oParam                = new Object();
  oParam.exec               = 'salvarDados';
  oParam.o70_codrec         = $F('o70_codrec');
  oParam.c50_codhist        = $F('c50_codhist');
  oParam.o57_codfon_credito = $F('o57_codfon_credito');
  oParam.o57_codfon_debito  = $F('o57_codfon_debito');
  oParam.valor_lancado      = $F('valor_lancado');
  oParam.sMotivo            = encodeURIComponent(tagString($F('motivo')));

  if (oParam.o70_codrec == "") {
    
    alert('Selecione uma receita orçamento.');
    return false;
  }

  if (oParam.sMotivo == '') {
    
    alert('Preencha o motivo.');
    return false;
  }

  js_divCarregando("Aguarde, buscando dados das contas ...", "msgBox");
  
  var oAjax = new Ajax.Request(sUrlRPC,{
                               method     : 'post',
                               parameters : 'json=' + Object.toJSON(oParam),
                               onComplete : js_retornoSalvar
                             });
  
}

function js_retornoSalvar(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  form1.reset();
}


/* 
 * Requisição ajax que retornara a conta debito , credito, historico e valor orcado relacionada a receita selecionada.
 */

function js_buscaDadosReceita () {


  var oParam                = new Object();
  oParam.exec               = 'buscaDadosReceita';
  oParam.o70_codrec         = $F('o70_codrec');
  
  js_divCarregando("Aguarde, buscando dados das contas ...", "msgBox");
  
  var oAjax = new Ajax.Request(sUrlRPC,{
                               method     : 'post',
                               parameters : 'json=' + Object.toJSON(oParam),
                               onComplete : js_preencheDadosReceita
                             });
  
}

/**
 * Função para preenchimento da tela após busca dos dados no RPC
 */
function js_preencheDadosReceita(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMessage.urlDecode());
    $('form1').reset();
    return false;
  }
  

  $('o57_codfon_debito').value  = oRetorno.o57_codfon_debito;
  $('o57_codfon_credito').value = oRetorno.o57_codfon_credito;
  $('c50_codhist').value        = oRetorno.c50_codhist;
  $('c50_descr').value          = oRetorno.c50_descr.urlDecode();
  $('c57_descr_debito').value   = oRetorno.c57_descr_debito.urlDecode();
  $('c57_descr_credito').value  = oRetorno.c57_descr_credito.urlDecode();
  $('o70_valor').value          = oRetorno.o70_valor;
}


/**
 * funcao de pesquisa para receitas
 */
function js_receitas(lMostra){
    
    if (lMostra == true) {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_orcreceita', 'func_orcreceita.php?lReceitaLancada=true&funcao_js=parent.js_mostraReceita|o70_codrec|o57_descr', 'Pesquisa', true);
    } else {
     
      js_OpenJanelaIframe('top.corpo', 'db_iframe_orcreceita', 'func_orcreceita.php?lReceitaLancada=true&pesquisa_chave=' + $F('o70_codrec') + '&funcao_js=parent.js_mostraReceita1', 'Pesquisa', false);
    }	 
 }
 function js_mostraReceita(iCodigoReceita, sDescricao){

   $('o70_codrec').value = iCodigoReceita;
   $('o57_descr').value  = sDescricao;
   db_iframe_orcreceita.hide();
   js_buscaDadosReceita();
 }
 
 function js_mostraReceita1(iCodigoReceita, lErro){   

   if (lErro == 'false' || lErro == false) {
     $('o57_descr').value = iCodigoReceita;
   } else {

     $('o70_codrec').value = "";
     $('o57_descr').value  = iCodigoReceita;
   }
   js_buscaDadosReceita();
 }

</script>