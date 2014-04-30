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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");

$oGet = db_utils::postmemory($_GET);

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
				$sSqlSusp  = " select distinct 										                                    									          ";
				$sSqlSusp .= "        arresusp.k00_numpre, 		                                        													  ";
				$sSqlSusp .= "        arresusp.k00_numpar, 		                                        													  ";
				$sSqlSusp .= "        arresusp.k00_receit, 		                                        													  ";
				$sSqlSusp .= "   		  k02_drecei,					                                    													          ";
				$sSqlSusp .= "		    k00_descr,			                                     															          ";
				$sSqlSusp .= "        case                                                                                        ";
				$sSqlSusp .= "          when v01_numpre is not null then v01_exerc                                                ";
				$sSqlSusp .= "          else cast(extract(year from k00_dtoper) as int4 )                                         ";
				$sSqlSusp .= "        end as dl_Ano,                                                                              ";
				$sSqlSusp .= "        arresusp.k00_valor, 		                                         													  ";
				$sSqlSusp .= "        arresusp.k00_vlrcor, 		                                         													  ";
				$sSqlSusp .= "        arresusp.k00_vlrjur, 		                                         													  ";
				$sSqlSusp .= "        arresusp.k00_vlrmul, 		                                        													  ";
				$sSqlSusp .= "        arresusp.k00_vlrdes, 		    	                                    												  ";
				$sSqlSusp .= "        (k00_vlrcor+coalesce(k00_vlrjur,0)+coalesce(k00_vlrmul,0)-coalesce(k00_vlrdes,0) ) as total ";
				$sSqlSusp .= "	 from arresusp 																			                                              ";
				$sSqlSusp .= "	 	  inner join suspensao   on suspensao.ar18_sequencial = arresusp.k00_suspensao	                ";
				$sSqlSusp .= "	 	  inner join arreinstit  on arreinstit.k00_numpre     = arresusp.k00_numpre		                  ";
				$sSqlSusp .= "	       				       	    and arreinstit.k00_instit     = ".db_getsession('DB_instit')."          ";
        $sSqlSusp .= "      left  join divida      on divida.v01_numpre         = arresusp.k00_numpre                     ";    				
        $sSqlSusp .= "                            and divida.v01_numpar         = arresusp.k00_numpar                     ";        
				$sSqlSusp .= "		  inner join tabrec      on arresusp.k00_receit       = k02_codigo				 	                    ";
				$sSqlSusp .= "      inner join tabrecjm    on tabrecjm.k02_codjm        = tabrec.k02_codjm			                  ";
				$sSqlSusp .= "		  inner join arretipo    on arresusp.k00_tipo 	      = arretipo.k00_tipo   		                ";
				$sSqlSusp .= "	where ar18_sequencial = {$oGet->suspensao}		 									 	                                ";
		    $sSqlSusp .= "  order by arresusp.k00_numpre,															                                        ";
		    $sSqlSusp .= "  		 arresusp.k00_numpar															                                            ";
	    
		    $arrayTot["k00_valor"]  = "k00_valor";
		    $arrayTot["k00_vlrcor"] = "k00_vlrcor";
		    $arrayTot["k00_vlrjur"] = "k00_vlrjur";
		    $arrayTot["k00_vlrmul"] = "k00_vlrmul";
	    	$arrayTot["k00_vlrdes"] = "k00_vlrdes";
	      $arrayTot["total"] 		  = "total";

        db_lovrot($sSqlSusp,50,"()","","","","NoMe", array(),false, $arrayTot);

      ?>
     </td>
   </tr>
</table>
</body>
</html>