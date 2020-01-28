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
require_once("classes/db_habittipogrupoprograma_classe.php");
require_once("classes/db_habittipogrupoprogramaprocdoc_classe.php");
require_once("classes/db_procdoc_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clhabittipogrupoprograma        = new cl_habittipogrupoprograma;
$clhabittipogrupoprogramaprocdoc = new cl_habittipogrupoprogramaprocdoc;
$clprocdoc                       = new cl_procdoc;

$db_opcao = 2;
$db_botao = false;
$sqlerro  = false;

if (isset($incluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clhabittipogrupoprogramaprocdoc->ht09_habittipogrupoprograma = $oPost->ht09_habittipogrupoprograma;
    $clhabittipogrupoprogramaprocdoc->ht09_procdoc                = $oPost->ht09_procdoc;
    $clhabittipogrupoprogramaprocdoc->ht09_obs                    = $oPost->ht09_obs;
    $clhabittipogrupoprogramaprocdoc->ht09_obrigatorio            = $oPost->ht09_obrigatorio;
    $clhabittipogrupoprogramaprocdoc->incluir(null);
    $erro_msg = $clhabittipogrupoprogramaprocdoc->erro_msg;
    if ($clhabittipogrupoprogramaprocdoc->erro_status == 0) {
    	$sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if(isset($alterar)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clhabittipogrupoprogramaprocdoc->ht09_sequencial             = $oPost->ht09_sequencial;
    $clhabittipogrupoprogramaprocdoc->ht09_habittipogrupoprograma = $oPost->ht09_habittipogrupoprograma;
    $clhabittipogrupoprogramaprocdoc->ht09_procdoc                = $oPost->ht09_procdoc;
    $clhabittipogrupoprogramaprocdoc->ht09_obs                    = $oPost->ht09_obs;
    $clhabittipogrupoprogramaprocdoc->ht09_obrigatorio            = $oPost->ht09_obrigatorio;
    $clhabittipogrupoprogramaprocdoc->alterar($clhabittipogrupoprogramaprocdoc->ht09_sequencial);
    $erro_msg = $clhabittipogrupoprogramaprocdoc->erro_msg;
    if ($clhabittipogrupoprogramaprocdoc->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clhabittipogrupoprogramaprocdoc->ht09_sequencial = $oPost->ht09_sequencial;
    $clhabittipogrupoprogramaprocdoc->excluir($clhabittipogrupoprogramaprocdoc->ht09_sequencial);
    $erro_msg = $clhabittipogrupoprogramaprocdoc->erro_msg;
    if ($clhabittipogrupoprogramaprocdoc->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if(isset($opcao)) {
	
	$sSqlTipoGrupoProgramaProcdoc = $clhabittipogrupoprogramaprocdoc->sql_query($ht09_sequencial);
	$rsTipoGrupoProgramaProcdoc   = $clhabittipogrupoprogramaprocdoc->sql_record($sSqlTipoGrupoProgramaProcdoc);
	if ($rsTipoGrupoProgramaProcdoc != false && $clhabittipogrupoprogramaprocdoc->numrows > 0) {
		db_fieldsmemory($rsTipoGrupoProgramaProcdoc,0);
	}
}

if (isset($oGet->ht02_sequencial)) {
	$ht09_habittipogrupoprograma = $oGet->ht02_sequencial;
} else {
	$ht09_habittipogrupoprograma = $oPost->ht09_habittipogrupoprograma;
}

$sWhere                = "ht02_sequencial = {$ht09_habittipogrupoprograma}";
$sSqlTipoGrupoPrograma = $clhabittipogrupoprograma->sql_query(null,"*",null,$sWhere);
$rsTipoGrupoPrograma   = $clhabittipogrupoprograma->sql_record($sSqlTipoGrupoPrograma);
if ($clhabittipogrupoprograma->numrows > 0) {
	
	db_fieldsmemory($rsTipoGrupoPrograma,0);
	$ht09_habittipogrupoprograma = $ht02_sequencial;
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
.fieldsetinterno {
  border:0px;
  border-top:2px groove white;
  margin-top:10px;
}

td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 100px;
  white-space: nowrap
}

#ht02_descricao {
  width: 81%;
}

#p56_descr {
  width: 81%;
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
        include("forms/db_frmhabittipogrupoprogramaprocdoc.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
	
  db_msgbox($erro_msg);
  if ($clhabittipogrupoprogramaprocdoc->erro_campo != "") {
  	
    echo "<script> document.form1.".$clhabittipogrupoprogramaprocdoc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clhabittipogrupoprogramaprocdoc->erro_campo.".focus();</script>";
  }
}
?>