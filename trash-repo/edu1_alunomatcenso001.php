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
include("classes/db_alunomatcenso_classe.php");
include("classes/db_aluno_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clalunomatcenso = new cl_alunomatcenso;
$claluno = new cl_aluno;
$db_opcao = 1;
$db_botao = true;
$result_inep = $claluno->sql_record($claluno->sql_query("","ed47_c_codigoinep",""," ed47_i_codigo = $ed280_i_aluno"));
db_fieldsmemory($result_inep,0);
if(isset($incluir)){
 db_inicio_transacao();
 $clalunomatcenso->incluir($ed280_i_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clalunomatcenso->alterar($ed280_i_codigo);
  db_fim_transacao();
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clalunomatcenso->excluir($ed280_i_codigo);
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Matrícula INEP</b></legend>
	<?
	include("forms/db_frmalunomatcenso.php");
	?>
    </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed280_i_turmacenso",true,1,"ed280_i_turmacenso",true);
</script>
<?
if(isset($incluir)){
 if($clalunomatcenso->erro_status=="0"){
  $clalunomatcenso->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clalunomatcenso->erro_campo!=""){
   echo "<script> document.form1.".$clalunomatcenso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clalunomatcenso->erro_campo.".focus();</script>";
  }
 }else{ 	
  $clalunomatcenso->erro(true,false);  
  db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
 }
}
if(isset($alterar)){
  if($clalunomatcenso->erro_status=="0"){
    $clalunomatcenso->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clalunomatcenso->erro_campo!=""){
      echo "<script> document.form1.".$clalunomatcenso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clalunomatcenso->erro_campo.".focus();</script>";
    }
  }else{
    $clalunomatcenso->erro(true,false);
    db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
  }
}
if(isset($excluir)){
  if($clalunomatcenso->erro_status=="0"){
    $clalunomatcenso->erro(true,false);
  }else{
    $clalunomatcenso->erro(true,false);
    db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
  }
}
if(isset($cancelar)){
    db_redireciona("edu1_alunomatcenso001.php?ed280_i_aluno=$ed280_i_aluno&ed47_v_nome=$ed47_v_nome");
}
?>