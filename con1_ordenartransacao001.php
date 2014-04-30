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

$oRotulos = new rotulocampo;
$oRotulos->label('c53_coddoc');
$oRotulos->label('c53_descr');
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" >
	<center>
  	<fieldset style="margin-top:25px; margin-bottom:10px; width:480px;">
  		<legend><strong>Ordenar transações do documento:</strong></legend>
  		<table>
  			<tr>
  				<td><?php db_ancora('<strong>Documento: </strong>', 'js_pesquisaDocumento(true);', 1)?></td>
  				<td><? db_input('c53_coddoc', 10, $Ic53_coddoc, true, 'text', 1, " onchange='js_pesquisaDocumento(false);'") ?></td>
  				<td><? db_input('c53_descr', 40, $Ic53_descr, true, 'text', 3) ?></td>
  			</tr>
  		</table>
  	</fieldset>
  	<input type="button" value="Ordenar Transações" id="btnOrdenar" onclick="js_abreTelaOrdenacao();" />
	</center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

function js_pesquisaDocumento(mostra) {

  if (mostra) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_documento', 
                        'func_conhistdoc.php?funcao_js=parent.js_mostraDocumento|c53_coddoc|c53_descr', 
                        'Pesquisa Documentos', true);
  } else {
		js_OpenJanelaIframe('top.corpo', 'db_iframe_documento',
				                'func_conhistdoc.php?funcao_js=parent.js_mostraDocumento1&pesquisa_chave='+$F('c53_coddoc'),
				                'Pesquisa Documentos', false);
  }
}

function js_mostraDocumento(iCodigoDocumento, sDescricaoDocumento) {

  $('c53_coddoc').value = iCodigoDocumento;
  $('c53_descr').value  = sDescricaoDocumento;
  db_iframe_documento.hide();
}

function js_mostraDocumento1(sDescricaoDocumento, lErro) {

  $('c53_descr').value = sDescricaoDocumento;
  if (lErro) {

    $('c53_coddoc').value = '';
    $('c53_coddoc').focus();
  }
}

function js_abreTelaOrdenacao() {

	if ($F('c53_coddoc').trim() == "") {

		alert('Deve ser selecionado um documento para que as suas transações sejam ordenadas.');
		return false;
	}

	js_OpenJanelaIframe('top.corpo', 'db_iframe_ordenatransacoes',
	  	                'con1_ordenartransacao002.php?c53_coddoc='+$F('c53_coddoc'),
	  	                'Ordenar Transações', true);
}

</script>