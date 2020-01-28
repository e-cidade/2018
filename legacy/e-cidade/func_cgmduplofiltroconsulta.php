<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgmcorreto_classe.php");
require_once('libs/db_utils.php');
$oGet = db_utils::postMemory($_GET);

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcgmduplo = new rotulocampo();
$clcgmduplo->label("z10_codigo");
$clcgmduplo->label("z10_numcgm");
$clcgmduplo->label("z10_nome");

$clcgmcorreto = new cl_cgmcorreto();
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
    <td height="63" align="center" valign="top">
       
       <fieldset style="width: 35%"><legend><b>Pesquisa de CGM correto</b></legend>
        <table width="20%" border="0" align="center" cellspacing="0">
       

  
  <tr> 
    <td  align="center" valign="top"> 
     <? 
  
     $sWhere = "";
     $sAnd = "";
               	
           /*
           * se informou cgmerrado
           */
     
        	if ( isset($cgmcorreto) && !empty($cgmcorreto) ){
        		
        	  $sWhere .= "{$sAnd} z10_numcgm = $cgmcorreto ";
        	  $sAnd   = " and "; 
      	
	      	}
	      	
	      	/*
	      	 * se informou cgmerrado
	      	 */
	      	
			    if ( isset($cgmerrado) && !empty($cgmerrado) ){
			      		
      	     $sWhere .= "{$sAnd} z11_numcgm = $cgmerrado ";
      	     $sAnd =" and ";   	
			      		
	      	}
	      	
	      	/*
	      	 * se informou usuario(s)
	      	 */
	      	
	      	if ( isset($iUsuarios) && !empty($iUsuarios)){
	      		
	      		$sWhere .= "{$sAnd} z10_login in ($iUsuarios) ";
	      		$sAnd = " and ";
	      		
	      	}
	      	  /*
	      	   * se informou apenas data inicial
	      	   */
	      	
	      	  if ( ( isset($dtinivlrg) && !empty($dtinivlrg)) && ( isset($dtfimvlrg) && empty($dtfimvlrg)) ){
            
            $sDtini    = implode("-", array_reverse(explode("/",$dtinivlrg)));

            $sWhere .= "{$sAnd} z10_data >= '$sDtini' ";
            $sAnd = " and ";
            
            }
          
            
            /*
             * se informou apenas data final
             */
          
            if ( ( isset($dtinivlrg) && empty($dtinivlrg)) && ( isset($dtfimvlrg) && !empty($dtfimvlrg)) ){
            
            $sDtfim    = implode("-", array_reverse(explode("/",$dtfimvlrg)));

            $sWhere .= "{$sAnd} z10_data <= '$sDtfim' ";
            $sAnd = " and ";
            
          }
          
          /*
           * se informou as duas datas
           */
	      	
	      	if ( ( isset($dtinivlrg) && !empty($dtinivlrg)) && ( isset($dtfimvlrg) && !empty($dtfimvlrg)) ){
	      		
	      		$sDtini    = implode("-", array_reverse(explode("/",$dtinivlrg)));
	      		$sDtfim    = implode("-", array_reverse(explode("/",$dtfimvlrg)));
	      		
	      		$sWhere .= "{$sAnd} z10_data between '$sDtini' and '$sDtfim' ";
            $sAnd = " and ";
            
	      	}
	      	
	      	
	      	$sWhere .= " {$sAnd} cgmcorreto.z10_proc = true ";
	
					$sCampos         = "distinct on (z10_numcgm) z10_numcgm as dl_cgmcorreto, ";
				  $sCampos        .= "z01_nome as dl_nomecorreto,  ";
					$sCampos        .= "z11_numcgm as dl_cgmerrado,  ";
					$sCampos        .= "z11_nome as dl_nomeerrado,   ";
					$sCampos        .= "z10_data,                 ";
					$sCampos        .= "z10_hora,                 ";
					$sCampos        .= "z10_login,                ";
					$sCampos        .= "z10_proc,                 ";
					$sCampos        .= "z10_codigo,                ";
					$sCampos        .= "nome as usuario           "; 
					$sSqlCgmCorreto  = $clcgmcorreto->sql_query_cgmduploprocessado(null, $sCampos, null, $sWhere);
	  
	        db_lovrot($sSqlCgmCorreto,15,"()","",$funcao_js);
      
      

      ?>
     </td>
   </tr>
</table>

</body>
</html>