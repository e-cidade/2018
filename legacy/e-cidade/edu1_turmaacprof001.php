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
include("classes/db_turmaacprof_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clturmaacprof = new cl_turmaacprof;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clturmaacprof->incluir($ed222_i_codigo);
  db_fim_transacao();
}
if(isset($alterar)){
 $db_opcao = 3;
 db_inicio_transacao();
 $clturmaacprof->alterar($ed222_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 db_inicio_transacao();
 $clturmaacprof->excluir($ed222_i_codigo);
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
<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset style="width:95%"><legend><b>Inclusão Profissional/Monitor</b></legend>
	<?include("forms/db_frmturmaacprof.php");?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed222_i_turmaac",true,1,"ed222_i_turmaac",true);
</script>
<?
if(isset($incluir)){
  if($clturmaacprof->erro_status=="0"){
    $clturmaacprof->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clturmaacprof->erro_campo!=""){
      echo "<script> document.form1.".$clturmaacprof->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturmaacprof->erro_campo.".focus();</script>";
    }
  }else{
    $clturmaacprof->erro(true,false);
     db_redireciona("edu1_turmaacprof001.php?ed222_i_turmaac=$ed222_i_turmaac&ed268_c_descr=$ed268_c_descr");
  }
}
if(isset($alterar)){
  if($clturmaacprof->erro_status=="0"){
    $clturmaacprof->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clturmaacprof->erro_campo!=""){
      echo "<script> document.form1.".$clturmaacprof->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturmaacprof->erro_campo.".focus();</script>";
    }
  }else{
    $clturmaacprof->erro(true,false);?>
    <? db_redireciona("edu1_turmaacprof001.php?ed222_i_turmaac=$ed222_i_turmaac&ed268_c_descr=$ed268_c_descr");
  }
}
if(isset($excluir)){
  if($clturmaacprof->erro_status=="0"){
    $clturmaacprof->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clturmaacprof->erro_campo!=""){
      echo "<script> document.form1.".$clturmaacprof->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturmaacprof->erro_campo.".focus();</script>";
    }
  }else{
    $clturmaacprof->erro(true,false);
     db_redireciona("edu1_turmaacprof001.php?ed222_i_turmaac=$ed222_i_turmaac&ed268_c_descr=$ed268_c_descr");
  }
}
if(isset($cancelar)){
  db_redireciona("edu1_turmaacprof001.php?ed222_i_turmaac=$ed222_i_turmaac&ed268_c_descr=$ed268_c_descr");
}
?>