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
  
    
      <fieldset style=" height:300; margin-top:50px;" > 
       
        <legend><b>Estorno de Reconhecimento de Receita pelo Fator Gerador</b></legend>
        <table border="0">
        
         <!-- Receita orçamento @tabela: orcreceita -->
          <tr>
             <td>
              <b>Receita Orçamento:</b>
            </td>
            
            <td>
              <?
              db_input('o70_codrec', 10, $Io70_codrec, true, 'text', 3);
              db_input('o57_descr', 40, $Io57_descr, true, 'text', 3);
              db_input('c81_sequencial', 10, $Io70_codrec, true, 'hidden', 3);
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
              db_input('o57_codfon_debito', 10, $Io70_codrec, true, 'text', 3);
              db_input('c57_descr_debito', 40, $Io70_codrec, true, 'text', 3);
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
                db_input('o57_codfon_credito', 10, $Io70_codrec, true, 'text', 3);
                db_input('c57_descr_credito', 40, $Io70_codrec, true, 'text', 3);
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
              db_input('c50_codhist', 10, $Ic50_codhist, true, 'text', 3);
              db_input('c50_descr', 40, $Ic50_descr, true, 'text', 3);
              ?>
            </td>
          </tr>
          
          <!-- Valor Orçado  -->
          
          <tr>
            <td>
              <strong>Valor Orçado</strong>
            </td>
            
            <td>
              <?
              db_input('o70_valor', 10, $Ic69_valor, true, 'text', 3);
              ?>
            </td>
          </tr>
          
          <!-- Valor Orçado @tabela: orcreceita -->
          
          <tr>
            <td>
              <strong>Valor Lançado</strong>
            </td>
            
            <td>
              <?
              db_input('valor_lancado', 10, $Ic69_valor, true, 'text', 3);
              ?>
            </td>
          </tr>
          
          <tr>
            <td colspan="3">
              <fieldset>
              <legend><b>Motivo Estorno</b></legend>
                <?php  
                  db_textarea('motivo',7,70,"",true,"",1);
                  ?>
              </fieldset>
            </td>
          </tr>
          
        </table>
      </fieldset>
    </br>
    <center>
    <input type='button' name='btnSalvar'    id='btnSalvar'    value='Salvar' onclick='js_estornarDados()'/>
    <input type='reset'  name='btnReset'     id='btnReset'     value='Novo'/>
    <input type='button' name='btnPesquisar' id='btnPesquisar' value='Pesquisar' onclick='js_receitas()' />
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

function js_estornarDados() {

  if (!confirm("Confirma o estorno do lançamento?")) {
    return false;
  }
  

  var oParam                = new Object();
  oParam.exec               = 'estornarReceitaFatoGerador';
  oParam.o70_codrec         = $F('o70_codrec');              
  oParam.c50_codhist        = $F('c50_codhist');             
  oParam.o57_codfon_credito = $F('o57_codfon_credito');      
  oParam.o57_codfon_debito  = $F('o57_codfon_debito');       
  oParam.valor_lancado      = $F('valor_lancado');           
  oParam.sMotivo            = encodeURIComponent(tagString($F('motivo')));     
  oParam.c81_sequencial     = $F('c81_sequencial');           

  if (oParam.o70_codrec == "") {
    
    alert('Nenhuma Receita Orçamento selecionada.');
    return false;
  }

  if (oParam.sMotivo == '') {
    
    alert('Para estornar, preencha o motivo do estorno.');
    return false;
  }

  js_divCarregando("Aguarde estorno ...", "msgBox");
  
  var oAjax = new Ajax.Request(sUrlRPC,{
                               method     : 'post',
                               parameters : 'json=' + Object.toJSON(oParam),
                               onComplete : js_retornoEstornar
                             });
  
}

function js_retornoEstornar(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  form1.reset();  
}




/* 
 * Requisição ajax que retornara a conta debito , credito, historico e valor orcado relacionada a receita selecionada.
 */
function js_preencheReceita() {

  var oParam                = new Object();
  oParam.exec               = 'buscaDadosReceitaEstorno';
  oParam.o70_codrec         = $F('o70_codrec');
  
  js_divCarregando("Aguarde, buscando dados das contas ...", "msgBox");
  
  var oAjax = new Ajax.Request(sUrlRPC,{
                               method     : 'post',
                               parameters : 'json=' + Object.toJSON(oParam),
                               onComplete : js_finalizaPreencheDadosReceita
                             });
  
}

/**
 * Função para preenchimento da tela após busca dos dados no RPC
 */
function js_finalizaPreencheDadosReceita(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval("("+oAjax.responseText+")");

  $('o57_codfon_debito') .value  = oRetorno.o57_codfon_debito;                 
  $('c57_descr_debito')  .value  = oRetorno.c57_descr_debito.urlDecode();   
  $('o57_codfon_credito').value  = oRetorno.o57_codfon_credito;
  $('c57_descr_credito') .value  = oRetorno.c57_descr_credito.urlDecode();
  $('c50_codhist')       .value  = oRetorno.c50_codhist;          
  $('c50_descr')         .value  = oRetorno.c50_descr.urlDecode();            
  $('o70_valor')         .value  = oRetorno.o70_valor;    
}

/**
 * funcao de pesquisa para receitas
 */
function js_receitas(){
      js_OpenJanelaIframe('top.corpo', 'db_iframe_orcreceita', 'func_orcreceitaEstornoReceitaFatoGerador.php?funcao_js=parent.js_mostraReceita|o70_codrec|o57_descr|c70_valor|c81_sequencial', 'Pesquisa', true);
 }
 
 function js_mostraReceita(iCodigoReceita, sDescricao, nValor, iAberturaExercicio){

   $('o70_codrec')     . value = iCodigoReceita;
   $('o57_descr')      . value = sDescricao;
   $('valor_lancado')  . value = nValor;
   $('c81_sequencial') . value = iAberturaExercicio;
   db_iframe_orcreceita.hide();
   js_preencheReceita();
 }
 
js_receitas();
</script>