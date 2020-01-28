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
include("classes/db_pctipoelemento_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;


db_postmemory($HTTP_POST_VARS);
$clpctipoelemento = new cl_pctipoelemento;
if(isset($incluir)){
  db_inicio_transacao();
  $clpctipoelemento->incluir($pc06_codtipo,$pc06_codele);
  db_fim_transacao();
}else if(isset($excluir)){
  db_inicio_transacao();
  $clpctipoelemento->pc06_codtipo=$pc06_codtipo;
  $clpctipoelemento->pc06_codele=$pc06_codele;
  $clpctipoelemento->excluir($pc06_codtipo,$pc06_codele);
  db_fim_transacao();
}elseif(isset($opcao)){
  $result = $clpctipoelemento->sql_record($clpctipoelemento->sql_query(null,null,"pc06_codtipo,o56_elemento,pc06_codele,o56_descr","pc06_codele","pc06_codtipo=$pc06_codtipo and pc06_codele=$pc06_codele and o56_anousu=".db_getsession("DB_anousu")));
  db_fieldsmemory($result,0);
}  
  


if(isset($opcao) && $opcao=="alterar"){
      $db_opcao = 2;
       $db_botao=true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
      $db_opcao = 3;
          $db_botao=true;
}else{
      $db_opcao = 1;
          $db_botao=true;
}

if((isset($novo) && $novo=="ok")|| (isset($sqlerro) && $sqlerro==false)){
  
  $pc06_codtipo='';  
  $pc06_codele='';  
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
	include("forms/db_frmpctipoelemento.php");
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clpctipoelemento->erro_status=="0"){
    $clpctipoelemento->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpctipoelemento->erro_campo!=""){
      echo "<script> document.form1.".$clpctipoelemento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpctipoelemento->erro_campo.".focus();</script>";
    };
  }else{
    $clpctipoelemento->erro(true,true);
  };
};
?>