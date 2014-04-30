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
include("classes/db_clientesmodulosproc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clclientesmodulosproc = new cl_clientesmodulosproc;

if(isset($atualizar)){
   db_inicio_transacao();
   
   $result = $clclientesmodulosproc->excluir(null," at75_seqclimod = $sequencial ");

   reset($HTTP_POST_VARS);

   for($i=0;$i<count($HTTP_POST_VARS);$i++){
  
     if(substr(key($HTTP_POST_VARS),0,14) == "at75_codproced"){
       $var1 = "at75_codproced_".substr(key($HTTP_POST_VARS),15);
       $var2 = "at75_data_".substr(key($HTTP_POST_VARS),15);
   
       if( $$var2 != "" ){
         
         $var22 = "at75_data_".substr(key($HTTP_POST_VARS),15)."_ano";
         $var23 = "at75_data_".substr(key($HTTP_POST_VARS),15)."_mes";
         $var24 = "at75_data_".substr(key($HTTP_POST_VARS),15)."_dia";

         $var3 = "at75_obs_".substr(key($HTTP_POST_VARS),15);

         $clclientesmodulosproc->at75_sequen = 0;
         $clclientesmodulosproc->at75_seqclimod = $sequencial;
         $clclientesmodulosproc->at75_codproced = $$var1;
         $clclientesmodulosproc->at75_data      = $$var22."-".$$var23."-".$$var24;
         $clclientesmodulosproc->at75_obs       = $$var3;

         $clclientesmodulosproc->incluir(0);
         if($clclientesmodulosproc->erro_status == "0"){
           db_msgbox($clclientesmodulosproc->erro_msg);
         }

       }
     }

     next($HTTP_POST_VARS);
   
   }

   db_fim_transacao();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmclientesmodulosproc.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>