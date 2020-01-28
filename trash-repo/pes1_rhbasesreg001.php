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
include("classes/db_rhbasesreg_classe.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhbasesreg = new cl_rhbasesreg;
$clrhrubricas = new cl_rhrubricas;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrhbasesreg->excluir(null," rh54_regist = ".$rh54_regist." and rh54_base = '".$rh54_base."'");
  if($clrhbasesreg->erro_status != 0 && isset($simsel) && count($simsel) > 0){
    for($i=0; $i<count($simsel); $i++){
      $clrhbasesreg->rh54_base   = $rh54_base;
      $clrhbasesreg->rh54_regist = $rh54_regist;
      $clrhbasesreg->rh54_rubric = $simsel[$i];
      $clrhbasesreg->incluir(null);
      if($clrhbasesreg->erro_status == 0){
	$sqlerro = true;
	break;
      }
    }
  }
  if($sqlerro == false){
    unset($rh54_base, $rh32_descr);
  }
  db_fim_transacao();
}else if(isset($opcao)){
  $result_basesreg = $clrhbasesreg->sql_record($clrhbasesreg->sql_query_dadosprinc(null, " rh54_regist, z01_nome, rh54_base, rh32_descr ", "", " rh54_base = '".$rh54_base."' and rh54_regist = ".$rh54_regist));
  if($clrhbasesreg->numrows > 0){
    db_fieldsmemory($result_basesreg, 0);
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
      include("forms/db_frmrhbasesreg.php");
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
<?if(!isset($rh54_regist) || (isset($rh54_regist) && trim($rh54_regist) == "")){?>
js_tabulacaoforms("form1","rh54_regist",true,1,"rh54_regist",true);
<?}else{?>
js_tabulacaoforms("form1","rh54_base",true,1,"rh54_base",true);
<?}?>
</script>
<?
if(isset($incluir)){
  if($clrhbasesreg->erro_status=="0"){
    $clrhbasesreg->erro(true,false);
    $db_botao=true;
    if($clrhbasesreg->erro_campo!=""){
      echo "<script> document.form1.".$clrhbasesreg->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhbasesreg->erro_campo.".focus();</script>";
    }
  }else{
    $clrhbasesreg->erro(true,false);
    echo "
          <script>
//	    location.href = 'pes1_rhbasesreg001.php?rh54_regist=".$rh54_regist."';
          </script>
         ";
  }
}
?>