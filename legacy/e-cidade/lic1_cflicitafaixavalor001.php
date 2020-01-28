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
require_once("classes/db_cflicitavalores_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET); 

$clcflicitavalores = new cl_cflicitavalores();
$db_opcao          = 22;
$db_botao          = false;

if ( isset($oGet->l37_cflicita) && trim($oGet->l37_cflicita) != '' ) {
	$l37_cflicita = $oGet->l37_cflicita; 
} else if ( isset($oPost->l37_cflicita) && trim($oPost->l37_cflicita) != '' ) {
  $l37_cflicita = $oPost->l37_cflicita;
}

if (isset($oPost->l40_sequencial) && !empty($oPost->l40_sequencial)) {
	$l40_sequencial = $oPost->l40_sequencial;
} else {
	$l40_sequencial = "";
}

if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
	
  $lSqlErro   = false;
  $dtDataIni  = implode("-",array_reverse(explode("/",$oPost->l40_datainicial)));
  $dtDataFim  = implode("-",array_reverse(explode("/",$oPost->l40_datafinal)));
}

if (isset($oPost->incluir)) {
	
  $sWhere               = " l40_codfclicita = {$l37_cflicita}                               ";
  $sWhere              .= " and (l40_datainicial::date,l40_datafinal::date)                 ";
  $sWhere              .= "      overlaps ('{$dtDataIni}'::date,'{$dtDataFim}'::date)       "; 
    
  $sSqlCfLicitaValores  = $clcflicitavalores->sql_query(null,"cflicitavalores.*",null,$sWhere);
  $rsSqlCfLicitaValores = $clcflicitavalores->sql_record($sSqlCfLicitaValores);
  $iNumRows             = $clcflicitavalores->numrows;
  if ($iNumRows > 0) {
      
    $lSqlErro     = true;
    $sMensagem    = "Usuário: \\n\\n";
    $sMensagem   .= "Valor informado já cadastrado para esse período! \\n\\n";
  }
	
  if (!$lSqlErro) {
    
    db_inicio_transacao();
    
    $clcflicitavalores->l40_codfclicita = $l37_cflicita;
    $clcflicitavalores->l40_valorminimo = $oPost->l40_valorminimo;
    $clcflicitavalores->l40_valormaximo = $oPost->l40_valormaximo;
    $clcflicitavalores->l40_datainicial = $dtDataIni;
    $clcflicitavalores->l40_datafinal   = $dtDataFim;
    $clcflicitavalores->incluir(null);
    
    $sMensagem  = $clcflicitavalores->erro_msg;
    if ($clcflicitavalores->erro_status == 0) {
    	$lSqlErro = true; 
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->alterar)) {
	
  $sWhere               = " l40_codfclicita = {$l37_cflicita}                               ";
  $sWhere              .= " and (l40_datainicial::date,l40_datafinal::date)                 ";
  $sWhere              .= "      overlaps ('{$dtDataIni}'::date,'{$dtDataFim}'::date)       "; 
    
  $sSqlCfLicitaValores  = $clcflicitavalores->sql_query(null,"cflicitavalores.*",null,$sWhere);
  $rsSqlCfLicitaValores = $clcflicitavalores->sql_record($sSqlCfLicitaValores);
  $iNumRows             = $clcflicitavalores->numrows;
  if ($iNumRows > 0) {
      
    $lSqlErro     = true;
    $sMensagem    = "Usuário: \\n\\n";
    $sMensagem   .= "Valor informado já cadastrado para esse período! \\n\\n";
  }
	
  if (!$lSqlErro) {
  	
    db_inicio_transacao();

    $clcflicitavalores->l40_sequencial  = $oPost->l40_sequencial;
    $clcflicitavalores->l40_codfclicita = $l37_cflicita;
    $clcflicitavalores->l40_valorminimo = $oPost->l40_valorminimo;
    $clcflicitavalores->l40_valormaximo = $oPost->l40_valormaximo;
    $clcflicitavalores->l40_datainicial = $dtDataIni;
    $clcflicitavalores->l40_datafinal   = $dtDataFim;
    $clcflicitavalores->alterar($clcflicitavalores->l40_sequencial);
    
    $sMensagem  = $clcflicitavalores->erro_msg;
    if ($clcflicitavalores->erro_status == 0) {
      $lSqlErro = true; 
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->excluir)) {
	
  if (!$lSqlErro) {
  	
    db_inicio_transacao();
  	
    $clcflicitavalores->l40_sequencial  = $oPost->l40_sequencial;
    $clcflicitavalores->excluir($clcflicitavalores->l40_sequencial);
    
    $sMensagem  = $clcflicitavalores->erro_msg;
    if ($clcflicitavalores->erro_status == 0) {
      $lSqlErro = true; 
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->opcao)) {
  
	if (isset($oPost->l40_sequencial) && !empty($oPost->l40_sequencial)) {
		
	  $sSqlCfLicitaValores  = $clcflicitavalores->sql_query($oPost->l40_sequencial,"*",null,"");
	  $rsSqlCfLicitaValores = $clcflicitavalores->sql_record($sSqlCfLicitaValores);
	  $iNumRows             = $clcflicitavalores->numrows;
	  if ($iNumRows > 0) {
	    db_fieldsmemory($rsSqlCfLicitaValores,0);	
	  }
	}
	
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmcflicitavalores.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
	
	if (isset($sMensagem)) {
		
    db_msgbox($sMensagem);
	  if ($clcflicitavalores->erro_campo != "") {
        echo "<script> document.form1.".$clcflicitavalores->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcflicitavalores->erro_campo.".focus();</script>";
    }
	}
}
?>