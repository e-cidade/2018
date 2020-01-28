<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matparamconsulta_classe.php");
require_once("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmatparamconsulta = new cl_matparamconsulta;

$db_opcao = 1;

$iInstituicao = db_getsession("DB_instit");


if (isset($alterar)){
	
  db_inicio_transacao();
  $clmatparamconsulta->m38_instit                 = $iInstituicao;
  $clmatparamconsulta->m38_visualizacaoitens      = $m38_visualizacaoitens;
  $clmatparamconsulta->m38_visualizacaomatestoque = $m38_visualizacaomatestoque;
	$clmatparamconsulta->alterar($iInstituicao);
	db_fim_transacao();
	
} else {

   $sSql    = $clmatparamconsulta->sql_query($iInstituicao);
   $result  = $clmatparamconsulta->sql_record($sSql); 
	 $iLinhas = pg_num_rows($result);

   if ( $iLinhas > 0 ) {
     
	   db_fieldsmemory($result,0);
	   
	 } else {
	   
	   $clmatparamconsulta->m38_instit                 = $iInstituicao;
	   $clmatparamconsulta->m38_visualizacaoitens      = 1;
	   $clmatparamconsulta->m38_visualizacaomatestoque = "false"; 
	   $clmatparamconsulta->incluir($iInstituicao);
	   if ($clmatparamconsulta->erro_status == "0") {
	     db_msgbox($clmatparamconsulta->erro_msg);
	   }
	   
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


<center>
  <?
    include("forms/db_frmmatparamconsulta.php");
  ?>
</center>



<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clmatparamconsulta->erro_status=="0"){
    $clmatparamconsulta->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clmatparamconsulta->erro_campo!=""){
      echo "<script> document.form1.".$clmatparamconsulta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatparamconsulta->erro_campo.".focus();</script>";
    }
  }else{
    $clmatparamconsulta->erro(true,true);
  }
}
?>
<script>
js_tabulacaoforms("form1","m38_visualizacaoitens",true,1,"m38_visualizacaoitens",true);
</script>