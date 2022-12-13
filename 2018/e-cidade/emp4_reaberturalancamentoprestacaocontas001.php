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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$oEmpenho = new rotulo("empempenho");
$oEmpenho->label();

$oEmpPresta = new rotulo("emppresta");
$oEmpPresta->label();
?>
<!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, prototype.js, strings.js");
db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
  <form>
    <fieldset style="margin-top: 25px; width: 500px;">
      <legend><b>Processar Reabertura</b></legend>
      <table>
        <tr style="display: none">
          <td nowrap="nowrap">
            <?php
              db_input('e45_sequencial', 10, $Ie45_sequencial, true, 'hidden', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <?php echo $Le45_numemp;?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('e45_numemp', 10, $Ie45_numemp, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <?php echo $Le45_codmov;?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('e45_codmov', 10, $Ie45_codmov, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <?php echo $Le45_acerta;?>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('e45_acerta', 10, $Ie45_acerta, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <b>Credor</b>
          </td>
          <td nowrap="nowrap">
            <?php
              db_input('credor', 40, '', true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id='btnProcessar' name='btnProcessar' value='Processar'>
  </form>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

var sRpc = "emp4_suprimentoFundos.RPC.php";

/**
 * Pesquisa os empenhos de prestacao de conta onde:
 * dtFechamento (e45_conferido) is null
 * dtAcertoPrestacaoContas (e45_acerta) is not null;
 */
function js_pesquisa() {

  var sUrl  = 'func_emppresta.php?';
  sUrl     += 'fechamento=null&acertoPrestacaoContas=notnull&exibeMovimento=1'; 
  sUrl     += '&funcao_js=parent.js_preenchePesquisa|e45_sequencial|e60_numemp|e45_codmov|z01_nome|e45_acerta';
    
  js_OpenJanelaIframe('',
                      'db_iframe_emppresta',
                      sUrl,
                      'Pesquisa',true);
}

function js_preenchePesquisa(iSequencial, iNumeroEmpenho, iCodigoMovimento, sCredor, dtAcertoPrestacaoContas) {
  
  db_iframe_emppresta.hide();
  $('e45_sequencial').value = iSequencial;
  $('e45_numemp').value     = iNumeroEmpenho;
  $('e45_codmov').value     = iCodigoMovimento;
  $('credor').value         = sCredor;
  $('e45_acerta').value     = js_formatar(dtAcertoPrestacaoContas, 'd');
}
js_pesquisa();


/**
 * Processa reabertura
 */
$('btnProcessar').observe('click', function() {

  var oObject            = new Object();
  oObject.exec           = "reaberturaPrestacaoConta";
  oObject.iSequencialEmpenho = $F('e45_sequencial');
  
  js_divCarregando('Processando Reabertura...','msgBox');
  var objAjax   = new Ajax.Request (sRpc,{
                                          method:'post',
                                          parameters:'json='+Object.toJSON(oObject), 
                                          onComplete:js_retornoReabertura
                                         }
                                   );
  
});

function js_retornoReabertura(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");
  
  alert(oRetorno.message.urlDecode());
  js_pesquisa();

}



</script>