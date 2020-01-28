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
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
     <td>
     <?
       $clcriaabas->identifica = array("auto"=>"Auto de Infração","autotipo"=>"Procedência","receitas"=>"Receitas","fiscais"=>"Fiscais","testem"=>"Testemunhas","calculo"=>"Cálculo"); 
       $clcriaabas->title = array("auto"=>"Auto de Infração","autotipo"=>"Procedência","receitas"=>"Receitas","fiscais"=>"Fiscais","testem"=>"Testemunhas","calculo"=>"Cálculo");    
       $clcriaabas->src = array("auto"=>"fis1_auto001.php?abas=1","autotipo"=>"fis1_autotipo001.php","receitas"=>"fis1_autorec001.php","fiscais"=>"fis1_autousu001.php","teste"=>"fis1_autotestem001.php","calculo"=>"fis1_autocalc001.php");  
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
if(isset($db_opcao) && $db_opcao==2){
  echo "
         <script>
	   function js_src(){
	    document.formaba.auto.size=15; 
            iframe_auto.location.href='fis1_auto002.php?abas=1';\n
	   }
	   js_src();
         </script>
       ";
       exit;
}else if(isset($db_opcao) && $db_opcao==3){
echo "
         <script>
	   function js_src(){
	    document.formaba.auto.size=15; 
        iframe_auto.location.href='fis1_auto003.php?abas=1';\n
	    document.formaba.autotipo.disabled=true; 
	    document.formaba.receitas.disabled=true; 
	    document.formaba.fiscais.disabled=true; 
	    document.formaba.testem.disabled=true; 
	    document.formaba.calculo.disabled=true; 
	   }
	   js_src();
         </script>
       ";
exit; 
}
  echo "
	 <script>
	    document.formaba.autotipo.disabled=true; 
	    document.formaba.receitas.disabled=true; 
	    document.formaba.fiscais.disabled=true; 
	    document.formaba.testem.disabled=true; 
	    document.formaba.calculo.disabled=true; 
	    document.formaba.auto.size=15; 
         </script>
       "; 
       exit;
?>