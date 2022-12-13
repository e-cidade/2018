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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
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
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div style="margin-top: 40px;"></div>
  <center>
    <fieldset style="width:308px; padding-top:10px; padding-bottom:3px;">
    	<legend><strong>Exportar Transações:</strong></legend>
    	<form name="form1">
    		<input type="button" value="Baixar arquivo de exportação" onclick="js_exportarTransacoes();" />
    	</form>
    </fieldset>
  </center>
</body>
</html>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
<script>

/**
 * Função que chama ao fonte que exporta as tranações
 */
function js_exportarTransacoes() {

  var oParam = new Object();
      oParam.exec = 'exportarTransacoes';
  var oAjax = new Ajax.Request('con4_transacoes.RPC.php',
                               {method: 'post', 
                                parameters:'json='+Object.toJSON(oParam), 
                                onComplete:js_retornoExportarTransacoes});

  js_divCarregando('Processando Operação', 'msgBox');
}

/**
 * Função de retorno da função que chama o fonte que exporta as transações
 */
function js_retornoExportarTransacoes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    var PathArquivo = oRetorno.pathArquivo + "# Download do Arquivo " + oRetorno.pathArquivo;
    js_montarlista(PathArquivo, 'form1');
  } else {

    alert(oRetorno.message);
    return false;
  }
}

</script>