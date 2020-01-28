<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_db_docparag_classe.php"));
include(modification("classes/db_matrequi_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$cldb_docparag = new cl_db_docparag;
$clmatrequi = new cl_matrequi;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $result_ord=$cldb_docparag->sql_record($cldb_docparag->sql_query_file($db03_docum,null,"max(db04_ordem)as ordem"));
  if ($cldb_docparag->numrows>0){
  	db_fieldsmemory($result_ord,0);
  	$ordem=$ordem+1;
  }else{
  	$ordem=1;
  }
  $cldb_docparag->db04_docum = $db03_docum;
  $cldb_docparag->db04_idparag = $db02_idparag;
  $cldb_docparag->db04_ordem = $ordem;
  $cldb_docparag->incluir($db03_docum,$db02_idparag);
  $erro_msg=$cldb_docparag->erro_msg;
  if ($cldb_docparag->erro_status==0){
    $sqlerro=true;
  }else{
   $db02_idparag="";
   $db02_descr="";
  }
  db_fim_transacao($sqlerro);
}else if(isset($alterar)){
	
}else if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_docparag->db04_docum=$db03_docum;
  $cldb_docparag->db04_idparag = $db02_idparag;
  $cldb_docparag->excluir($db03_docum,$db02_idparag);
  $erro_msg=$cldb_docparag->erro_msg;
  if ($cldb_docparag->erro_status==0){
    $sqlerro=true;
  }else{
   $db02_idparag="";
   $db02_descr="";
  }
  db_fim_transacao($sqlerro);
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
<br><br>

  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include(modification("forms/db_frmparagdoc.php"));
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
//if(isset($incluir) || isset($alterar) || isset($excluir)){
if(isset($incluir) ||  isset($excluir)){
  if($sqlerro==true){
    $cldb_docparag->erro(true,false);
    if($cldb_docparag->erro_campo!=""){
      echo "<script> parent.document.form1.".$cldb_docparag->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$cldb_docparag->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "<script>
               parent.iframe_parag.location.href='con4_docparag003.php?db03_docum=".@$db03_docum."';\n
	 </script>";

  }
}  
?>