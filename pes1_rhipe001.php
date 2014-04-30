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
include("classes/db_rhipe_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhiperegist_classe.php");
include("classes/db_rhipenumcgm_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhipe = new cl_rhipe;
$clcgm = new cl_cgm;
$clrhpessoal = new cl_rhpessoal;
$clrhiperegist = new cl_rhiperegist;
$clrhipenumcgm = new cl_rhipenumcgm;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
	$clrhipe->rh14_instit = db_getsession("DB_instit");
  $clrhipe->incluir(null);
  $rh14_sequencia = $clrhipe->rh14_sequencia;
  $erro_msg = $clrhipe->erro_msg;
  if($clrhipe->erro_status==0){
    $sqlerro=true;
  }
  if($sqlerro == false && trim($rh62_regist) != ""){
    $clrhiperegist->incluir($rh14_sequencia);
    if($clrhiperegist->erro_status==0){
      $erro_msg = $clrhiperegist->erro_msg;
      $sqlerro=true;
    }else{
      $result_numcgm = $clrhpessoal->sql_record($clrhpessoal->sql_query_file($rh62_regist, "rh01_numcgm  as rh63_numcgm"));
      if($clrhpessoal->numrows > 0){
        db_fieldsmemory($result_numcgm, 0);
      }
    }
  }
  if($sqlerro == false && trim($rh63_numcgm) != ""){
    $clrhipenumcgm->rh63_numcgm = $rh63_numcgm;
    $clrhipenumcgm->incluir($rh14_sequencia);
    if($clrhipenumcgm->erro_status==0){
      $erro_msg = $clrhipenumcgm->erro_msg;
      $sqlerro=true;
    }
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
	include("forms/db_frmrhipe.php");
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
js_tabulacaoforms("form1","rh62_regist",true,1,"rh62_regist",true);
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro == false){
    db_redireciona("pes1_rhipe001.php");
  }
}
?>