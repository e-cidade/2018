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

/**
 * Carregamos um objeto com as informa��es passadas por GET.
 * Ap�s isso analisamos se foi passado uma flag de erro e em caso positivo informamos isso ao usu�rio. 
 */
$oGet = db_utils::postMemory($_GET);
if (isset($oGet->lErro)) {
	db_msgbox($_SESSION['sMsgConflitoTransacaoLancamento']);
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <center>
   	<form name="form1" action="con1_importartransacao002.php" method="post" 
   	      enctype="multipart/form-data" onsubmit="return js_validaFormulario();">
      <fieldset style="margin-top:25px;margin-bottom:10px;width:580px;padding-top:10px;padding-bottom:15px;">
      	<legend><strong>Importar Transa��es:</strong></legend>
      		<?
		        db_input("arquivoTransacoes",50,'',true,"file",4);
          ?>
      </fieldset>
      <input type="submit" value="Importar Transa��es" id="btnImportarTransacao">
    </form>
  </center>
</body>
</html>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
<script>

/**
 * Fun��o que chama ao fonte que importa as transa��es
 */
function js_validaFormulario() {

  if ($F('arquivoTransacoes') == "") {

    alert("Deve ser informado o arquivo que cont�m as informa��es de importa��o das transa��es.");
    return false;
  }

  if (!confirm("A importa��o do arquivo poder� deixar o sistema um pouco lento. Tem certeza que deseja executar esta importa��o agora?")) {
    return false;
  }
  $('btnImportarTransacao').disabled = true;
  
  return true;
}

/**
 * Fun��o que exibe o relat�rio se vier informado
 */
<?php 
if (isset($oGet->lRelatorio)) {
	
	echo "var oJanela = window.open('{$oGet->sUrlRelatorio}', '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
	echo "oJanela.moveTo(0,0)";
	
} ?>
</script>