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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");

$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");

$oGet = db_utils::postMemory($_GET);
?>


<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  // Includes padrão
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px'>
<center>
  <div id = 'divContainer' style="width: 500px;">
    <form id='formPadrao' action="">
      <fieldset>
        <legend>Impressão do Acordo</legend>
        <table>
          <tr>
            <td nowrap title="<?php echo $Tac16_sequencial; ?>" width="130" id='ctnAnconra'>
              <?php db_ancora($Lac16_sequencial, "js_acordo(true);",1);?>
            </td>
            <td nowrap="nowrap" colspan="2">  
              <?php
                db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1, "onchange='js_acordo(false);'");
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                db_input('ac16_origem', 10, '', true, 'hidden', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td class='bold'>Documento:</td>
            <td>
              <select id='documento' style="width: 100%;" disabled="disabled" >
                <option selected="selected" value=''>Selecione um acordo</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <?php db_input('iTipoDocumento', 10, '', true, 'hidden', 3);?> 
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id='imprimir' value='Imprimir' name='botao' onclick="js_imprime();" disabled="disabled"/>
    </form>
  </div>
</center>
<?
if (!isset($oGet->iContrato)) {
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}?>
  
</body>
</html>
<script type="text/javascript">

  var sRPC = "aco4_acordo.RPC.php";
  var oGet = js_urlToObject();

  function js_acordo(mostra) {

    var sUrl = 'func_acordoinstit.php';

    if (mostra){

      sUrl += '?funcao_js=parent.js_mostraAcordo1|ac16_sequencial|ac16_resumoobjeto|ac16_origem';
      js_OpenJanelaIframe('','db_iframe_acordo', sUrl, 'Pesquisa Acordo', true);
    } else {

      if($F('ac16_sequencial').trim() != '') { 
        
        sUrl += '?pesquisa_chave='+$F('ac16_sequencial')+'&funcao_js=parent.js_mostraAcordo&descricao=true';
        js_OpenJanelaIframe('','db_iframe_depart', sUrl, 'Pesquisa Acordo', false);
      }else{
        $('ac16_resumoobjeto').value = ''; 
      }
    }
  }
  
  function js_mostraAcordo(chave, descricao, erro, origem) {

    $('ac16_resumoobjeto').value = descricao;
    $('ac16_origem').value       = origem;
    if (erro) {
      
      $('ac16_sequencial').focus(); 
      $('ac16_sequencial').value = ''; 
      $('ac16_origem').value     = '';
    }

    js_buscaDocumentos();
  }
  
  function js_mostraAcordo1(codigo, resumo, origem) {
    
    $('ac16_sequencial').value   = codigo;
    $('ac16_resumoobjeto').value = resumo;
    $('ac16_origem').value       = origem;
    db_iframe_acordo.hide();

    js_buscaDocumentos();
  }

  function js_buscaDocumentos() {

    var oParametro     = new Object();
  	oParametro.exec    = 'buscaDocumentoTemplate';
  	oParametro.iOrigem = $F('ac16_origem');

  	js_divCarregando("Aguarde, carregando os documentos.", "msgBox");
  	new Ajax.Request(sRPC,
  	                 {method:     'post',
  	                  parameters: 'json='+Object.toJSON(oParametro),
  	                  onComplete: js_retornaDocumentos
  	                 }
  	                ); 
  }


  function js_retornaDocumentos(oAjax) {

  	js_removeObj("msgBox");
  	var oRetorno = eval('('+oAjax.responseText+')');

  	$('documento').innerHTML = '';
  	oRetorno.aDocumentoRetorno.each(function(oDocumento) {

  	  var oOption       = document.createElement('option');
  	  oOption.value     = oDocumento.iCodigo;
  	  oOption.innerHTML = oDocumento.sDescricao.urlDecode();
  	  $('documento').appendChild(oOption);
  	});
  	$('iTipoDocumento').value = oRetorno.iTipoDocumento;
  	$('documento').removeAttribute("disabled");
  	$('imprimir').removeAttribute("disabled");
  }

  /**
   * Imprime o documento
   */
  function js_imprime() {

    if ($F('documento') == '') {

      alert('Selecione um modelo de documento.');
      return false;
    }

    var sLocation  = "aco2_impressaoacordo002.php?";
  	sLocation     += "iAcordo="+$F('ac16_sequencial');
  	sLocation     += "&iOrigem="+$F('ac16_origem');
  	sLocation     += "&iTipoDocumento="+$F('iTipoDocumento');
  	sLocation     += "&iDocumento="+$F('documento');
  	jan            = window.open(sLocation, '', 
  	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
   }

  if (oGet.iContrato && oGet.iContrato != '') {
    
    $('ac16_sequencial').value = oGet.iContrato;
    $('ac16_sequencial').setAttribute("readonly", "readonly");
    $('ac16_sequencial').addClassName('readonly');
    $('ctnAnconra').innerHTML = '<b>Código Acordo<b>';
    
    js_acordo(false); 
  }
</script>
