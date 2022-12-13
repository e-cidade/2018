<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$oGet = db_utils::postMemory($HTTP_GET_VARS);

$sWhere = "";
if(trim($oGet->e69_numero)!=''){
	if($sWhere == ""){
		$sWhere .= " e69_numero='".$oGet->e69_numero."' ";
	}else{
		$sWhere .= " and e69_numero='".$oGet->e69_numero."' ";
	}
}
if(trim($oGet->e60_codemp)!=''){
	
	$iAnoUsu = db_getsession("DB_anousu");
	
	$aCodEmp = explode('/',$oGet->e60_codemp);
	
	if (count($aCodEmp) == 2) {
	 $iAnoUsu    = $aCodEmp[1];
	 $e60_codemp = $aCodEmp[0];   	   
	}
	
	if($sWhere == ""){
    $sWhere .= " e60_codemp='".$e60_codemp."' and e60_anousu = ".$iAnoUsu;
  }else{
    $sWhere .= " and e60_codemp='".$e60_codemp."' and e60_anousu = ".$iAnoUsu;
  }
}
if(trim($oGet->z01_numcgm)!=''){
  if($sWhere == ""){
    $sWhere .= " z01_numcgm=".$oGet->z01_numcgm;
  }else{
    $sWhere .= " and z01_numcgm=".$oGet->z01_numcgm;
  }
}

if(trim($oGet->m51_codordem)!=''){
  if($sWhere == ""){
    $sWhere .= " m51_codordem=".$oGet->m51_codordem;
  }else{
    $sWhere .= " and m51_codordem=".$oGet->m51_codordem;
  }
}


if ( isset($oGet->e04_numeroprocesso) && !empty($oGet->e04_numeroprocesso) ) {
  
  $sProcesso = addslashes($oGet->e04_numeroprocesso);
  if($sWhere == ""){
    
    $sWhere .= " e04_numeroprocesso ilike '%{$sProcesso}%' ";
  }else{
    
    $sWhere .= " and e04_numeroprocesso ilike '%{$sProcesso}%' ";
  }
  
}



if ( isset($oGet->dtini) && !empty($oGet->dtini) && isset($oGet->dtfim) && !empty($oGet->dtfim) ) {
  
  if(trim($oGet->dtini) != '' && trim($oGet->dtfim) != '' ){
    if($sWhere == ""){
      $sWhere .= " e69_dtnota between '".$oGet->dtini."' and '".$oGet->dtfim."'";
    }else{  
      $sWhere .= " and e69_dtnota between '".$oGet->dtini."' and '".$oGet->dtfim."'";
    }
  }else if(trim($oGet->dtini) != ''){
    if($sWhere == ""){
      $sWhere .= " e69_dtnota = '".$oGet->dtini."' ";
    }else{
      $sWhere .= " and e69_dtnota = '".$oGet->dtini."' ";
    }
  }else if(trim($oGet->dtfim) != ''){
    if($sWhere == ""){
      $sWhere .= " e69_dtnota = '".$oGet->dtfim."' ";
    }else{
      $sWhere .= " and e69_dtnota = '".$oGet->dtfim."' ";
    }
  }
}

$sSql = "
          select distinct empnotaord.m72_codordem, 
                          empnota.e69_codnota, 
                          empnota.e69_numero, 
                          substr(z01_nome,1,30) as z01_nome, 
                          empnota.e69_numemp, 
									        empnota.e69_dtrecebe, 
									        empnotaele.e70_valor, 
									        empnotaele.e70_vlrliq, 
									        empnotaele.e70_vlranu ,
                          empnotaprocesso.e04_numeroprocesso
                     from empnota 
									        inner join empnotaele  on e69_codnota = e70_codnota 
									        inner join db_usuarios on db_usuarios.id_usuario = empnota.e69_id_usuario 
									        inner join empempenho  on empempenho.e60_numemp   = empnota.e69_numemp 
									        inner join cgm         on cgm.z01_numcgm = empempenho.e60_numcgm 
									        left join pagordemnota on e71_codnota = empnota.e69_codnota 
									                              and e71_anulado is false 
									        left join pagordem     on e71_codord = e50_codord 
									        left join pagordemele  on e53_codord   = pagordemnota.e71_codord 
									        left join empnotaord   on m72_codnota = e69_codnota 
									        left join matordem     on m72_codordem    = m51_codordem 
									        left join matordemanu  on m51_codordem = m53_codordem
									        left join empnotaitem  on e69_codnota  =  e72_codnota 
									        left join empempitem   on e62_sequencial = e72_empempitem
                          left join empnotaprocesso on empnota.e69_codnota = empnotaprocesso.e04_empnota
                    ";
if($sWhere != ""){
	$sWhere = " where ".$sWhere; 
}
$sSql .= $sWhere;
$sSql .=	"				  order by e69_codnota "; 

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