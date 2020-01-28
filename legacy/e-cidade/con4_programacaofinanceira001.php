<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_acordo_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clacordo = new cl_acordo;
$clrotulo = new rotulocampo;

$clacordo->rotulo->label();
$clrotulo->label("ac16_sequencial");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               DBViewProgramacaoFinanceira.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,
               dbcomboBox.widget.js,datagrid.widget.js,widgets/dbtextFieldData.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>
<style>
td {
  white-space: nowrap;
}

fieldset table td:first-child {
  width: 80px;
  white-space: nowrap;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
  <tr> 
    <td valign="top" align="center"> 
      <fieldset>
        <legend><b>Programação Financeira</b></legend>
        <table align="center" border="0">
          <tr>
            <td title="<?=@$Tac16_sequencial?>" align="left">
              <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);",1); ?>
            </td>
            <td align="left">
              <?
                db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 
                         'text', 1, " onchange='js_pesquisaac16_sequencial(false);'");
              ?>
            </td>
            <td align="left">
              <?
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
$('ac16_sequencial').style.width   = "100%";
$('ac16_resumoobjeto').style.width = "100%";

/**
 * Pesquisa acordos
 */
function js_pesquisaac16_sequencial(lMostrar) {

  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?iTipoFiltro=4&funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordo', 
                        sUrl,
                        'Pesquisar Acordo',
                        true);
  } else {
  
    if ($('ac16_sequencial').value != '') { 
    
      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+$('ac16_sequencial').value+
                 '&iTipoFiltro=4'+
                 '&funcao_js=parent.js_mostraacordo';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          'Pesquisar Acordo',
                          false);
     } else {
       $('ac16_sequencial').value   = ''; 
       $('ac16_resumoobjeto').value = '';
     }
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo(chave1,chave2,erro) {
 
  if (erro == true) {
   
    $('ac16_sequencial').value   = ''; 
    $('ac16_resumoobjeto').value = chave1;
    $('ac16_sequencial').focus(); 
    return false;
  } else {
  
    $('ac16_sequencial').value   = chave1;
    $('ac16_resumoobjeto').value = chave2;
    js_getAcordoProgramacaoFinanceira();
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo1(chave1,chave2) {

  $('ac16_sequencial').value    = chave1;
  $('ac16_resumoobjeto').value  = chave2;
  db_iframe_acordo.hide();
  js_getAcordoProgramacaoFinanceira();
}

/*
 * Pesquisa Acordo programação Financeira
 */
function js_getAcordoProgramacaoFinanceira() {

  var iAcordo = $('ac16_sequencial').value;
  if (iAcordo == '') {
    
    alert('Informe um acordo!');
    return false;
  }

  js_divCarregando('Aguarde, pesquisando...',"msgBoxAcordoProgramacaoFinanceira");
    
  var oParam     = new Object();
  oParam.exec    = 'getAcordoProgramacaFinanceira';
  oParam.acordo  = iAcordo;
    
  var oAjax      = new Ajax.Request ('con4_contratos.RPC.php',
                                    {
                                      method: 'post',  
                                      parameters:'json='+Object.toJSON(oParam),
                                      onComplete: js_retornoAcordoProgramacaoFinanceira 
                                    });
}

/*
 * Retorno Acordo programação Financeira
 */
function js_retornoAcordoProgramacaoFinanceira(oAjax) {

  js_removeObj("msgBoxAcordoProgramacaoFinanceira");
  
  var oRetorno    = eval("("+oAjax.responseText+")");
  var nValorTotal = new Number(oRetorno.valortotal.valoratual);
  if (nValorTotal == 0) {
    alert('Acordo não possui saldo disponível!');
    return false;
  }
  
  var iCodigo     = '';
  if (oRetorno.programacaofinanceira != null) {
    iCodigo = oRetorno.programacaofinanceira; 
  }

  js_visualizarProgramacao(iCodigo, nValorTotal);
}

/*
 * Visualiza registros programação financeira
 */
function js_visualizarProgramacao(iCodigo, nValorTotal) {
   
  oProgramacaoFinanceira = new DBViewProgramacaoFinanceira(iCodigo, "oProgramacaoFinanceira", null, document.width/1.2);
  oProgramacaoFinanceira.setValorTotal(nValorTotal);
  oProgramacaoFinanceira.setCallBack(function (iCodigo) {
    
   /*
    * Inclui registro na acordo programação financeira
    */
	  if (iCodigo == '') {
	    
	    alert('Código da programação financeira não informado!');
	    return false;
	  }
	    
	  var iAcordo = $('ac16_sequencial').value;
	  if (iAcordo == '') {
	    
	    alert('Informe um acordo!');
	    return false;
	  }
	    
	  js_divCarregando('Aguarde, incluindo programação...',"msgBoxAcordoProgramacaoFinanceira");
	    
	  var oParam     = new Object();
	  oParam.exec    = 'incluirAcordoProgramacaFinanceira';
	  oParam.codigo  = iCodigo;
	  oParam.acordo  = iAcordo;
	    
	  var oAjax      = new Ajax.Request ('con4_contratos.RPC.php',
	                                    {
	                                      method: 'post',  
	                                      parameters:'json='+Object.toJSON(oParam),
	                                      onComplete: function (oAjax) {
	                                        js_removeObj("msgBoxAcordoProgramacaoFinanceira");
	                                        var oRetorno = eval("("+oAjax.responseText+")");
	                                        if (oRetorno.status == 2) {
	                                          alert(oRetorno.message.urlDecode());
	                                          return false;
	                                        }
	                                      }
	                                    });
	  });
  oProgramacaoFinanceira.show();
}
</script>
</html>
