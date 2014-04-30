<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_far_listaprescricao_classe.php");
include("classes/db_far_listacontrolado_classe.php");
include("classes/db_far_prescricaomedica_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$clfar_listaprescricao = new cl_far_listaprescricao;
$clfar_listacontrolado = new cl_far_listacontrolado;
$clfar_prescricaomedica = new cl_far_prescricaomedica;
$op=1;
$db_opcao = 1;
$db_botao = true;
if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao = $opcao=="alterar"?2:3;	
	$result = $clfar_listaprescricao->sql_record($clfar_listaprescricao->sql_query($fa21_i_codigo));
	if( $clfar_listaprescricao->numrows > 0 ){
		db_fieldsmemory($result,0);		
	}
}
if(isset($incluir)){
  db_inicio_transacao();  
  $clfar_listaprescricao->fa21_i_listacontrolado=$fa21_i_listacontrolado; 
  $clfar_listaprescricao->incluir(null);
  db_fim_transacao();
}else if(isset($alterar)){
     db_inicio_transacao();
     $clfar_listaprescricao->alterar($fa21_i_codigo);
     db_fim_transacao();
}else if(isset($excluir)){
     db_inicio_transacao();
     $clfar_listaprescricao->excluir($fa21_i_codigo);
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
<br><br><br>
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <fieldset style="width:100%"><legend><b>Inclusão de Lista Modelo</b></legend>
	<?
	include("forms/db_frmfar_listaprescricao.php");
	?>
    </fieldset>
	</td>
  </tr>
</table>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","fa21_i_prescricaomedica",true,1,"fa21_i_prescricaomedica",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
  if($clfar_listaprescricao->erro_status=="0"){
    $clfar_listaprescricao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfar_listaprescricao->erro_campo!=""){
      echo "<script> document.form1.".$clfar_listaprescricao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfar_listaprescricao->erro_campo.".focus();</script>";
    }
  }else{
    $clfar_listaprescricao->erro(true,false);
	db_redireciona("far1_far_listaprescricao001.php?op=$op&fa20_c_prescricao=$fa20_c_prescricao&fa21_i_prescricaomedica=$fa21_i_prescricaomedica");
  }
}

if(isset($excluir)){
 if($clfar_listaprescricao->erro_status=="0"){
  $clfar_listaprescricao->erro(true,false);
 }else{
  $clfar_listaprescricao->erro(true,false);
    db_redireciona("far1_far_listaprescricao001.php?op=$op&fa20_c_prescricao=$fa20_c_prescricao&fa21_i_prescricaomedica=$fa21_i_prescricaomedica");
 }
}
?>