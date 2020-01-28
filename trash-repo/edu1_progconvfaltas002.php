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
include("classes/db_progconvfaltas_classe.php");
include("classes/db_progconvocacaores_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprogconvfaltas = new cl_progconvfaltas;
$clprogconvocacaores = new cl_progconvocacaores;
$clprogconvfaltas->rotulo->label();
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 if($ed128_d_data_dia=="" || $ed128_d_data_mes=="" || $ed128_d_data_ano==""){
  $clprogconvfaltas->erro_status = "0";
  $clprogconvfaltas->erro_campo = "ed128_d_data_dia";
  $clprogconvfaltas->erro_msg = "Campo Data não Informado!";
 }elseif($ed128_c_numfono==""){
  $clprogconvfaltas->erro_status = "0";
  $clprogconvfaltas->erro_campo = "ed128_c_numfono";
  $clprogconvfaltas->erro_msg = "Campo N° do Fono não Informado!";
 }else{
  db_inicio_transacao();
  $clprogconvfaltas->incluir($ed128_i_codigo);
  db_fim_transacao();
 }
}
if(isset($alterar)){
 if($ed128_d_data_dia=="" || $ed128_d_data_mes=="" || $ed128_d_data_ano==""){
  $clprogconvfaltas->erro_status = "0";
  $clprogconvfaltas->erro_campo = "ed128_d_data_dia";
  $clprogconvfaltas->erro_msg = "Campo Data não Informado!";
 }elseif($ed128_c_numfono==""){
  $clprogconvfaltas->erro_status = "0";
  $clprogconvfaltas->erro_campo = "ed128_c_numfono";
  $clprogconvfaltas->erro_msg = "Campo N° do Fono não Informado!";
 }else{
  db_inicio_transacao();
  $clprogconvfaltas->alterar($ed128_i_codigo);
  db_fim_transacao();
 }
}
if(isset($excluir)){
 db_inicio_transacao();
 $db_opcao = 3;
 $clprogconvfaltas->excluir($ed128_i_codigo);
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="90%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <?
   include("dbforms/db_classesgenericas.php");
   $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
   $db_botao1 = false;
   if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
    $db_botao1 = true;
    $ed128_d_data_dia = substr($ed128_d_data,0,2);
    $ed128_d_data_mes = substr($ed128_d_data,3,2);
    $ed128_d_data_ano = substr($ed128_d_data,6,4);
   }elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_botao1 = true;
    $db_opcao = 3;
    $ed128_d_data_dia = substr($ed128_d_data,0,2);
    $ed128_d_data_mes = substr($ed128_d_data,3,2);
    $ed128_d_data_ano = substr($ed128_d_data,6,4);
   }else{
    if(isset($alterar)){
     $db_opcao = 2;
     $db_botao1 = true;
    }else{
     $db_opcao = 1;
    }
   }
   ?>
   <form name="form1" method="post" action="">
   <fieldset style="width:90%;"><legend><b>Faltas NÃO Justificadas</b></legend>
    <table border="0" align="left" cellspacing="4" cellpading="2">
     <tr>
      <td nowrap title="<?=@$Ted128_i_codigo?>">
       <?=@$Led128_i_codigo?>
      </td>
      <td>
       <?db_input('ed128_i_codigo',10,$Ied128_i_codigo,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted128_d_data?>">
       <?=@$Led128_d_data?>
      </td>
      <td>
       <?db_inputdata('ed128_d_data',@$ed128_d_data_dia,@$ed128_d_data_mes,@$ed128_d_data_ano,true,'text',$db_opcao," onchange=\"js_data();\"","","","parent.js_data();","js_data();")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted128_c_numfono?>">
       <?=@$Led128_c_numfono?>
      </td>
      <td>
       <?db_input('ed128_c_numfono',20,$Ied128_c_numfono,true,'text',$db_opcao,"")?>
      </td>
     </tr>
     <tr>
      <td colspan="2"><br><br>
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
       <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
       <input name="ed128_i_progconvres" type="hidden" value="<?=$ed128_i_progconvres?>">
       <input name="ed128_c_abonada" type="hidden" value="N">
      </td>
     </tr>
    </table>
    <br>
    <table border="0" align="left">
     <tr>
      <td valign="top">
       <?
       $chavepri= array("ed128_i_codigo"=>@$ed128_i_codigo,"ed128_d_data"=>@$ed128_d_data,"ed128_c_numfono"=>@$ed128_c_numfono);
       $cliframe_alterar_excluir->chavepri=$chavepri;
       @$cliframe_alterar_excluir->sql = $clprogconvfaltas->sql_query("","*",""," ed128_c_abonada = 'N' AND ed128_i_progconvres = $ed128_i_progconvres");
       $cliframe_alterar_excluir->campos  ="ed128_i_codigo,ed128_d_data,ed128_c_numfono";
       $cliframe_alterar_excluir->legenda="Registros";
       $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
       $cliframe_alterar_excluir->textocabec ="#DEB887";
       $cliframe_alterar_excluir->textocorpo ="#444444";
       $cliframe_alterar_excluir->fundocabec ="#444444";
       $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
       $cliframe_alterar_excluir->iframe_height ="150";
       $cliframe_alterar_excluir->iframe_width ="300";
       $cliframe_alterar_excluir->tamfontecabec = 9;
       $cliframe_alterar_excluir->tamfontecorpo = 9;
       $cliframe_alterar_excluir->formulario = false;
       $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
       ?>
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clprogconvfaltas->erro_status=="0"){
  $clprogconvfaltas->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>";
  if($clprogconvfaltas->erro_campo!=""){
   echo "<script> document.form1.".$clprogconvfaltas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprogconvfaltas->erro_campo.".focus();</script>";
   echo "<script> document.form1.cancelar.disabled=false;</script>";
  }
 }else{
  $clprogconvfaltas->erro(true,false);
  $result = $clprogconvfaltas->sql_record($clprogconvfaltas->sql_query("","count(*) as fnjust",""," ed128_c_abonada = 'N' AND ed128_i_progconvres = $ed128_i_progconvres"));
  db_fieldsmemory($result,0);
  $result = $clprogconvocacaores->sql_record($clprogconvocacaores->sql_query("","ed127_i_nfaltajust as fjust,ed127_i_nconvoca as nconv",""," ed127_i_codigo = $ed128_i_progconvres"));
  db_fieldsmemory($result,0);
  $sql1 = "UPDATE progconvocacaores SET
            ed127_i_nparticipa = ".($nconv-($fnjust+$fjust)).",
            ed127_i_nfaltanjust = $fnjust
           WHERE ed127_i_codigo = $ed128_i_progconvres
          ";
  $result1 = pg_query($sql1);
  ?>
  <script>
   parent.location.href = "edu1_progconvocacaores002.php?chavepesquisa=<?=$ed128_i_progconvres?>";
  </script>
  <?
 }
}
if(isset($alterar)){
 if($clprogconvfaltas->erro_status=="0"){
  $clprogconvfaltas->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprogconvfaltas->erro_campo!=""){
   echo "<script> document.form1.".$clprogconvfaltas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprogconvfaltas->erro_campo.".focus();</script>";
  }
 }else{
  $clprogconvfaltas->erro(true,false);
  ?>
  <script>
   parent.location.href = "edu1_progconvocacaores002.php?chavepesquisa=<?=$ed128_i_progconvres?>";
  </script>
  <?
 }
}
if(isset($excluir)){
 if($clprogconvfaltas->erro_status=="0"){
  $clprogconvfaltas->erro(true,false);
 }else{
  $clprogconvfaltas->erro(true,false);
  $result = $clprogconvfaltas->sql_record($clprogconvfaltas->sql_query("","count(*) as fnjust",""," ed128_c_abonada = 'N' AND ed128_i_progconvres = $ed128_i_progconvres"));
  db_fieldsmemory($result,0);
  $result = $clprogconvocacaores->sql_record($clprogconvocacaores->sql_query("","ed127_i_nfaltajust as fjust,ed127_i_nconvoca as nconv",""," ed127_i_codigo = $ed128_i_progconvres"));
  db_fieldsmemory($result,0);
  $sql1 = "UPDATE progconvocacaores SET
            ed127_i_nparticipa = ".($nconv-($fnjust+$fjust)).",
            ed127_i_nfaltanjust = $fnjust
           WHERE ed127_i_codigo = $ed128_i_progconvres
          ";
  $result1 = pg_query($sql1);
  ?>
  <script>
   parent.location.href = "edu1_progconvocacaores002.php?chavepesquisa=<?=$ed128_i_progconvres?>";
  </script>
  <?
 }
}
if(isset($cancelar)){
 ?>
 <script>
  parent.location.href = "edu1_progconvocacaores002.php?chavepesquisa=<?=$ed128_i_progconvres?>";
 </script>
 <?
}
?>
<script>
function js_data(){
 if(parent.document.form1.ed127_i_progmatricula.value==""){
  alert("Informe a Matrícula!");
  document.form1.ed128_d_data_dia.value = "";
  document.form1.ed128_d_data_mes.value = "";
  document.form1.ed128_d_data_ano.value = "";
  parent.js_pesquisaed127_i_progmatricula(true);
 }else{
  dataini = parent.document.form1.ed112_d_datainicio_ano.value+parent.document.form1.ed112_d_datainicio_mes.value+parent.document.form1.ed112_d_datainicio_dia.value;
  data = document.form1.ed128_d_data_ano.value+document.form1.ed128_d_data_mes.value+document.form1.ed128_d_data_dia.value;
  if(dataini>data && document.form1.ed128_d_data_dia.value!="" && document.form1.ed128_d_data_mes.value!="" && document.form1.ed128_d_data_ano.value!=""){
   alert("Data deve ser maior que a Data de Início na Classe!");
   document.form1.ed128_d_data_dia.value = "";
   document.form1.ed128_d_data_mes.value = "";
   document.form1.ed128_d_data_ano.value = "";
   document.form1.ed128_d_data_dia.focus();
  }
  if(parent.document.form1.ed127_i_ano.value!=document.form1.ed128_d_data_ano.value && document.form1.ed128_d_data_dia.value!="" && document.form1.ed128_d_data_mes.value!="" && document.form1.ed128_d_data_ano.value!=""){
   alert("Data deve estar dentro do ano referente!");
   document.form1.ed128_d_data_ano.value = "";
   document.form1.ed128_d_data_ano.focus();
  }
 }
}
</script>