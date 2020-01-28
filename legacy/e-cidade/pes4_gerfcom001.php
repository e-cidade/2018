<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_gerfcom_classe.php");
require_once("dbforms/db_funcoes.php");


if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {

  require_once("pes4_fechamentocomplementar001.php");
  exit;
}

db_postmemory($HTTP_POST_VARS);
$clgerfcom  = new cl_gerfcom;
$db_opcao   = 1;
$db_botao   = true;

$r48_anousu = db_anofolha();
$r48_mesusu = db_mesfolha();

if(isset($incluir)){

  $result_semest  = $clgerfcom->sql_record($clgerfcom->sql_query_file($r48_anousu, $r48_mesusu, null, null," r48_regist, r48_rubric",""," r48_anousu = ".$r48_anousu." and r48_mesusu = ".$r48_mesusu." and r48_semest = 0 "));
  $numrows_result = $clgerfcom->numrows;
  db_inicio_transacao();
  for($i=0; $i<$numrows_result; $i++){
    db_fieldsmemory($result_semest, $i);
    $clgerfcom->r48_mesusu = $r48_mesusu;
    $clgerfcom->r48_anousu = $r48_anousu;
    $clgerfcom->r48_regist = $r48_regist;
    $clgerfcom->r48_rubric = $r48_rubric;
    $clgerfcom->r48_semest = $r48_semest;
    $clgerfcom->alterar($r48_anousu,$r48_mesusu,$r48_regist,$r48_rubric);
    if($clgerfcom->erro_status == "0"){
      break;
    }
  }
  db_fim_transacao();
}else{
  $semestatual = "0";
  $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($r48_anousu, $r48_mesusu, null, null," max(r48_semest) as semestatual"));
  if($clgerfcom->numrows > 0){
    db_fieldsmemory($result_semest, 0);
  }
  $r48_semest = $semestatual  + 1;
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
      include("forms/db_frmgerfcom.php");
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
js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
</script>
<?
if(isset($incluir)){
  if($clgerfcom->erro_status=="0"){
    $clgerfcom->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clgerfcom->erro_campo!=""){
      echo "<script> document.form1.".$clgerfcom->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clgerfcom->erro_campo.".focus();</script>";
    }
  }else{
    $clgerfcom->erro(true,true);
  }
}
?>
