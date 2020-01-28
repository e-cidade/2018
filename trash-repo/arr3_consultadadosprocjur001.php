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
include("libs/db_utils.php");
include("classes/db_procjur_classe.php");
include("dbforms/db_funcoes.php");
	
$oGet	   = db_utils::postMemory($_GET);
$clprocjur = new cl_procjur();
$clprocjur->rotulo->label();

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle">
	  <table width="95%" border="1" cellspacing="0" class='tab_cinza'>
	    <?
		    $rsDadosProcesso  = $clprocjur->sql_record($clprocjur->sql_query_susp($oGet->procjur));	
	        $iNumRowsProcesso = $clprocjur->numrows;
			
			if($iNumRowsProcesso > 0){

			  $oDadosProcesso = db_utils::fieldsMemory($rsDadosProcesso,0);
			  	
			  echo " <tr>																				 	 ";
			  echo "   <th align='center' colspan='4'>&nbsp;<b>Dados do Processo</b> 				   </th> ";
			  echo " </tr>																					 ";
			  echo " <tr>																					 ";
			  echo "   <th align='left' width='15%' >Código do Processo: 			  				   </th> ";
			  echo "   <td align='left' width='35%' >{$oDadosProcesso->v62_sequencial}			       </td> ";
			  echo "   <th align='left' width='15%' >Descrição do Processo:			  			       </th> ";
			  echo "   <td align='left' width='35%' >{$oDadosProcesso->v62_descricao} 				   </td> ";
			  echo " </tr>																					 ";
			  echo " <tr>																			 		 ";
			  echo "   <th align='left' width='15%' >Tipo: 			 				  				   </th> ";
			  echo "   <td align='left' width='35%' >{$oDadosProcesso->v66_descr}	  				   </td> ";
			  echo "   <th align='left' width='15%' >Situacão:			 			  				   </th> ";
			  
			  if ( $oDadosProcesso->v62_situacao == 1) {
			  	$sSituacao = "Ativo";
			  } else {
			  	$sSituacao = "Finalizado";
			  }
			  
			  echo "   <td align='left' width='35%' >{$sSituacao} 					 				   </td> ";
			  echo " </tr>																					 ";
			  echo " <tr>																					 ";			  			  
			  echo "   <th align='left' width='15%' >Data Processo: 								   </th> ";
			  echo "   <td align='left' width='35%' >".db_formatar($oDadosProcesso->v62_data,"d")."    </td> ";
			  echo "   <th align='left' width='15%' >Hora:			 			 	  				   </th> ";
			  echo "   <td align='left' width='35%' >{$oDadosProcesso->v62_hora}  	  				   </td> ";
			  echo " </tr>																					 ";			  
			  echo " <tr>																	 				 ";			  
			  echo "   <th align='left' width='15%' >Data Inicial: 			 	  	 				   </th> ";
			  echo "   <td align='left' width='35%' >".db_formatar($oDadosProcesso->v62_dataini,"d")." </td> ";
			  echo "   <th align='left' width='15%' >Data Final:			 		 				   </th> ";
			  echo "   <td align='left' width='35%' >".db_formatar($oDadosProcesso->v62_datafim,"d")." </td> ";			  
			  echo " </tr>																	 				 ";			  
			  echo " <tr>																					 ";			  			  
			  echo "   <th align='left' width='15%' >Usuário: 			 	  		 				   </th> ";
			  echo "   <td align='left' width='35%' >{$oDadosProcesso->login}	  	  				   </td> ";
			  echo " </tr>																				 	 ";

			  if (trim($oDadosProcesso->v62_sequencial) != "") {
			  	
			    echo " <tr>																		 ";			  
			    echo "   <th align='left' width='15%' >Cód. Processo Jurídico:	 		   </th> ";
			    echo "   <td align='left' width='35%' >{$oDadosProcesso->v63_sequencial}   </td> ";			  
			    echo "   <th align='left' width='15%' >Localiza: 			 		   	   </th> ";
			    echo "   <td align='left' width='35%' >{$oDadosProcesso->v63_localiza}     </td> ";
			    echo " </tr>																	 ";
			    echo " <tr>																		 ";			  
			    echo "   <th align='left' width='15%' >Vara:	 			 		   	   </th> ";
			    echo "   <td align='left' width='35%' >{$oDadosProcesso->v63_vara} 	       </td> ";
			    echo "   <th align='left' width='15%' >Processo Foro:			 		   </th> ";
			    echo "   <td align='left' width='35%' >{$oDadosProcesso->v63_processoforo} </td> ";			  
			    echo " </tr>																	 ";
			    
			  }

			  if (trim($oDadosProcesso->v64_sequencial) != "") {
			  	
			    echo " <tr>																		 ";			  
			    echo "   <th align='left' width='15%' >Cód. Proc. Administrativo:	 	   </th> ";
			    echo "   <td align='left' width='35%' >{$oDadosProcesso->v64_sequencial}   </td> ";			  
			    echo "   <th align='left' width='15%' >Processo Protocolo: 	 		   	   </th> ";
			    echo "   <td align='left' width='35%' >{$oDadosProcesso->v64_protprocesso} </td> ";
			    echo " </tr>																	 ";
			    
			  }			  
			  
			  
			  echo " <tr>																	";
			  echo "	 <th align='left' width='15%' >Observações :				  </th>	";
			  echo "	 <td align='left' colspan='3' >{$oDadosProcesso->v62_obs}	  </td>	";
			  echo " </tr>																	";

			}else{
		
			  echo "    <tr>";
			  echo "      <th align='center'>Processo não localizado</th>";
			  echo "    </tr>";
			  
		    }
		?>		
	  </table>
    </td>
  </tr>
</table>
</body>
</html>