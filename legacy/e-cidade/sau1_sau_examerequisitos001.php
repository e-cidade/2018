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
include("classes/db_sau_examerequisitos_classe.php");
include("classes/db_sau_exames_classe.php");
include("classes/db_sau_requisitos_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clsau_examerequisitos = new cl_sau_examerequisitos;
$clsau_exames = new cl_sau_exames;
$clsau_requisitos = new cl_sau_requisitos;
$db_opcao = 1;
$db_botao = true;


if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao = $opcao=="alterar"?2:3;	
	$result = $clsau_examerequisitos->sql_record($clsau_examerequisitos->sql_query($s109_i_codigo));
	if( $clsau_examerequisitos->numrows > 0 ){
		db_fieldsmemory($result,0);		
	}
}
if(isset($incluir)){
  db_inicio_transacao();  
  $clsau_examerequisitos->s109_i_requisito=$s109_i_requisito; 
  $clsau_examerequisitos->incluir(null);
  db_fim_transacao();
}else if(isset($alterar)){
     db_inicio_transacao();
     $clsau_examerequisitos->alterar($s109_i_codigo);
     db_fim_transacao();
}else if(isset($excluir)){
     db_inicio_transacao();
     $clsau_examerequisitos->excluir($s109_i_codigo);
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
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <fieldset style="width:95%"><legend><b>Inclusão de Exames Requisitos</b></legend>
	<? include("forms/db_frmsau_examerequisitos.php");?>
	</fieldset>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","s109_i_requisito",true,1,"s109_i_requisito",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
  if($clsau_examerequisitos->erro_status=="0"){
    $clsau_examerequisitos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsau_examerequisitos->erro_campo!=""){
      echo "<script> document.form1.".$clsau_examerequisitos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsau_examerequisitos->erro_campo.".focus();</script>";
    }
  }else{
    $clsau_examerequisitos->erro(true,false);
	db_redireciona("sau1_sau_examerequisitos001.php?op=$op&s108_c_exame=$s108_c_exame&s109_i_exame=$s109_i_exame");
  }
}

if(isset($excluir)){
 if($clsau_examerequisitos->erro_status=="0"){
  $clsau_examerequisitos->erro(true,false);
 }else{
  $clsau_examerequisitos->erro(true,false);
    db_redireciona("sau1_sau_examerequisitos001.php?op=$op&s108_c_exame=$s108_c_exame&s109_i_exame=$s109_i_exame");
 }
}
?>