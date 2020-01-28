<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_mensageriaacordo_classe.php");

require_once("classes/db_mensageriaacordodb_usuario_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);


$clrotulo = new rotulocampo;
$clrotulo->label("ac51_assunto");
$clrotulo->label("ac51_mensagem");
$clrotulo->label("ac52_dias");

$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, 
                strings.js, 
                prototype.js, 
                datagrid.widget.js,
                widgets/DBLancador.widget.js,
                widgets/DBToogle.widget.js,
                estilos.css, 
                grid.style.css
               ");
?>
<style>

 #ac51_mensagem{
   width: 100%;
 }

td {
  white-space: nowrap;
}

fieldset table td:first-child {
  white-space: nowrap;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>

      <fieldset style="margin-top: 30px; width: 400px;">

        <legend><strong>Ficha Financeira</strong></legend>

	      <table align="left" border="0">
	      
	        <tr>
	          <td nowrap="nowrap" width="95px">
	            <strong>Data Inicial:</strong>
	          </td>
	          
	          <td nowrap="nowrap" align="left">
	            <?php db_inputdata('dtInicial','','','',true,'text',1,"");?>
	          </td>
	        </tr>
	      
	        <tr>
	          <td nowrap="nowrap">
	            <strong>Data Final:</strong>
	          </td>
	          
	          <td nowrap="nowrap" align="left">
	            <?php db_inputdata('dtFinal','','','',true,'text',1,"");?>
	          </td>
	        </tr>
	        
      <tr>
        <td>
          <?php
            db_ancora("<strong>Material:</strong>", "js_pesquisaMaterial(true);", 1);
          ?>
        </td>
        <td>
          <?php 
            db_input('m60_codmater', 10, $Im60_codmater, true, 'text', 1, "onchange='js_pesquisaMaterial(false);'");
            db_input('m60_descr', 40, $Im60_descr, true, 'text', 3);
          ?>
        </td>
      </tr>  	      

		      
		  <tr>
		    <td colspan="2">
		      <div id='ctnAlmoxarifado'></div>
		    </td>
		  </tr>
		      
	      </table>

      </fieldset>


      <div style="margin-top: 10px; text-align: center;">
        <input id="emitir" name="emitir" type="button" value="Emitir" onclick="js_emite();" >
      </div>
</center>

<?PHP db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>

<script>

const CAMINHO_MENSAGENS = "patrimonial.material.mat2_fichafinanceira001.";
var   sUrl               = "ac4_mensageriaacordo.RPC.php";
var   aDadosLinhaDia     = [];

var oLancadorAlmoxarifado = new DBLancador('LancadorAlmoxarifado');
    oLancadorAlmoxarifado.setLabelAncora("Almoxarifado:");
    oLancadorAlmoxarifado.setTextoFieldset("Lista de Almoxarifados");
    oLancadorAlmoxarifado.setTituloJanela("Pesquisar Almoxarifados");
    oLancadorAlmoxarifado.setNomeInstancia("oLancadorAlmoxarifado");
    oLancadorAlmoxarifado.setParametrosPesquisa("func_db_almoxFixaFinanceira.php", ["m91_depto", "descrdepto"]);
    oLancadorAlmoxarifado.setGridHeight(150);
    oLancadorAlmoxarifado.show($("ctnAlmoxarifado"));



function getAlmoxarifados(){

  var aAlmoxarifado = [];
  
  oLancadorAlmoxarifado.getRegistros().each( function( oDados, iIndice){

    aAlmoxarifado.push( oDados.sCodigo );
  });

  return aAlmoxarifado;
}

function js_emite() {

  var dtInicial     = js_formatar($F("dtInicial"), "d");
  var dtFinal       = js_formatar($F("dtFinal"), "d");
  var iMaterial     = $F("m60_codmater");
  var aAlmoxarifado = getAlmoxarifados();
  var sFonte        = "mat2_fichafinanceira002.php";
/*
  if (dtInicial == '' || dtFinal == '') {

    alert(_M(CAMINHO_MENSAGENS + "periodo_nao_informado" ));
    return false;
  }
*/
  
  if (iMaterial == '') {
    
    alert(_M(CAMINHO_MENSAGENS + "material_nulo" ));
    return false;
  }

  var sQuery  = "?dtInicial="     + dtInicial;
      sQuery += "&dtFinal="       + dtFinal;
      sQuery += "&iMaterial="     + iMaterial;
      sQuery += "&aAlmoxarifado=" + aAlmoxarifado;
      
	    jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	    jan.moveTo(0,0);
}


/**
 * Funções de pesquisa do Material
 */
function js_pesquisaMaterial(lMostra) {

  var sUrlOpen = "func_matmater.php?pesquisa_chave="+$F('m60_codmater')+"&funcao_js=parent.js_completaMaterial";
  if (lMostra) {
    sUrlOpen = "func_matmater.php?funcao_js=parent.js_preencheMaterial|m60_codmater|m60_descr";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_matmater', sUrlOpen, 'Pesquisa Material', lMostra);
}
function js_completaMaterial(sDescricao, lErro) {
  
  $("m60_descr").setValue(sDescricao);
  if (lErro) {
    $("m60_codmater").setValue('');
  }
}
function js_preencheMaterial(iCodigo, sDescricao) {

  $('m60_codmater').setValue(iCodigo);
  $('m60_descr').setValue(sDescricao);
  db_iframe_matmater.hide();
}
</script>
</html>
