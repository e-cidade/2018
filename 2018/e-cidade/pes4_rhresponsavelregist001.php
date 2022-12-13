<?
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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhresponsavel_classe.php");
require_once("classes/db_rhresponsavelregist_classe.php");
require_once("classes/db_cgm_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrhresponsavel       = new cl_rhresponsavel();
$clrhresponsavelregist = new cl_rhresponsavelregist();
$clcgm                 = new cl_cgm();

$db_opcao              = 22;
$db_botao              = false;
$lSqlErro              = false;

$sWhereRhResponsavel   = "rhresponsavel.rh107_sequencial = {$oGet->rh107_sequencial}";
$sCamposRhResponsavel  = "rh107_sequencial, z01_nome";
$sSqlRhResponsavel     = $clrhresponsavel->sql_query(null, $sCamposRhResponsavel, null, $sWhereRhResponsavel);
$rsSqlRhResponsavel    = $clrhresponsavel->sql_record($sSqlRhResponsavel);
if ($clrhresponsavel->numrows > 0) {
    
  $oRhResponsavel      = db_utils::fieldsMemory($rsSqlRhResponsavel, 0);
  $rh108_rhresponsavel = $oRhResponsavel->rh107_sequencial;
  $z01_nome            = $oRhResponsavel->z01_nome;
}

/**
 * Valida se já existe servidor ativo cadastrado para um responsável.
 */
if (isset($oPost->incluir) || isset($oPost->alterar)) {
	
  $sWhereRhResponsavelRegist   = "rhresponsavelregist.rh108_regist = {$oPost->rh108_regist} ";
  $sWhereRhResponsavelRegist  .= "and rhresponsavelregist.rh108_status is true              ";
  $sCamposRhResponsavelRegist  = "rhresponsavel.rh107_numcgm, a.z01_nome";
  $sSqlRhResponsavelRegist     = $clrhresponsavelregist->sql_query(null, $sCamposRhResponsavelRegist, 
                                                                   null, $sWhereRhResponsavelRegist);
  $rsSqlRhResponsavelRegist    = $clrhresponsavelregist->sql_record($sSqlRhResponsavelRegist);
  if ($clrhresponsavelregist->numrows > 0) {
    
    $oRhResponsavelRegist = db_utils::fieldsMemory($rsSqlRhResponsavelRegist, 0);
    
    $lSqlErro     = true;
    $sMsgUsuario  = "Usuário: \\n\\n Servidor {$oPost->rh108_regist} já está vinculado ativamente \\n para o"; 
    $sMsgUsuario .= " responsável {$oRhResponsavelRegist->rh107_numcgm} - {$oRhResponsavelRegist->z01_nome} ";
  }
}

/**
 * Verifica se é inclusão/alteração/exclusão de um registro.
 */
if (isset($oPost->incluir)) {
  
  if (!$lSqlErro) {
    
    db_inicio_transacao();
    
    $clrhresponsavelregist->rh108_rhresponsavel = $oPost->rh108_rhresponsavel;
    $clrhresponsavelregist->rh108_regist        = $oPost->rh108_regist;
    $clrhresponsavelregist->rh108_status        = $oPost->rh108_status;
    $clrhresponsavelregist->incluir(null);
    $sMsgUsuario = $clrhresponsavelregist->erro_msg;
    if ($clrhresponsavelregist->erro_status == 0) {
    	$lSqlErro = true;
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->alterar)) {
  
  if (!$lSqlErro) {
    
    db_inicio_transacao();
    
    $clrhresponsavelregist->rh108_sequencial    = $oPost->rh108_sequencial;
    $clrhresponsavelregist->rh108_rhresponsavel = $oPost->rh108_rhresponsavel;
    $clrhresponsavelregist->rh108_regist        = $oPost->rh108_regist;
    $clrhresponsavelregist->rh108_status        = $oPost->rh108_status;
    $clrhresponsavelregist->alterar($clrhresponsavelregist->rh108_sequencial);
    $sMsgUsuario = $clrhresponsavelregist->erro_msg;
    if ($clrhresponsavelregist->erro_status == 0) {
      $lSqlErro = true;
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->excluir)) {
  
  if (!$lSqlErro) {
    
    db_inicio_transacao();
    
    $clrhresponsavelregist->rh108_sequencial = $oPost->rh108_sequencial;
    $clrhresponsavelregist->excluir($clrhresponsavelregist->rh108_sequencial);
    $sMsgUsuario = $clrhresponsavelregist->erro_msg;
    if ($clrhresponsavelregist->erro_status == 0) {
      $lSqlErro = true;
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($opcao)) {
  
	/**
	 * Pesquisa dados já cadastrados para a alteração/exclusão.
	 */
	$sWhereRhResponsavelRegist   = "rhresponsavelregist.rh108_sequencial = {$oPost->rh108_sequencial}";
	$sCamposRhResponsavelRegist  = "rh108_sequencial, rh108_regist,                ";
  $sCamposRhResponsavelRegist .= "cgm.z01_nome as z01_nome_servidor, rh108_status";
	$sSqlRhResponsavelRegist     = $clrhresponsavelregist->sql_query(null, $sCamposRhResponsavelRegist, 
	                                                                 null, $sWhereRhResponsavelRegist);
	$rsSqlRhResponsavelRegist    = $clrhresponsavelregist->sql_record($sSqlRhResponsavelRegist);
	if ($clrhresponsavelregist->numrows > 0) {
	    
	  $oRhResponsavelRegist = db_utils::fieldsMemory($rsSqlRhResponsavelRegist, 0);
    $rh108_sequencial     = $oRhResponsavelRegist->rh108_sequencial;
    $rh108_regist         = $oRhResponsavelRegist->rh108_regist;
    $z01_nome_servidor    = $oRhResponsavelRegist->z01_nome_servidor;
    $rh108_status         = $oRhResponsavelRegist->rh108_status;
	}
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
fieldset table td:first-child {

  width: 80px;
  white-space: nowrap;
}

td {
  white-space: nowrap;
}

#z01_nome, #rh108_status_select_descr {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table border="0" align="center" cellspacing="0" cellpadding="0" width="530">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmrhresponsavelregist.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
<?
if (isset($oPost->incluir) || isset($oPost->alterar) || isset($oPost->excluir)) {
  
  db_msgbox($sMsgUsuario);
  if ($clrhresponsavelregist->erro_campo != "") {
    
    echo "<script> document.form1.".$clrhresponsavelregist->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clrhresponsavelregist->erro_campo.".focus();</script>";
  }
}
?>
</html>