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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_iptutabelasconfig_classe.php");
require_once("classes/db_iptutabelasconfigcampochave_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cliptutabelasconfig            = new cl_iptutabelasconfig;
$cliptutabelasconfigcampochave  = new cl_iptutabelasconfigcampochave;
$db_opcao                       = 1;
$db_botao                       = true;
$lSqlErro                       = false;

if (!isset($j121_codarq)) {
	$j121_codarq = '';
}

if (!isset($j122_sequencial)) {
	$j122_sequencial = '';
}

if (isset($oPost->atualizar)) {
  
  if (!$lSqlErro) {
     
  	$sMsgErro = "Lista de campos atualizada com sucesso.";
  	
  	db_inicio_transacao();
  	  	
    $cliptutabelasconfigcampochave->excluir(null, "j124_iptutabelasconfig = {$oPost->j122_sequencial}");
    if ($cliptutabelasconfigcampochave->erro_status == 0) {
        
      $lSqlErro = true;
      $sMsgErro = $cliptutabelasconfigcampochave->erro_msg;
    }
  	
    if (!$lSqlErro) {
      
      if (isset($oPost->listacampos) && !empty($oPost->listacampos)) {
      	
      	$aListaCampos = explode(',', $oPost->listacampos);
        foreach ( $aListaCampos as $iCodArq ) {
        
          
          $cliptutabelasconfigcampochave->j124_codcam            = $iCodArq;
          $cliptutabelasconfigcampochave->j124_iptutabelasconfig = $oPost->j122_sequencial;
          $cliptutabelasconfigcampochave->incluir(null);
          if ($cliptutabelasconfigcampochave->erro_status == 0) {
            
            $lSqlErro = true;
            $sMsgErro = $cliptutabelasconfigcampochave->erro_msg;
          }
        }
      }
    }
    
    db_fim_transacao($lSqlErro);
  }
}

if (isset($oGet->j122_sequencial)) {
	$j122_sequencial = $oGet->j122_sequencial;
} else if (isset($oPost->j122_sequencial)) {
	$j122_sequencial = $oPost->j122_sequencial;
}

if (isset($j122_sequencial)) {
  
	$sWhere                 = "j122_sequencial = {$j122_sequencial}";
	$sSqlIptuTabelasConfig  = $cliptutabelasconfig->sql_query(null, "*", null, $sWhere);
	$rsSqlIptuTabelasConfig = $cliptutabelasconfig->sql_record($sSqlIptuTabelasConfig);
	if ($cliptutabelasconfig->numrows > 0) {
		db_fieldsmemory($rsSqlIptuTabelasConfig, 0);
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 90px;
              white-space: nowrap
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
        include("forms/db_frmiptutabelasconfigcampochave.php");
      ?>
    </td>
  </tr>
</table>
</body>
<?
if (isset($oPost->atualizar)) {

  if ($lSqlErro) {
    
    db_msgbox($sMsgErro);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($cliptutabelasconfigcampochave->erro_campo != "") {
      
      echo "<script> document.form1.".$cliptutabelasconfigcampochave->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptutabelasconfigcampochave->erro_campo.".focus();</script>";
    }
  } else {
    db_msgbox($sMsgErro);
  }
}
?>
</html>