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
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_pctipocompravalores_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost                 = db_utils::postMemory($_POST);
$oGet                  = db_utils::postMemory($_GET); 

$clpctipocompra        = new cl_pctipocompra();
$clpctipocompravalores = new cl_pctipocompravalores();

$db_opcao              = 22;
$db_botao              = false;

if ( isset($oGet->pc50_codcom) && trim($oGet->pc50_codcom) != '' ) {
	$pc50_codcom = $oGet->pc50_codcom; 
} else if ( isset($oPost->pc50_codcom) && trim($oPost->pc50_codcom) != '' ) {
  $pc50_codcom = $oPost->pc50_codcom;
}

if (isset($oPost->pc85_sequencial) && !empty($oPost->pc85_sequencial)) {
	$pc85_sequencial = $oPost->pc85_sequencial;
} else {
	$pc85_sequencial = "";
}

if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
	
  $lSqlErro   = false;
  $dtDataIni  = implode("-",array_reverse(explode("/",$oPost->pc85_datainicial)));
  $dtDataFim  = implode("-",array_reverse(explode("/",$oPost->pc85_datafinal)));
}

if (isset($oPost->incluir)) {
	
  $sWhere                   = " pc85_codtipocompra = {$pc50_codcom}                                      ";
  $sWhere                  .= " and (pc85_datainicial::date,pc85_datafinal::date)                        ";
  $sWhere                  .= "      overlaps ('{$dtDataIni}'::date,'{$dtDataFim}'::date)                "; 
    
  $sSqlPcTipoCompraValores  = $clpctipocompravalores->sql_query(null,"pctipocompravalores.*",null,$sWhere);
  $rsSqlPcTipoCompraValores = $clpctipocompravalores->sql_record($sSqlPcTipoCompraValores);
  $iNumRows                 = $clpctipocompravalores->numrows;
  if ($iNumRows > 0) {
      
    $lSqlErro     = true;
    $sMensagem    = "Usuário: \\n\\n";
    $sMensagem   .= "Valor informado já cadastrado para esse período! \\n\\n";
  }
	
  if (!$lSqlErro) {
    
    db_inicio_transacao();
    
    $clpctipocompravalores->pc85_codtipocompra = $pc50_codcom;
    $clpctipocompravalores->pc85_valorminimo   = $oPost->pc85_valorminimo;
    $clpctipocompravalores->pc85_valormaximo   = $oPost->pc85_valormaximo;
    $clpctipocompravalores->pc85_datainicial   = $dtDataIni;
    $clpctipocompravalores->pc85_datafinal     = $dtDataFim;
    $clpctipocompravalores->incluir(null);
    
    $sMensagem  = $clpctipocompravalores->erro_msg;
    if ($clpctipocompravalores->erro_status == 0) {
    	$lSqlErro = true; 
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->alterar)) {
	
  $sWhere                   = " pc85_codtipocompra = {$pc50_codcom}                                      ";
  $sWhere                  .= " and (pc85_datainicial::date,pc85_datafinal::date)                        ";
  $sWhere                  .= "      overlaps ('{$dtDataIni}'::date,'{$dtDataFim}'::date)                "; 
    
  $sSqlPcTipoCompraValores  = $clpctipocompravalores->sql_query(null,"pctipocompravalores.*",null,$sWhere);
  $rsSqlPcTipoCompraValores = $clpctipocompravalores->sql_record($sSqlPcTipoCompraValores);
  $iNumRows                 = $clpctipocompravalores->numrows;
  if ($iNumRows > 0) {
      
    $lSqlErro     = true;
    $sMensagem    = "Usuário: \\n\\n";
    $sMensagem   .= "Valor informado já cadastrado para esse período! \\n\\n";
  }
	
  if (!$lSqlErro) {
  	
    db_inicio_transacao();

    $clpctipocompravalores->pc85_sequencial    = $oPost->pc85_sequencial;
    $clpctipocompravalores->pc85_codtipocompra = $pc50_codcom;
    $clpctipocompravalores->pc85_valorminimo   = $oPost->pc85_valorminimo;
    $clpctipocompravalores->pc85_valormaximo   = $oPost->pc85_valormaximo;
    $clpctipocompravalores->pc85_datainicial   = $dtDataIni;
    $clpctipocompravalores->pc85_datafinal     = $dtDataFim;
    $clpctipocompravalores->alterar($clpctipocompravalores->pc85_sequencial);
    
    $sMensagem  = $clpctipocompravalores->erro_msg;
    if ($clpctipocompravalores->erro_status == 0) {
      $lSqlErro = true; 
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->excluir)) {
	
  if (!$lSqlErro) {
  	
    db_inicio_transacao();
  	
    $clpctipocompravalores->pc85_sequencial  = $oPost->pc85_sequencial;
    $clpctipocompravalores->excluir($clpctipocompravalores->pc85_sequencial);
    
    $sMensagem  = $clpctipocompravalores->erro_msg;
    if ($clpctipocompravalores->erro_status == 0) {
      $lSqlErro = true; 
    }
    
    db_fim_transacao($lSqlErro);
  }
} else if (isset($oPost->opcao)) {
  
	if (isset($oPost->pc85_sequencial) && !empty($oPost->pc85_sequencial)) {
		
	  $sSqlPcTipoCompraValores  = $clpctipocompravalores->sql_query($oPost->pc85_sequencial,"*",null,"");
	  $rsSqlPcTipoCompraValores = $clpctipocompravalores->sql_record($sSqlPcTipoCompraValores);
	  $iNumRows             = $clpctipocompravalores->numrows;
	  if ($iNumRows > 0) {
	    db_fieldsmemory($rsSqlPcTipoCompraValores,0);	
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
        include("forms/db_frmpctipocompravalores.php");
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
	  if ($clpctipocompravalores->erro_campo != "") {
        echo "<script> document.form1.".$clpctipocompravalores->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpctipocompravalores->erro_campo.".focus();</script>";
    }
	}
}
?>