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
include("libs/db_utils.php");
include("classes/db_admissao_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhparam_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$cladmissao  = new cl_admissao;
$clrhpessoal = new cl_rhpessoal;
$clrhparam   = new cl_rhparam;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $cladmissao->incluir($h07_regist);
  db_fim_transacao();
}else if(isset($h07_regist) && trim($h07_regist) != ""){
  $result_funcao = $clrhpessoal->sql_record($clrhpessoal->sql_query_cargo($h07_regist, " rh37_funcao, rh37_descr as rh37_descr2 "));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_funcao, 0);
  }
}

 $rsConsultaModelo = $clrhparam->sql_record($clrhparam->sql_query_file(null,"h36_modtermoposse",null,"h36_instit = ".db_getsession("DB_instit")));
 
 if($clrhparam->numrows > 0){
 	$oParam  = db_utils::fieldsMemory($rsConsultaModelo,0);
 	$modeloposse = $oParam->h36_modtermoposse;
 }
 

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/geradorrelatorios.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/libJsonJs.js"></script>

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
<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmadmissao.php");
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
js_tabulacaoforms("form1","h07_regist",true,1,"h07_regist",true);
</script>
<?
if(isset($incluir)){
  if($cladmissao->erro_status=="0"){
    $cladmissao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cladmissao->erro_campo!=""){
      echo "<script> document.form1.".$cladmissao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cladmissao->erro_campo.".focus();</script>";
    }
  }else{
    $cladmissao->erro(true,false);
    echo "<script> document.form1.incluir.disabled  = true;</script>";
    echo "<script> document.form1.imprimir.disabled = false;</script>";
  }
}
?>