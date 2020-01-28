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
include("classes/db_rhinssoutros_classe.php");
include("classes/db_rhpessoal_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhinssoutros = new cl_rhinssoutros;
$clrhpessoal = new cl_rhpessoal;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro = false;
  db_inicio_transacao();
  $clrhinssoutros->incluir($rh51_seqpes);
  $erro_msg = $clrhinssoutros->erro_msg;
  if($clrhinssoutros->erro_status=="0"){
    $sqlerro = true;
  }
  db_fim_transacao();
}else if(isset($alterar)){
  $db_opcao = 22;
  $sqlerro = false;
  db_inicio_transacao();
  if(trim($rh51_ocorre) == ""){
    $rh51_ocorre = "  ";
  }
  $clrhinssoutros->rh51_ocorre = $rh51_ocorre;
  $clrhinssoutros->rh51_seqpes = $rh51_seqpes;
  $clrhinssoutros->alterar($rh51_seqpes);
  $erro_msg = $clrhinssoutros->erro_msg;
  if($clrhinssoutros->erro_status=="0"){
    $sqlerro = true;
  }
  db_fim_transacao();
}else if(isset($excluir)){
  $db_opcao = 33;
  $sqlerro = false;
  db_inicio_transacao();
  $clrhinssoutros->excluir($rh51_seqpes);
  $erro_msg = $clrhinssoutros->erro_msg;
  if($clrhinssoutros->erro_status=="0"){
    $sqlerro = true;
  }
  db_fim_transacao();
}else if(isset($chavepesquisa)){
  $result = $clrhpessoal->sql_record($clrhpessoal->sql_query_rescisao(null,"rh01_regist,z01_nome,rh02_seqpes as rh51_seqpes,rh05_recis","","rh01_regist = ".$chavepesquisa." and rh02_anousu = ".db_anofolha()." and rh02_mesusu = ".db_mesfolha()));
  db_fieldsmemory($result, 0);
  $result = $clrhinssoutros->sql_record($clrhinssoutros->sql_query_file($rh51_seqpes)); 
  if($clrhinssoutros->numrows > 0){
    db_fieldsmemory($result,0);
    $db_botao = true;
    $db_opcao = 2;
  }
  // if(trim($rh05_recis) != ""){
  //  $sqlerro  = false;
  //  $erro_msg = "Funcionário com contrato rescindido. Verifique.";
  // }
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmrhinssoutros.php");
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
<script>
js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
</script>
<?
// if(isset($incluir) || isset($alterar) || isset($excluir) || (isset($rh05_recis) && trim($rh05_recis) != "")){
if(isset($incluir) || isset($alterar) || isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro == true){
    if($clrhinssoutros->erro_campo!=""){
      echo "<script> document.form1.".$clrhinssoutros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhinssoutros->erro_campo.".focus();</script>";
    }
  }else{
    echo "
          <script>
	    location.href = 'pes1_rhinssoutros001.php';
	  </script>
         ";
  }
}
?>