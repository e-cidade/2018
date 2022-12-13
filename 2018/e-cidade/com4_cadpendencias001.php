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

/**
 * Carregamos as libs necessárias
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
/**
* Intância da classe que tras as informações da validação do campos pc10_numero ($Ipc10_numero)
*/
$oDaoSolicita = db_utils::getDao('solicita');
$oDaoSolicita->rotulo->label();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<center>
	<fieldset style = "width:300px; margin-top:25px; margin-bottom:10px;">
		<legend><strong>Pendência da Solicitação de Compras</strong></legend>
		<table>
			<tr>
				<td><?php db_ancora('<strong>Solicitação: </strong>', 'js_pesquisaSolicitacao()', 1); ?></td>
				<td><?php db_input('pc10_numero', 8, $Ipc10_numero, true, "text", 1); ?></td>
			</tr>
		</table>
	</fieldset>
	<input type = "button" name = "btnEnviar" onclick = "js_efetuarPesquisa();" value = "Pesquisar" />
</center>
</body>
</html>

<script type = "text/javascript">

/**
 * Função que mostra a lookup de pesquisa de solicitações
 */
function js_pesquisaSolicitacao(){

  var sQuery     = "&nada=true"; // Esse parâmetro passado pr GET indica à lookup que o filtro não deve distinguir departamentos
  var sUrlLookUp = 'func_solicita.php?funcao_js=parent.js_mostraRetornoPesquisaSolicitacao|pc10_numero';
 	js_OpenJanelaIframe('top.corpo',
 	  	                'db_iframe_solicita',
 	  	                sUrlLookUp + sQuery,
 	  	                'Pesquisa de Solicitações',
 	  	                true);
}

/**
 * Função que faz o retorno da pesquisa de solicitações preenchendo o campo pc10_numero
 */
function js_mostraRetornoPesquisaSolicitacao(sRetorno){

  $('pc10_numero').value = sRetorno;
  db_iframe_solicita.hide();
}

/**
 * Função que valida se o formulário é válido e envia os dados até a pesquisa
 */
function js_efetuarPesquisa(){

  if ($('pc10_numero').value.trim() !== ""){
    
    var sUrlPesquisa = 'com4_cadpendencias002.php?pc10_numero=' + $F('pc10_numero');
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_cadpendencia',
                        sUrlPesquisa,
                        'Pendência de Solicitações de Compras',
                        true);
    return true;
  }
  alert("O campo Solicitação deve ser informado para efetuar a pesquisa.");
  return false;
}

</script>