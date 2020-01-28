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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

$oGet	    = db_utils::postMemory($_GET);
$clcriaabas = new cl_criaabas;

?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
     <td>
     <?
     
       $clcriaabas->identifica = array("dados"   =>"Dados do imovel", 
         					           "transm"  =>"Transmitentes",                  
         					           "compnome"=>"Adquirentes",                        
         					           "constr"  =>"Benfeitorias");
       
       $clcriaabas->title      = array("dados"   =>"Dados do imovel",            
       								   "transm"  =>"Transmitentes",                  
       								   "compnome"=>"Adquirentes",                        
       								   "constr"	 =>"Benfeitorias");
           
       $clcriaabas->src        = array("dados"	 =>"itb1_itbidadosimovel002.php",
       								   "transm"  =>"itb1_itbinome001.php?tiponome=t",
       								   "compnome"=>"itb1_itbinomecomp001.php?tiponome=c",
       								   "constr"	 =>"itb1_itbiconstr001.php");    
     
     
       $clcriaabas->cria_abas();    
     ?> 
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?

echo "<script>document.formaba.compnome.size='20';</script>"; 
echo "<script>document.formaba.itbi.size='20';</script>"; 
echo "<script>document.formaba.dados.size='20';</script>"; 
echo "<script>document.formaba.transm.size='20';</script>"; 
echo "<script>document.formaba.constr.size='20';</script>"; 
echo "<script>document.formaba.old.size='20';</script>"; 

  echo "
         <script>
		   function js_src(){
		    document.formaba.dados.disabled=true; 
		    document.formaba.transm.disabled=true; 
		    document.formaba.compnome.disabled=true; 
		    document.formaba.constr.disabled=true; 
		    document.formaba.old.disabled=true; 
		   }
		   js_src();
         </script>
       ";
 
?>