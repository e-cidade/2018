<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_baseato_classe.php");
require_once("classes/db_baseatoserie_classe.php");
require_once("classes/db_escolabase_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$clbaseato = new cl_baseato;
$clbaseatoserie = new cl_baseatoserie;
$clescolabase = new cl_escolabase;
$db_opcao = 1;
$db_botao = false;

$ed77_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");

$sCampos       = " cursoedu.ed29_i_codigo as codcursobase, ed77_i_codigo as codbaseescola ";
$sWhere        = " ed77_i_base = $ed77_i_base and ed77_i_escola = $ed77_i_escola ";
$sSql = $clescolabase->sql_query("", $sCampos, "", $sWhere);
$result_ver = $clescolabase->sql_record($sSql);

if ($clescolabase->numrows > 0) {
  db_fieldsmemory($result_ver, 0);
}

if (isset($incluir)) {
	
  db_inicio_transacao();
  
  $clbaseato->ed278_i_escolabase = $codbaseescola;
  $clbaseato->incluir($ed278_i_codigo);
  $codbaseato = $clbaseato->ed278_i_codigo;
  
  for ($t = 0; $t < count($ed279_i_serie); $t++) {
    
  	$clbaseatoserie->ed279_i_baseato = $codbaseato;
    $clbaseatoserie->ed279_i_serie = $ed279_i_serie[$t];
    $clbaseatoserie->incluir(null);
  
  }
  
  db_fim_transacao();
  
  $db_botao = false;
  
}

if (isset($alterar)) {
	
  $db_opcao = 2;
  db_inicio_transacao();
  $clbaseatoserie->excluir(""," ed279_i_baseato = $ed278_i_codigo");
  
  for ($t = 0; $t < count($ed279_i_serie); $t++) {
  	
    $clbaseatoserie->ed279_i_baseato = $ed278_i_codigo;
    $clbaseatoserie->ed279_i_serie = $ed279_i_serie[$t];
    $clbaseatoserie->incluir(null);
   
  }
  
  $clbaseato->erro_msg = "Altera��o efetuada com Sucesso";
  $clbaseato->erro_status = "1";
  
  db_fim_transacao();

}

if (isset($excluir)) {
	
  $db_opcao = 3;
  
  db_inicio_transacao();
  
  $clbaseatoserie->excluir("", " ed279_i_baseato = $ed278_i_codigo");
  $clbaseato->excluir($ed278_i_codigo);
  
  db_fim_transacao();

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Atos Legais que regulamentam esta base curricular na escola <?=$ed18_c_nome?></b></legend>
    <?include("forms/db_frmbaseato.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed278_i_atolegal",true,1,"ed278_i_atolegal",true);
</script>
<?
if(isset($incluir)){
  if($clbaseato->erro_status=="0"){
    $clbaseato->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clbaseato->erro_campo!=""){
      echo "<script> document.form1.".$clbaseato->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbaseato->erro_campo.".focus();</script>";
    }
  }else{
    $clbaseato->erro(true,false);
    db_redireciona("edu1_baseato001.php?ed77_i_base=$ed77_i_base&ed31_c_descr=$ed31_c_descr");
  }
}
if(isset($alterar)){
 if($clbaseato->erro_status=="0"){
  $clbaseato->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbaseato->erro_campo!=""){
   echo "<script> document.form1.".$clbaseato->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbaseato->erro_campo.".focus();</script>";
  }
 }else{
  $clbaseato->erro(true,false);
  db_redireciona("edu1_baseato001.php?ed77_i_base=$ed77_i_base&ed31_c_descr=$ed31_c_descr");
 }
}
if(isset($excluir)){
 if($clbaseato->erro_status=="0"){
  $clbaseato->erro(true,false);
 }else{
  $clbaseato->erro(true,false);
  db_redireciona("edu1_baseato001.php?ed77_i_base=$ed77_i_base&ed31_c_descr=$ed31_c_descr");
 }
}
if(isset($cancelar)){
  db_redireciona("edu1_baseato001.php?ed77_i_base=$ed77_i_base&ed31_c_descr=$ed31_c_descr");
}
?>