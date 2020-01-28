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
include("classes/db_parecerresult_classe.php");
include("classes/db_parecer_classe.php");
include("classes/db_parecerlegenda_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clparecerresult = new cl_parecerresult;
$clparecer = new cl_parecer;
$clparecerlegenda = new cl_parecerlegenda;
$db_opcao = 1;
$db_botao = false;
if(isset($incluir)){
 $db_botao = true;
 if($ed92_i_sequencial!=""){
  $result1 = $clparecerresult->sql_record($clparecerresult->sql_query("","ed63_i_codigo,ed63_t_parecer",""," ed63_i_diarioresultado = $ed63_i_diarioresultado"));
  if($clparecerresult->numrows>0){
   db_fieldsmemory($result1,0);
   $conf_sequencial = trim($ed92_c_descr);
   if(strstr($ed63_t_parecer,$conf_sequencial)){
    $clparecerresult->erro_status = "0";
    $clparecerresult->erro_msg = "Parecer já Informado para este período.";
   }else{
    db_inicio_transacao();
    $clparecerresult->ed63_t_parecer= trim($ed63_t_parecer)." ** ".$ed92_i_sequencial." - ".trim($ed92_c_descr).($ed91_c_descr!=""?" =>".trim($ed91_c_descr):"");
    $clparecerresult->ed63_i_codigo=$ed63_i_codigo;
    $clparecerresult->alterar($ed63_i_codigo);
    db_fim_transacao();
   }
  }else{
   db_inicio_transacao();
   $clparecerresult->ed63_t_parecer= $ed92_i_sequencial." - ".trim($ed92_c_descr).($ed91_c_descr!=""?" => ".trim($ed91_c_descr):"");
   $clparecerresult->ed63_i_diarioresultado=$ed63_i_diarioresultado;
   $clparecerresult->incluir(null);
   db_fim_transacao();
  }
 }else{
  $clparecerresult->erro_status = "0";
  $clparecerresult->erro_msg = "Campo Parecer Não Informado.";
 }
}
if(isset($alterar)){
 $db_opcao = 2;
 $db_botao = true;
 db_inicio_transacao();
 $clparecerresult->ed63_t_parecer= $ed63_t_parecer;
 $clparecerresult->ed63_i_diarioresultado=$ed63_i_diarioresultado;
  $clparecerresult->alterar($ed63_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 $db_opcao = 3;
 $db_botao = true;
 db_inicio_transacao();
 $clparecerresult->excluir($ed63_i_codigo);
 db_fim_transacao();
}
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $result1 = $clparecerresult->sql_record($clparecerresult->sql_query("","ed63_t_parecer",""," ed63_i_codigo = $ed63_i_codigo"));
 db_fieldsmemory($result1,0);
 $db_opcao = 2;
 $db_botao1 = true;
 $db_botao = true;
}elseif(isset($opcao) && $opcao=="excluir"){
 $result1 = $clparecerresult->sql_record($clparecerresult->sql_query("","ed63_t_parecer",""," ed63_i_codigo = $ed63_i_codigo"));
 db_fieldsmemory($result1,0);
 $db_botao1 = true;
 $db_botao = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
  $db_botao = true;
 }else{
  $db_opcao = 1;
 }
}
if($encerrado=="S"){
 $db_botao = false;
 $db_botao1 = false;
 $db_opcao = 3;
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
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td bgcolor="#CCCCCC">
   <?db_input('ed63_i_codigo',10,@$Ied63_i_codigo,true,'hidden',$db_opcao,"")?>
   <?db_input('ed63_i_diarioresultado',10,@$Ied63_i_diarioresultado,true,'hidden',$db_opcao,"")?>
  </td>
 </tr>
 <?if((isset($opcao) && $opcao=="alterar") || (isset($opcao) && $opcao=="excluir") || (isset($alterar))){?>
  <tr>
   <td>
    <b>Parecer Padronizado:</b>
   </td>
   <td>
    <?db_textarea('ed63_t_parecer',2,80,@$ed63_t_parecer,true,'text',@$db_opcao,"")?><br>
   </td>
  </tr>
  <script>js_tabulacaoforms("form1","ed63_t_parecer",true,1,"ed63_t_parecer",true);</script>
 <?}else{?>
  <tr>
   <td nowrap title="<?=@$Ted92_i_sequencial?>">
   <?db_ancora("<b>Parecer:</b>","js_pesquisaed92_i_sequencial(true);",$db_opcao);?>
   </td>
   <td>
    <?db_input('ed92_i_sequencial',15,@$Ied92_i_sequencial,true,'text',$db_opcao," onchange='js_pesquisaed92_i_sequencial(false);'")?>
    <?db_input('ed92_c_descr',40,@$Ied92_c_descr,true,'text',3,"")?>
   </td>
  </tr>
  <tr>
   <td>
     <b>Legenda:</b>
  </td>
   <td>
    <select name="ed91_c_descr" <?=$encerrado=="S"?"disabled style=\"background:#f3f3f3;\"":"style=\"height:17px;font-size:10px;padding:0px;\""?>>
    <option value=""></option>
    <?
    $result = $clparecerlegenda->sql_record($clparecerlegenda->sql_query("","ed91_i_codigo,ed91_c_descr",""," ed91_i_escola=".db_getsession("DB_coddepto")));
    for($y=0;$y<$clparecerlegenda->numrows;$y++){
     db_fieldsmemory($result,$y);
     ?>
      <option value="<?=$ed91_c_descr?>"><?=trim($ed91_c_descr)?></option>
     <?
    }
    ?>
   </td>
  </tr>
  <script>js_tabulacaoforms("form1","ed92_i_sequencial",true,1,"ed92_i_sequencial",true);</script>
 <?}?>
 <tr>
  <td>
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
   <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
  </td>
 </tr>
</table>
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed63_i_codigo"=>@$ed63_i_codigo);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clparecerresult->sql_query("","*","ed63_i_codigo"," ed63_i_diarioresultado = $ed63_i_diarioresultado");
   $cliframe_alterar_excluir->campos  ="ed63_t_parecer";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="80";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<?
if(isset($incluir)){
 if($clparecerresult->erro_status=="0"){
  $clparecerresult->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clparecerresult->erro_campo!=""){
   echo "<script> document.form1.".$clparecerresult->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clparecerresult->erro_campo.".focus();</script>";
  }
 }else{
  $clparecerresult->erro(false,false);
  db_redireciona("edu1_parecerresult004.php?regencia=$regencia&ed43_i_codigo=$ed43_i_codigo&ed63_i_diarioresultado=$ed63_i_diarioresultado&campo=$campo&periodo=$periodo&aluno=$aluno&encerrado=$encerrado&turma=$turma&codaluno=$codaluno&modelo=$modelo");
 }
}
if(isset($alterar)){
 if($clparecerresult->erro_status=="0"){
  $clparecerresult->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clparecerresult->erro_campo!=""){
   echo "<script> document.form1.".$clparecerresult->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clparecerresult->erro_campo.".focus();</script>";
  }
 }else{
  $clparecerresult->erro(false,false);
  db_redireciona("edu1_parecerresult004.php?regencia=$regencia&ed43_i_codigo=$ed43_i_codigo&ed63_i_diarioresultado=$ed63_i_diarioresultado&campo=$campo&periodo=$periodo&aluno=$aluno&encerrado=$encerrado&turma=$turma&codaluno=$codaluno&modelo=$modelo");
 }
}
if(isset($excluir)){
 if($clparecerresult->erro_status=="0"){
  $clparecerresult->erro(true,false);
 }else{
  $clparecerresult->erro(false,false);
  db_redireciona("edu1_parecerresult004.php?regencia=$regencia&ed43_i_codigo=$ed43_i_codigo&ed63_i_diarioresultado=$ed63_i_diarioresultado&campo=$campo&periodo=$periodo&aluno=$aluno&encerrado=$encerrado&turma=$turma&codaluno=$codaluno&modelo=$modelo");
 }
}
if(isset($cancelar)){
 db_redireciona("edu1_parecerresult004.php?regencia=$regencia&ed43_i_codigo=$ed43_i_codigo&ed63_i_diarioresultado=$ed63_i_diarioresultado&campo=$campo&periodo=$periodo&aluno=$aluno&encerrado=$encerrado&turma=$turma&codaluno=$codaluno&modelo=$modelo");
}
?>
<script>
function js_pesquisaed92_i_sequencial(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('parent','db_iframe_parecer001','func_parecerdiario.php?turma=<?=$turma?>&funcao_js=parent.parecer.js_mostrasequencial1|ed92_i_sequencial|ed92_c_descr','Pesquisa',true,0,0,screen.availWidth-75,screen.availHeight);
 }else{
  if(document.form1.ed92_i_sequencial.value != ''){
   js_OpenJanelaIframe('parent','db_iframe_parecer001','func_parecerdiario.php?turma=<?=$turma?>&pesquisa_chave='+document.form1.ed92_i_sequencial.value+'&funcao_js=parent.parecer.js_mostrasequencial','Pesquisa',false);
  }else{
   document.form1.ed92_i_sequencial.value = '';
   document.form1.ed92_c_descr.value = '';
   document.getElementById("db_opcao").disabled = true;
  }
 }
}
function js_mostrasequencial(chave,erro){
 document.form1.ed92_c_descr.value = chave;
 if(erro==true){
  document.form1.ed92_i_sequencial.focus();
  document.form1.ed92_i_sequencial.value = '';
 }else{
  document.getElementById("db_opcao").disabled = false;
  document.getElementById("db_opcao").focus();
 }
}
function js_mostrasequencial1(chave1,chave2){
 document.form1.ed92_i_sequencial.value = chave1;
 document.form1.ed92_c_descr.value = chave2;
 document.getElementById("db_opcao").disabled = false;
 document.getElementById("db_opcao").focus();
 parent.db_iframe_parecer001.hide();
}
</script>