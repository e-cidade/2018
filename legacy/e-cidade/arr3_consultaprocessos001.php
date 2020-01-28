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
include("classes/db_procjur_classe.php");

$oGet 	   = db_utils::postmemory($_GET);
$clprocjur = new cl_procjur();

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
     
        $sCampos  = " distinct		 										  ";
        $sCampos .= " v62_sequencial,										  ";
        $sCampos .= " v62_descricao,  										  ";
        $sCampos .= " v62_data,		  										  ";
        $sCampos .= " v66_descr,	  										  ";
        $sCampos .= " case 		  											  ";
        $sCampos .= "   when v62_situacao = 1 then 'Ativo' else 'Finalizado'  ";
        $sCampos .= " end as v62_situacao	  								  ";
        
        $sSqlProcesso = $clprocjur->sql_query_susp(null,$sCampos,"v62_sequencial"," ar18_sequencial = {$oGet->suspensao}");
 		
        $funcao_js = "js_consultaDetalhes|v62_sequencial";
        
        db_lovrot($sSqlProcesso,50,"()","",$funcao_js,"");

      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
  function js_consultaDetalhes(iCodProcesso){
    js_OpenJanelaIframe('top.corpo',"db_iframe_procjur",'arr3_consultadadosprocjur001.php?procjur='+iCodProcesso,'Detalhes da Pesquisa',true);
  }
</script>