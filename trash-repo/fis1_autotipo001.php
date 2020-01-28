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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_autotipo_classe.php");
include("classes/db_autoandam_classe.php");
include("classes/db_autoultandam_classe.php");
include("classes/db_fandam_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_fiscalprocrec_classe.php");
db_postmemory($HTTP_POST_VARS);
$clautotipo  = new cl_autotipo;
$clautoandam = new cl_autoandam;
$clfandam         = new cl_fandam;
$clautoultandam = new cl_autoultandam;
$clfiscalprocrec = new cl_fiscalprocrec;
$db_opcao = 1;
$db_botao = true;
global $y59_codauto;
global $y39_codandam;
$y59_codauto = @$y50_codauto;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  if ($y59_fator==""){
    $clautotipo->y59_fator='0';  
  } 
  //db_msgbox($y59_valor);
  if (strpos(trim($y59_valor),',')!=""){
	  $y59_valor=str_replace('.','',$y59_valor);
	  $y59_valor=str_replace(',','.',$y59_valor);
  } 
  $clautotipo->y59_valor = "$y59_valor";
  $clautotipo->y59_codauto=$y59_codauto;
  $clautotipo->y59_codtipo=$y59_codtipo;
  $clautotipo->incluir(null);
  if($clautotipo->erro_status != 0 && $clautotipo->numrows <= 1){
      $clfandam->y39_codtipo = $andamento;
      $clfandam->y39_obs="0";
      $clfandam->y39_id_usuario= db_getsession("DB_id_usuario");
      $clfandam->y39_data=date("Y-m-d",db_getsession("DB_datausu"));
      $clfandam->y39_hora=db_hora();
      $clfandam->incluir("");
      $clautoultandam->y16_codauto = $y59_codauto;
      $clautoultandam->y16_codandam = $clfandam->y39_codandam;
      $clautoultandam->incluir($y59_codauto,$clfandam->y39_codandam);
      $clautoandam->y58_codauto = $y59_codauto;
      $clautoandam->y58_codandam = $clfandam->y39_codandam;
      $clautoandam->incluir($y59_codauto,$clfandam->y39_codandam);
  }
  db_fim_transacao();
}elseif(isset($andamento) && $andamento != ""){
  db_inicio_transacao();
  $result = $clautoultandam->sql_record($clautoultandam->sql_query_file("",""," max(y16_codandam) as y16_codandam ",""," y16_codauto = $y59_codauto and y50_instit = ".db_getsession('DB_instit') ));
  if($clautoultandam->numrows > 0){
    db_fieldsmemory($result,0);
    $clfandam->y39_codtipo = $andamento;
    $clfandam->y39_obs="0";
    $clfandam->y39_id_usuario= db_getsession("DB_id_usuario");
    $clfandam->y39_data=date("Y-m-d",db_getsession("DB_datausu"));
    $clfandam->y39_hora=db_hora();
    $clfandam->y39_codandam = $y16_codandam;
    $clfandam->alterar("");
  }else{
    $clfandam->y39_codtipo = $andamento;
    $clfandam->y39_obs="0";
    $clfandam->y39_id_usuario= db_getsession("DB_id_usuario");
    $clfandam->y39_data=date("Y-m-d",db_getsession("DB_datausu"));
    $clfandam->y39_hora=db_hora();
    $clfandam->incluir("");
    $clautoultandam->y16_codauto = $y59_codauto;
    $clautoultandam->y16_codandam = $clfandam->y39_codandam;
    $clautoultandam->incluir($y59_codauto,$clfandam->y39_codandam);
    $clautoandam->y58_codauto = $y59_codauto;
    $clautoandam->y58_codandam = $clfandam->y39_codandam;
    $clautoandam->incluir($y59_codauto,$clfandam->y39_codandam);
  }
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <br>
	<?
	include("forms/db_frmautotipo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_setatabulacao();
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clautotipo->erro_status=="0"){
    $clautotipo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautotipo->erro_campo!=""){
      echo "<script> document.form1.".$clautotipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautotipo->erro_campo.".focus();</script>";
    };
  }else{
    $clautotipo->erro(true,false);
    echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&y39_codandam=".$clfandam->y39_codandam."&abas=1';</script>\n";
  };
};
?>