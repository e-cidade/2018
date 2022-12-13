<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include ("classes/db_conplanoreduz_classe.php");
include ("classes/db_conplanoorcamentoanalitica_classe.php");
include("classes/db_orcreceita_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcfontes_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplanoorcamentoanalitica = new cl_conplanoorcamentoanalitica;
$clorcreceita    = new cl_orcreceita;
$clorcfontes     = new cl_orcfontes;
$clorcparametro  = new cl_orcparametro;
$clestrutura     = new cl_estrutura;
if(isset($atualizar)){
  $db_opcao = 2;
  $db_botao = true;
}else{  
  $db_opcao = 22;
  $db_botao = false;
} 
if(isset($alterar)) {

  db_inicio_transacao();
  $sqlerro=false;
  $db_opcao = 2;
  $result=$clorcfontes->sql_record($clorcfontes->sql_query_file(null,
  		                                                          null,
  		                                                          "o57_codfon as o70_codfon",
  		                                                          "",
  		                                                          "o57_fonte='".str_replace(".","",$o50_estrutreceita)."' 
  		                                                           and o57_anousu = ".db_getsession("DB_anousu")));
  if($clorcfontes->numrows>0){ 
    db_fieldsmemory($result,0);
    $result = $clorcreceita->sql_record($clorcreceita->sql_query_file(null,
    		                                                              null,
    		                                                              "o70_codrec as codrec",
    		                                                              "",
    		                                                              "o70_anousu=".db_getsession('DB_anousu')." 
    		                                                               and o70_codfon = {$o70_codfon} 
    		                                                               and o70_concarpeculiar = '{$o70_concarpeculiar}'"
    		                                                               ));
    db_fieldsmemory($result,0);
    if($clorcreceita->numrows>0 && $codrec!=$o70_codrec){
      $erro_msg="O código da fonte já foi cadastrado!";
      $clorcreceita->erro_status='0';
      $sqlerro=true;
    }else{ 
      $clorcreceita->o70_codfon=$o70_codfon;
      $clorcreceita->alterar($o70_anousu,$o70_codrec);
      if($clorcreceita->erro_status==0){
	       $sqlerro=true;
      }
	$erro_msg=$clorcreceita->erro_msg;
      }
  }else{
    $sqlerro=true;
    $clorcreceita->erro_status='0';
    $erro_msg="Verifique o código da fonte!";
  } 
  
// Faz update no recurso da conta conforme previsao
  if($sqlerro==false) {  	  
      $rs_conplanoreduz = $clconplanoorcamentoanalitica->sql_record($clconplanoorcamentoanalitica->sql_query_file(null,null,"c61_reduz,c61_anousu","c61_codcon","c61_codcon=$o70_codfon and c61_instit=".db_getsession("DB_instit")));
      $rows             = $clconplanoorcamentoanalitica->numrows;
	  if($rows>0) {	  	 
	  	  for($x=0;$x<$rows;$x++){	  	 	 
		      db_fieldsmemory($rs_conplanoreduz,$x);
		      $clconplanoorcamentoanalitica->c61_instit = db_getsession("DB_instit");	
	  	      $clconplanoorcamentoanalitica->c61_codigo = $o70_codigo;
	  	      $clconplanoorcamentoanalitica->c61_anousu = $c61_anousu;
	  	      $clconplanoorcamentoanalitica->c61_reduz = $c61_reduz;
	  	      $clconplanoorcamentoanalitica->alterar($c61_reduz,$c61_anousu);
	  	      if($clconplanoorcamentoanalitica->erro_status==0){
	              $sqlerro=true;
	              $erro_msg=$clconplanoorcamentoanalitica->erro_msg;
              }      
	  	  }
	  }	  	  
  }  
  $db_botao = true;
  $db_opcao=2;  
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clorcreceita->sql_record($clorcreceita->sql_query($chavepesquisa,$chavepesquisa1)); 
   db_fieldsmemory($result,0);

   $result=$clorcfontes->sql_record($clorcfontes->sql_query_file(null,null,"o57_fonte as o50_estrutreceita",'',"o57_codfon='$o70_codfon' and o57_anousu = ".db_getsession("DB_anousu")));
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcreceita.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clorcreceita->erro_campo!=""){
      echo "<script> document.form1.".$clorcreceita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcreceita->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    db_redireciona("orc1_orcreceita002.php");
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>