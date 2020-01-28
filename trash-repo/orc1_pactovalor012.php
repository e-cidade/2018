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
include("classes/db_pactovalor_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpactovalor = new cl_pactovalor;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  if ($o87_pactoprograma != "") {
   $clpactovalor->o87_orcprogramaano = db_getsession("DB_anousu");
  }else {
    $clpactovalor->o87_orcprogramaano = null;
  }
  $clpactovalor->alterar($o87_sequencial);
  db_fim_transacao();
  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clpactovalor->sql_record($clpactovalor->sql_query($chavepesquisa));
   
   db_fieldsmemory($result,0);
   $db_botao = true;
   echo " <script>
             parent.document.formaba.itememp.disabled = false;
             parent.iframe_itememp.location.href      = 'orc1_empempitempacto001.php?codpacto=".$o87_sequencial."'; 
          </script>";       
   
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
<table align="center" style="padding-top:25px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
			<?
			  include("forms/db_frmpactovalor.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clpactovalor->erro_status=="0"){
    $clpactovalor->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpactovalor->erro_campo!=""){
      echo "<script> document.form1.".$clpactovalor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpactovalor->erro_campo.".focus();</script>";
    }
  }else{
   echo " <script>
             parent.document.formaba.itememp.disabled = true;
          </script>";  	
    $clpactovalor->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","o87_pactoplano",true,1,"o87_pactoplano",true);
</script>