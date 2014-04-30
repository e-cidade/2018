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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("ov02_nome");
$oRotuloCampos->label("as04_sequencial");
$oRotuloCampos->label("as15_codigofamiliarcadastrounico");
$oRotuloCampos->label("as02_nis");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <center>
    <form name="form1" method="post" action="">
    <fieldset style="width:300px;">
      <legend><b>Consulta Família</b></legend>
      <table>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
            <?php
              db_ancora("<b>Responsável da Família: </b>", "js_pesquisaCidadaoFamilia(true, false);", 1);
            ?>
          </td>
          <td nowrap="nowrap">
          	<?php
            	db_input("as04_sequencial", 10, $Ias04_sequencial, true, "text", 1, "onchange='js_pesquisaCidadaoFamilia(false, false);'");
            	db_input("as15_codigofamiliarcadastrounico", 10, $Ias15_codigofamiliarcadastrounico, true,
            					 "hidden", 1);
              db_input("ov02_nome", 40, $Iov02_nome, true, "text", 3);
				    ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
            <?php
              db_ancora("NIS do Responsável da Família:", "js_pesquisaCidadaoFamilia(true, true);", 1);
            ?>
          </td>
          <td>
          <?php
            db_input("as02_nis", 10, $Ias02_nis, true, "text", 1, "onchange='js_pesquisaCidadaoFamilia(false, true);'");
          ?>
          </td>
        </tr>
      </table>
     </fieldset>
     <input type="button" id="btnConsultar" value="Consultar" onclick="js_consultar();"
            style="margin-top: 10px;" />
     </form>
  </center>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function js_pesquisaCidadaoFamilia(lMostra, lNis) {

  var sUrl = 'func_cidadaofamiliacompleto.php?lSomenteResponsavel&';

  if (lMostra == true) {

    sUrl += 'funcao_js=parent.js_mostracidadaofamilia1|as04_sequencial|ov02_nome|as15_codigofamiliarcadastrounico|as02_nis';
  	js_OpenJanelaIframe('', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisar Código da Família', true);
  } else {

    sUrl += 'funcao_js=parent.js_mostracidadaofamilia';
    sUrl += '&sTipoRetorno=relatorio';
    sUrl += '&sNIS='+lNis;
    sUrl += '&lFamilia';

    if ($F('as04_sequencial') == '' && $F('as02_nis') == "") {

      $('as15_codigofamiliarcadastrounico').value = '';
      return false;
    }

  	if (!lNis && $F('as04_sequencial') != '') {
  	  sUrl += '&pesquisa_chave='+$F('as04_sequencial');
  	} else if (lNis && $F('as02_nis') != "") {
  	  sUrl += '&pesquisa_chave='+$F('as02_nis');
    }

  	js_OpenJanelaIframe('', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisar Código da Família', false);
  }
}

function js_mostracidadaofamilia(iCodigoFamilia, erro, sNome, iSequencial, iNis) {

  $('as15_codigofamiliarcadastrounico').value = iCodigoFamilia;
  $('ov02_nome').value                        = sNome;
  $('as02_nis').value                         = iNis;

  if (arguments[0] == true) {

  	$('as04_sequencial').value                     = "";
  	$('ov02_nome').value                           = arguments[1];
  	$('as02_nis').value                            = "";
  	$('as15_codigofamiliarcadastrounico').values   = "";
  	$('as15_codigofamiliarcadastrounico').focus();
  }
}

function js_mostracidadaofamilia1(iSequencial, sNome, iCodigoFamilia, iNis) {

	$('as04_sequencial').value                  = iSequencial;
	$('as15_codigofamiliarcadastrounico').value = iCodigoFamilia;
	$('ov02_nome').value                        = sNome;
	$('as02_nis').value                         = iNis;
	db_iframe_cidadaofamilia.hide();
}

function js_consultar(){

  if ($('as04_sequencial').value.trim() !== ""){

    var sUrlPesquisa = 'soc3_consultafamilia003.php?codigoFamilia=' + $F('as04_sequencial');
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_consulta_familia',
                        sUrlPesquisa,
                        'Consulta Família',
                        true);
    return true;
  }
  alert("Selecione uma Família para efetuar a pesquisa.");
  return false;
}
</script>