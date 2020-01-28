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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cursoato_classe.php");
include("classes/db_cursoatoserie_classe.php");
include("classes/db_base_classe.php");
include("classes/db_cursoescola_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcursoato = new cl_cursoato;
$clcursoatoserie = new cl_cursoatoserie;
$clcursoescola = new cl_cursoescola;
$clbase = new cl_base;
$db_opcao = 1;
$db_botao = false;
$ed71_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome = db_getsession("DB_nomedepto");
$result_ver = $clcursoescola->sql_record($clcursoescola->sql_query("","ed71_i_codigo as codcursoescola",""," ed71_i_curso = $ed71_i_curso AND ed71_i_escola = $ed71_i_escola"));
if(isset($incluir)){
 db_inicio_transacao();
 if (!isset($ed216_i_serie)) {
   $ed216_i_serie = array();
 }
 $clcursoato->ed215_i_cursoescola = $codcursoescola;
 $clcursoato->incluir(null);
 $codcursoato = $clcursoato->ed215_i_codigo;

 if ($clcursoato->erro_status != '0') {

   for ($t=0;$t<count($ed216_i_serie);$t++){

     $clcursoatoserie->ed216_i_cursoato = $codcursoato;
     $clcursoatoserie->ed216_i_serie = $ed216_i_serie[$t];
     $clcursoatoserie->incluir(null);
     if ($clcursoatoserie->erro_status == '0') {

       $clcursoato->erro_status = '0';
       $clcursoato->erro_msg    = $clcursoatoserie->erro_msg;
       break;

     }

   }

 }
 db_fim_transacao($clcursoato->erro_status == '0');

}
if(isset($alterar)){
 $db_opcao = 2;
 db_inicio_transacao();
 if (!isset($ed216_i_serie)) {
   $ed216_i_serie = array();
 }
 $clcursoatoserie->excluir(""," ed216_i_cursoato = $ed215_i_codigo");

 if ($clcursoatoserie->erro_status == '0') {

   $clcursoato->erro_status = '0';
   $clcursoato->erro_msg    = $clcursoatoserie->erro_msg;

 }

 if ($clcursoatoserie->erro_status != '0') {

   for ($t=0;$t<count($ed216_i_serie);$t++){

     $clcursoatoserie->ed216_i_cursoato = $ed215_i_codigo;
     $clcursoatoserie->ed216_i_serie = $ed216_i_serie[$t];
     $clcursoatoserie->incluir(null);
     if ($clcursoatoserie->erro_status == '0') {
    
       $clcursoato->erro_status = '0';
       $clcursoato->erro_msg    = $clcursoatoserie->erro_msg;
       break;
    
     }

   }

 }

 if ($clcursoato->erro_status != '0') {

   $clcursoato->erro_msg    = "Alteração efetuada com Sucesso";
   $clcursoato->erro_status = "1";

 }
 db_fim_transacao($clcursoato->erro_status == '0');

}
if (isset($excluir)) {

 $db_opcao = 3;
 db_inicio_transacao();
 $clcursoatoserie->excluir(""," ed216_i_cursoato = $ed215_i_codigo");
 if ($clcursoatoserie->erro_status == '0') {
 
   $clcursoato->erro_status = '0';
   $clcursoato->erro_msg    = $clcursoatoserie->erro_msg;
 
 } else {
   $clcursoato->excluir($ed215_i_codigo);
 }

 db_fim_transacao($clcursoato->erro_status == '0');

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
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Atos Legais que regulamentam este curso na escola <?=$ed18_c_nome?></b></legend>
    <?
    if($clcursoescola->numrows==0){
     echo "<br><center>Para ter acesso a esta rotina, primeiro vincule este curso nesta escola. (Aba Vincular Curso)</center>";
     exit;
    }else{
     db_fieldsmemory($result_ver,0); 
     include("forms/db_frmcursoato.php");
    } 
    ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed215_i_atolegal",true,1,"ed215_i_atolegal",true);
</script>
<?
if(isset($incluir)){
 if($clcursoato->erro_status=="0"){
  $clcursoato->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcursoato->erro_campo!=""){
   echo "<script> document.form1.".$clcursoato->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcursoato->erro_campo.".focus();</script>";
  }
 }else{
  $clcursoato->erro(true,false);
  db_redireciona("edu1_cursoato001.php?ed71_i_curso=$ed71_i_curso&ed29_c_descr=$ed29_c_descr");
 }
}
if(isset($alterar)){
 if($clcursoato->erro_status=="0"){
  $clcursoato->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcursoato->erro_campo!=""){
   echo "<script> document.form1.".$clcursoato->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcursoato->erro_campo.".focus();</script>";
  }
 }else{
  $clcursoato->erro(true,false);
  db_redireciona("edu1_cursoato001.php?ed71_i_curso=$ed71_i_curso&ed29_c_descr=$ed29_c_descr");
 }
}
if(isset($excluir)){
 if($clcursoato->erro_status=="0"){
  $clcursoato->erro(true,false);
 }else{
  $clcursoato->erro(true,false);
  db_redireciona("edu1_cursoato001.php?ed71_i_curso=$ed71_i_curso&ed29_c_descr=$ed29_c_descr");
 }
}
if(isset($cancelar)){
 db_redireciona("edu1_cursoato001.php?ed71_i_curso=$ed71_i_curso&ed29_c_descr=$ed29_c_descr");
}
?>