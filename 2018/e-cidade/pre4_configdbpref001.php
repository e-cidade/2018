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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_configdbpref_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libdicionario.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

$clconfigdbpref = new cl_configdbpref;
$db_botao = true;
$db_opcao = 2; 


if(isset($alterar)){
	
  db_inicio_transacao();

  $clconfigdbpref->alterar($w13_instit);
  db_fim_transacao();
  
}else if(isset($incluir)){

  db_inicio_transacao();	
	
  $clconfigdbpref->incluir($w13_instit);

  db_fim_transacao();	  
  
}else{
 
  $rsConsultaConfig = $clconfigdbpref->sql_record($clconfigdbpref->sql_query(db_getsession('DB_instit')));

  if ( $clconfigdbpref->numrows > 0 ) {
    db_fieldsmemory($rsConsultaConfig,0);
  } else {
  	$w13_instit = db_getsession('DB_instit');
	$db_opcao   = 1;
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

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table style="padding-top:15px;">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmconfigdbpref.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar) || isset($incluir)){
  if($clconfigdbpref->erro_status=="0"){
    $clconfigdbpref->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clconfigdbpref->erro_campo!=""){
      echo "<script> document.form1.".$clconfigdbpref->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconfigdbpref->erro_campo.".focus();</script>";
    }
  }else{
    $clconfigdbpref->erro(true,true);
  }
}

?>
<script>
js_tabulacaoforms("form1","w13_liberapedsenha",true,1,"w13_liberapedsenha",true);
</script>