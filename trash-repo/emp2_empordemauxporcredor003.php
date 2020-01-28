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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matordem_classe.php");
require_once("classes/db_empageordemcgm_classe.php");

$clEmpAgeOrdemCgm = new cl_empageordemcgm();

$oGet = db_utils::postMemory($HTTP_GET_VARS);

$sWhere = "";
if(trim($oGet->e42_sequencial)!=''){
	if($sWhere == ""){
		$sWhere .= " e42_sequencial='".$oGet->e42_sequencial."' ";
	}else{
		$sWhere .= " and e42_sequencial='".$oGet->e42_sequencial."' ";
	}
}
if(trim($oGet->z01_numcgm)!=''){
  if($sWhere == ""){
    $sWhere .= " z01_numcgm='".$oGet->z01_numcgm."' ";
  }else{
    $sWhere .= " and z01_numcgm='".$oGet->z01_numcgm."' ";
  }
}
if(trim($oGet->dtinicial)!='' && trim($oGet->dtfinal)!=''){
  if($sWhere == ""){
    $sWhere .= " e42_dtpagamento between  '".$oGet->dtinicial."' and '".$oGet->dtfinal."'";
  }else{
    $sWhere .= " and e42_dtpagamento between  '".$oGet->dtinicial."' and '".$oGet->dtfinal."'";
  }
}else if(trim($oGet->dtinicial)!=''){
  if($sWhere == ""){
    $sWhere .= " e42_dtpagamento >= '".$oGet->dtinicial."'";
  }else{
    $sWhere .= " and e42_dtpagamento >= '".$oGet->dtinicial."'";
  }
}else if(trim($oGet->dtfinal)!=''){
  if($sWhere == ""){
    $sWhere .= " e42_dtpagamento <= '".$oGet->dtfinal."'";
  }else{
    $sWhere .= " and e42_dtpagamento <= '".$oGet->dtfinal."'";
  }
}

$sCampos = "e42_sequencial,e42_dtpagamento,z01_nome";

$sSql = $clEmpAgeOrdemCgm->sql_query(null,$sCampos,null,$sWhere);
//die($sSql);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td align="center" valign="top">
    <? 
        $funcao_js = $oGet->funcao_js;
        db_lovrot($sSql,15,"()","",$funcao_js);
    ?>
    </td>
   </tr>
</table>
</body>
</html>