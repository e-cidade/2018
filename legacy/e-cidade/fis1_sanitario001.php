<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_sanitario_classe.php");
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
       $clcriaabas->identifica = array("sanitario"=>"Sanitário", "observacoes"=>"Observa&ccedil;&otilde;es","saniatividade"=>"Atividades","resptecnico"=>"Responsável Técnico","calculo"=>"Cálculo");//nome do iframe e o label    
       $clcriaabas->title      = array("sanitario"=>"Manutenção de Alvará Sanitário","observacoes"=>"Observa&ccedil;&otilde;es", "saniatividade"=>"Manutenção de atividades do sanitário","resptecnico"=>"Responsável Técnico","calculo"=>"Cálculo");//nome do iframe e o label    
       $clcriaabas->src = array("sanitario"=>"fis1_sanitario005.php","observacoes"=>"fis1_sanitario006.php","saniatividade"=>"fis1_saniatividade001.php","resptecnico"=>"fis1_resptecnico001.php","calculo"=>"fis1_sanicalc001.php");//nome do iframe e o label    
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
echo "<script>document.formaba.resptecnico.size=20;</script>"; 
if(isset($db_opcao) && $db_opcao==2){
  echo "
         <script>
	   function js_src(){
            iframe_sanitario.location.href='fis1_sanitario002.php?y80_codsani=';\n
	   }
	   js_src();
         </script>
       ";
       exit;
}else if(isset($db_opcao) && $db_opcao==3){
echo "
         <script>
	   function js_src(){
            iframe_sanitario.location.href='fis1_sanitario003.php?y80_codsani=';\n
			document.formaba.observacoes.disabled=true;
	    document.formaba.saniatividade.disabled=true; 
	    document.formaba.resptecnico.disabled=true; 
	    document.formaba.calculo.disabled=true; 
	   }
	   js_src();
         </script>
       ";
exit; 
}
  echo "
	 <script>
	    document.formaba.observacoes.disabled=true;
	    document.formaba.saniatividade.disabled=true; 
	    document.formaba.resptecnico.disabled=true; 
	    document.formaba.calculo.disabled=true; 
         </script>
       "; 
       exit;
?>