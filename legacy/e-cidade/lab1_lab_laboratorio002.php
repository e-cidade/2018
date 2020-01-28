<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_lab_labresp_classe.php");
include("classes/db_lab_turnohora_classe.php");
include("classes/db_lab_laboratorio_classe.php");
include("classes/db_lab_labdepart_classe.php");
include("classes/db_lab_labcgm_classe.php");
include("classes/db_lab_labusuario_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllab_laboratorio = new cl_lab_laboratorio;
$cllab_labresp = new cl_lab_labresp;
$cllab_turnohora = new cl_lab_turnohora;
$cllab_labdepart = new cl_lab_labdepart;
$cllab_labcgm = new cl_lab_labcgm;
$cllab_labusuario = new cl_lab_labusuario;

$db_opcao = 22;
$db_botao = false;
$iBloqueioTipo=1;
if(isset($alterar)){

  /* Verifico se existe algum usuario cadastrado neste laboratorio. Se tiver, o tipo nao pode ser alterado para interno */
  $cllab_labusuario->sql_record($cllab_labusuario->sql_query("","*",""," la05_i_laboratorio = $la02_i_codigo "));
  if($cllab_labusuario->numrows>0) { 
    $iBloqueioTipo = 3;
  }

  db_inicio_transacao();
  $db_opcao = 2;
  $iRows = 0;

   //tipo interno
   if($la02_i_tipo == 1) {

     if($iBloqueioTipo == 3) {

       db_msgbox('Impossivel alterar tipo do laboratorio enquanto houver usuario cadastrados');
       db_fim_transacao(true);
       db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa=$la02_i_codigo");

     }
     
     /* Verifico se ja existe algum registro na tabela lab_depart. Se sim, eu altero, senao eu incluo */
     $sSql = $cllab_labdepart->sql_query(null, '*', null, " la03_i_laboratorio = $la02_i_codigo ");
     $rsTmp = $cllab_labdepart->sql_record($sSql);
     if($cllab_labdepart->numrows > 0) {

       db_fieldsmemory($rsTmp, 0);
       $cllab_labdepart->la03_i_codigo = $la03_i_codigo;
       $cllab_labdepart->alterar($la03_i_codigo);
       $iRows = $cllab_labdepart->numrows;

     } else {

       $cllab_labdepart->la03_i_laboratorio = $la02_i_codigo;
       $cllab_labdepart->incluir(null);
       $iRows = $cllab_labdepart->numrows;

     }
     $cllab_labcgm->excluir(null, " la04_i_laboratorio = $la02_i_codigo "); // excluo algum registro que possa ter na lab_labcgm

   } else { // tipo externo
 
     $sSql = $cllab_labcgm->sql_query(null, 'la04_i_codigo', null, " la04_i_laboratorio = $la02_i_codigo ");
     $rsTmp = $cllab_labcgm->sql_record($sSql);
     if($cllab_labcgm->numrows > 0) {
       
       db_fieldsmemory($rsTmp, 0);
       $cllab_labcgm->la04_i_codigo = $la04_i_codigo;
       $cllab_labcgm->alterar($la04_i_codigo);
       $iRows = $cllab_labcgm->numrows;

     } else {

       $cllab_labcgm->la04_i_laboratorio = $la02_i_codigo;
       $cllab_labcgm->incluir(null);
       $iRows = $cllab_labcgm->numrows;

     }
     $cllab_labdepart->excluir(null, " la03_i_laboratorio = $la02_i_codigo "); // excluo algum registro que possa ter na lab_labcgm

   }

   if($iRows == 0) { // houve algum erro

     $cllab_laboratorio->erro_status = '0';
     $cllab_laboratorio->erro_msg =  $la02_i_tipo == 1 ? $cllab_labdepart->erro_msg : $cllab_labcgm->erro_msg;

   } else {

     $cllab_laboratorio->alterar($la02_i_codigo);

   }
    
   db_fim_transacao($cllab_laboratorio->erro_status == '0' ? true : false);

}else if(isset($chavepesquisa)){

  /* Verifico se existe algum usuario cadastrado neste laboratorio. Se tiver, o tipo nao pode ser alterado para interno */
  $cllab_labusuario->sql_record($cllab_labusuario->sql_query("","*",""," la05_i_laboratorio = $chavepesquisa "));
  if($cllab_labusuario->numrows>0) { 
    $iBloqueioTipo = 3;
  }
  $db_botao = true;
  $db_opcao = 2;

  $rResult = $cllab_laboratorio->sql_record($cllab_laboratorio->sql_query($chavepesquisa)); 
  db_fieldsmemory($rResult,0);
   
  //tipo interno
  $rResult = $cllab_labdepart->sql_record($cllab_labdepart->sql_query("","*",""," la03_i_laboratorio=$chavepesquisa "));
  if($cllab_labdepart->numrows>0){
     db_fieldsmemory($rResult,0);
  }

  //tipo externo
  $rResult = $cllab_labcgm->sql_record($cllab_labcgm->sql_query("","*",""," la04_i_laboratorio=$chavepesquisa "));
  if($cllab_labcgm->numrows>0){
     db_fieldsmemory($rResult,0);
  }

   ?>
     <script>
         
         parent.document.formaba.a2.disabled = false;
         parent.document.formaba.a3.disabled = false;
         parent.document.formaba.a4.disabled = false;
         parent.document.formaba.a5.disabled = false;
         parent.document.formaba.a6.disabled = false;
         parent.document.formaba.a7.disabled = false; 
         parent.document.formaba.a8.disabled = false;
         top.corpo.iframe_a2.location.href='lab1_lab_labresp001.php?la06_i_laboratorio=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
         top.corpo.iframe_a3.location.href='lab1_lab_labusuario001.php?la05_i_laboratorio=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
         top.corpo.iframe_a4.location.href='lab1_lab_labsetor001.php?la24_i_laboratorio=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
         top.corpo.iframe_a5.location.href='lab1_lab_setorexame001.php?la24_i_laboratorio=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
         top.corpo.iframe_a6.location.href='lab1_lab_horario001.php?la02_i_codigo=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
         top.corpo.iframe_a7.location.href='lab1_lab_ausencia001.php?la02_i_codigo=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
         top.corpo.iframe_a8.location.href='lab1_lab_paralizacao001.php?la37_i_laboratorio=<?=$chavepesquisa?>&la02_c_descr=<?=$la02_c_descr?>';
           
     </script>
   <?
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
<!--
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>-->
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <br><br>
    <fieldset><legend><b> Laboratório </b></legend>
	<?
	include("forms/db_frmlab_laboratorio.php");
	?>
    </fieldset>
    </center>
	</td>
  </tr>
</table>
<center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($cllab_laboratorio->erro_status=="0"){
    $cllab_laboratorio->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_laboratorio->erro_campo!=""){
      echo "<script> document.form1.".$cllab_laboratorio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_laboratorio->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_laboratorio->erro(true,false);
    db_redireciona("lab1_lab_laboratorio002.php?chavepesquisa=".$cllab_laboratorio->la02_i_codigo);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","la02_i_tipo",true,1,"la02_i_tipo",true);
</script>