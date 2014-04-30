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
include("classes/db_relacaotrabalho_classe.php");
include("classes/db_rechumano_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrelacaotrabalho = new cl_relacaotrabalho;
$clrechumano = new cl_rechumano;
$db_opcao = 1;
$db_botao = true;
$result1 = $clrelacaotrabalho->sql_record($clrelacaotrabalho->sql_query("","ed23_i_disciplina as discjacad",""," ed23_i_rechumanoescola = $ed23_i_rechumanoescola"));
if($clrelacaotrabalho->numrows>0){
 $disc_cad = "";
 $sep = "";
 for($c=0;$c<$clrelacaotrabalho->numrows;$c++){
  db_fieldsmemory($result1,$c);
  if($discjacad!=""){
   $disc_cad .= $sep.$discjacad;
   $sep = ",";
  }
 }
}else{
 $disc_cad = 0;
}
if(isset($incluir)){
 db_inicio_transacao();
 if($regente=="N"){
  $clrelacaotrabalho->incluir(null);
 }else{
  for($x=0;$x<count($coddisciplina);$x++){
   $clrelacaotrabalho->ed23_i_disciplina = $coddisciplina[$x];
   $clrelacaotrabalho->incluir(null);
  }
 }
 db_fim_transacao();
}
if(isset($alterar)){
 $db_opcao = 2;
 db_inicio_transacao();
 $clrelacaotrabalho->alterar($ed23_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 db_inicio_transacao();
 $clrelacaotrabalho->excluir($ed23_i_codigo);
 db_fim_transacao();
}
$campos = "case when ed20_i_tiposervidor = 1
            then ed284_i_rhpessoal
            else ed285_i_cgm
           end as identificacao,
           case when ed20_i_tiposervidor = 1
            then cgmrh.z01_nome
            else cgmcgm.z01_nome
           end as z01_nome,
           ed20_i_tiposervidor
          ";
$result11 = $clrechumano->sql_record($clrechumano->sql_query_escola("",$campos,""," ed75_i_codigo = $ed23_i_rechumanoescola"));
db_fieldsmemory($result11,0);
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
   <fieldset style="width:95%"><legend><b>Relações de Trabalho</b></legend>
    <?include("forms/db_frmrelacaotrabalho.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed23_i_regimetrabalho",true,1,"ed23_i_regimetrabalho",true);
</script>
<?
if(isset($incluir)){
 if($clrelacaotrabalho->erro_status=="0"){
  $clrelacaotrabalho->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clrelacaotrabalho->erro_campo!=""){
   echo "<script> document.form1.".$clrelacaotrabalho->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clrelacaotrabalho->erro_campo.".focus();</script>";
  }
 }else{
  db_redireciona("edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=$ed23_i_rechumanoescola");
 }
}
if(isset($alterar)){
 if($clrelacaotrabalho->erro_status=="0"){
  $clrelacaotrabalho->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clrelacaotrabalho->erro_campo!=""){
   echo "<script> document.form1.".$clrelacaotrabalho->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clrelacaotrabalho->erro_campo.".focus();</script>";
  }
 }else{
  db_redireciona("edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=$ed23_i_rechumanoescola");
 }
}
if(isset($excluir)){
 if($clrelacaotrabalho->erro_status=="0"){
  $clrelacaotrabalho->erro(true,false);
 }else{
  db_redireciona("edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=$ed23_i_rechumanoescola");
 }
}
if(isset($cancelar)){
 db_redireciona("edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=$ed23_i_rechumanoescola");
}
?>