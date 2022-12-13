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
include("classes/db_cgmerrado_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcgmerrado = new cl_cgmerrado;
$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){

  
  $sql1 = "select z01_nome from cgm where z01_numcgm = $z11_numcgm ";
  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);
  if($linhas1>0){
  	db_fieldsmemory($result1,0);
  	db_inicio_transacao();
    $db_opcao = 2;
    $clcgmerrado->excluir($z11_codigo,$z11_numcgm_old);
    $clcgmerrado->z11_nome = $z01_nome;
    $clcgmerrado->incluir($z11_codigo,$z11_numcgm);
    db_fim_transacao();
  }else{
  	db_msgbox("O CGM digitato inválido");
  }
  
  //$z11_nome = $z01_nome;
  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clcgmerrado->sql_record($clcgmerrado->sql_query($chavepesquisa,$chavepesquisa1)); 
   db_fieldsmemory($result,0);
   if ($z10_proc =='t'){
   		$db_botao = false;
   		db_msgbox("Você não pode alterar um CGM já processado pela rotina Elimina Duplos");
   }else{
   		$db_botao = true;
   }
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcgmerrado.php");
	?>
    </center>
	</td>
  </tr>
</table>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clcgmerrado->erro_status=="0"){
    $clcgmerrado->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    echo "
         <script>
           parent.iframe_cgmerrado.location.href ='pro1_cgmerrado001.php?z11_codigo=$z11_codigo&abas=1';\n
         </script>
       ";
    if($clcgmerrado->erro_campo!=""){
      echo "<script> document.form1.".$clcgmerrado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgmerrado->erro_campo.".focus();</script>";
    };
  }else{
    $clcgmerrado->erro(true,false);
    echo "
         <script>
         function js_src(){
           parent.iframe_cgmerrado.location.href ='pro1_cgmerrado001.php?z11_codigo=$z11_codigo&abas=1';\n
         }
         js_src();
         </script>
       ";
  };
};
?>