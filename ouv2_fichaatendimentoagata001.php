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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load ( 'strings.js,scripts.js,datagrid.widget.js,prototype.js' );
db_app::load ( 'estilos.css,grid.style.css' );
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<center>  
<div style="margin-top: 25px; width:620px;" >
  <fieldset>
    <legend>
      <b>Emissão da ficha de atendimento</b>
    </legend>
    <table>
      <tr>
        <td nowrap="nowrap">
          <?
            db_ancora('<b>Número do Atendimento:</b>', ' js_pesquisaNumeroAtendimento(true); ', '');
          ?>
        </td>
        <td nowrap="nowrap">
          <?
            db_input('ov01_numero',     10, "", true, 'text', 1, ' onchange="js_pesquisaNumeroAtendimento(false); "');
            db_input('ov01_requerente', 40,  0, true, 'text', 3, '');
            db_input('ov01_anousu',      5, "", true, 'hidden', 1, '');
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <div>
    <input type="button" name='Imprimir' value='imprimir' id='btnProcessar' />
  </div>
</div>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

/**
 * Efetua a pesquisa de número de atendimento.
 */
function js_pesquisaNumeroAtendimento(mostra) {
 
  if (document.getElementById('ov01_numero').value == '' && mostra == false) {
     
    $('ov01_numero').value      = '';
    $('ov01_requerente').value = '';
  } else {
    if (mostra == true) {
     
      var sUrlLookUp = 'func_ouvidoriaatendimento.php?funcao_js=parent.js_mostraNumeroAtendimento|ov01_numero|ov01_requerente|true|ov01_anousu';
      js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Atendimento', true);
    } else {
     
      var sValorPesquisa = $('ov01_numero').value;
      var sUrlLookUp     = 'func_ouvidoriaatendimento.php?requer=1&pesquisa_chave='+sValorPesquisa+'&funcao_js=parent.js_mostraNumeroAtendimento';
      js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Atendimento', false);
    }
  }
}
 
/**
 * Insere no formulário o retorno da pesquisa de numero de atendimento.
 */
function js_mostraNumeroAtendimento() { // tem que buscar qual o parâmetro correto pra esse método

  if (arguments[1] === true) {
    
    $('ov01_numero').value     = '';
    $('ov01_requerente').value = arguments[0];
  } else {
    $('ov01_numero').value     = arguments[0];
    $('ov01_requerente').value = arguments[1];
    $('ov01_anousu').value     = arguments[3];
  }
  db_iframe.hide();
}

$("btnProcessar").observe("click", function() {

  var ov01_numero = $F("ov01_numero");
  var ov01_anousu = $F("ov01_anousu");
  var aNumero     = ov01_numero.split('/');
  var sLocation   = "ouv2_fichaatendimentoagata002.php?ov01_numero="+aNumero[0]+"&ov01_anousu="+ov01_anousu;
  
  jan = window.open(sLocation, '', 
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});


$('ov01_numero').value     = "";
$('ov01_requerente').value = "";
$('ov01_anousu').value     = "";
</script>