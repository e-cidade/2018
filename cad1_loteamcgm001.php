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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_loteam_classe.php");
require_once("classes/db_loteamcgm_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clloteam                 = new cl_loteam;
$clloteamcgm              = new cl_loteamcgm;
$clcgm                    = new cl_cgm;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$db_opcao   = 1;
$db_botao   = true;
$lSqlErro   = false;

if ( isset($oGet->codigo) ) {
	
	$sSqlLoteAm  = $clloteam->sql_query(null,"*",null," j34_loteam = {$oGet->codigo}");
	$rsSqlLoteAm = $clloteam->sql_record($sSqlLoteAm);
	if ( $clloteam->numrows > 0 ) {
		
		$oLoteAm = db_utils::fieldsMemory($rsSqlLoteAm,0);
		$j34_loteam = $oLoteAm->j34_loteam;
		$j34_descr  = $oLoteAm->j34_descr;
	}
}

if ( isset($oPost->incluir) ) {
	
  db_inicio_transacao();
  
  if ( !$lSqlErro ) {
  	
  	$clloteamcgm->j120_loteam = $oPost->j34_loteam;
  	$clloteamcgm->j120_cgm    = $oPost->z01_numcgm;
    $clloteamcgm->incluir(null);
    
    $sErroMsg = $clloteamcgm->erro_msg;
    if ( $clloteamcgm->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }
  
  db_fim_transacao($lSqlErro);
  
} else if ( isset($oPost->alterar) ) {
	
  db_inicio_transacao();
  
  if ( !$lSqlErro ) {
    
    $clloteamcgm->j120_loteam = $oPost->j34_loteam;
    $clloteamcgm->j120_cgm    = $oPost->z01_numcgm;
    $clloteamcgm->alterar($oPost->j120_sequencial);
    
    $sErroMsg = $clloteamcgm->erro_msg;
    if ( $clloteamcgm->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }
  
  db_fim_transacao($lSqlErro); 
  
} else if ( isset($oPost->excluir) ) {
	
  db_inicio_transacao();
  
  if ( !$lSqlErro ) {
    
    $clloteamcgm->excluir($oPost->j120_sequencial);
    
    $sErroMsg = $clloteamcgm->erro_msg;
    if ( $clloteamcgm->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }
  
  db_fim_transacao($lSqlErro);  
    
}  

if ( isset($oPost->opcao) ) {
	
	if ( $oPost->opcao == 'alterar' ) {

		$db_opcao   = 2;
		if ( isset($oPost->j120_sequencial) ) {
		  
		  $sSqlLoteAmCgm  = $clloteamcgm->sql_query($oPost->j120_sequencial,"loteamcgm.*",null,"");		  
		  $rsSqlLoteAmCgm = $clloteamcgm->sql_record($sSqlLoteAmCgm);
		  if ( $clloteamcgm->numrows > 0 ) {
		  	
		    db_fieldsMemory($rsSqlLoteAmCgm,0);
		    $sSqlCgm  = $clcgm->sql_query($j120_cgm,"z01_numcgm,z01_nome",null,"");
		    $rsSqlCgm = $clcgm->sql_record($sSqlCgm);
		    if ( $clcgm->numrows > 0 ) {
		      db_fieldsMemory($rsSqlCgm,0);	
		    }
		  }
		}		
		
	} else if ( $oPost->opcao == 'excluir' ) {

	  $db_opcao   = 3;
    if ( isset($oPost->j120_sequencial) ) {
      
      $sSqlLoteAmCgm  = $clloteamcgm->sql_query($oPost->j120_sequencial,"loteamcgm.*",null,"");     
      $rsSqlLoteAmCgm = $clloteamcgm->sql_record($sSqlLoteAmCgm);
      if ( $clloteamcgm->numrows > 0 ) {
        
        db_fieldsMemory($rsSqlLoteAmCgm,0);
        $sSqlCgm  = $clcgm->sql_query($j120_cgm,"z01_numcgm,z01_nome",null,"");
        $rsSqlCgm = $clcgm->sql_record($sSqlCgm);
        if ( $clcgm->numrows > 0 ) {
          db_fieldsMemory($rsSqlCgm,0); 
        }
      }
    } 	  
	}
} else {
	
  $z01_numcgm  = "";
  $z01_nome    = "";
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
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top"> 
      <?
        include("forms/db_frmloteamcgm.php");
      ?>
  </td>
  </tr>
</table>
</body>
</html>
<?
if ( isset($sErroMsg) ) {
	
	if ( !empty($sErroMsg) ) {
		db_msgbox($sErroMsg);
	}
}
?>