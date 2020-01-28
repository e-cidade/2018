<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("classes/db_habitformaavaliacao_classe.php");
require_once("classes/db_habitformaavaliacaousuario_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clhabitformaavaliacao        = new cl_habitformaavaliacao;
$clhabitformaavaliacaousuario = new cl_habitformaavaliacaousuario;
$cldb_usuarios                = new cl_db_usuarios;

$db_opcao = 2;
$db_botao = false;
$sqlerro  = false;

if (isset($oPost->incluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clhabitformaavaliacaousuario->ht08_habitformaavaliacao = $oPost->ht08_habitformaavaliacao;
    $clhabitformaavaliacaousuario->ht08_id_usuario          = $oPost->id_usuario;
    $clhabitformaavaliacaousuario->incluir(null);
    $erro_msg = $clhabitformaavaliacaousuario->erro_msg;
    if ($clhabitformaavaliacaousuario->erro_status == 0) {
    	$sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if(isset($oPost->alterar)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clhabitformaavaliacaousuario->ht08_sequencial          = $oPost->ht08_sequencial;
    $clhabitformaavaliacaousuario->ht08_habitformaavaliacao = $oPost->ht08_habitformaavaliacao;
    $clhabitformaavaliacaousuario->ht08_id_usuario          = $oPost->id_usuario;
    $clhabitformaavaliacaousuario->alterar($clhabitformaavaliacaousuario->ht08_sequencial);
    $erro_msg = $clhabitformaavaliacaousuario->erro_msg;
    if ($clhabitformaavaliacaousuario->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->excluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clhabitformaavaliacaousuario->ht08_sequencial = $oPost->ht08_sequencial;
    $clhabitformaavaliacaousuario->excluir($clhabitformaavaliacaousuario->ht08_sequencial);
    $erro_msg = $clhabitformaavaliacaousuario->erro_msg;
    if ($clhabitformaavaliacaousuario->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if(isset($oPost->opcao)) {
	
	$sSqlFormaAvaliacaoUsuario = $clhabitformaavaliacaousuario->sql_query($oPost->ht08_sequencial);
	$rsFormaAvaliacaoUsuario   = $clhabitformaavaliacaousuario->sql_record($sSqlFormaAvaliacaoUsuario);
	if ($rsFormaAvaliacaoUsuario != false && $clhabitformaavaliacaousuario->numrows > 0) {
		db_fieldsmemory($rsFormaAvaliacaoUsuario,0);
	}
}

if (isset($oGet->ht07_sequencial)) {
	$ht08_habitformaavaliacao = $oGet->ht07_sequencial;
} else {
	$ht08_habitformaavaliacao = $oPost->ht08_habitformaavaliacao;
}

$sWhere             = "ht07_sequencial = {$ht08_habitformaavaliacao}";
$sSqlFormaAvaliacao = $clhabitformaavaliacao->sql_query(null,"*",null,$sWhere);
$rsFormaAvaliacao   = $clhabitformaavaliacao->sql_record($sSqlFormaAvaliacao);
if ($clhabitformaavaliacao->numrows > 0) {
	
	db_fieldsmemory($rsFormaAvaliacao,0);
	$ht08_habitformaavaliacao = $ht07_sequencial;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 100px;
  white-space: nowrap
}

#ht07_descricao {
  width: 80%;
}

#nome {
  width: 80%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmhabformaavaliacaousuario.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
	
  db_msgbox($erro_msg);
  if ($clhabitformaavaliacaousuario->erro_campo != "") {
  	
    echo "<script> document.form1.".$clhabitformaavaliacaousuario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clhabitformaavaliacaousuario->erro_campo.".focus();</script>";
  }
}
?>