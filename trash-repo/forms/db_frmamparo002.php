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

//MODULO: educação
$clamparo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed95_i_codigo");
$clrotulo->label("ed06_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" align="left">
 <?if(isset($opcao)){?>
 <tr>
  <td valign="top">
   <?
   $sql = "SELECT ed95_i_codigo,ed47_v_nome
           FROM diario
            inner join aluno on ed47_i_codigo = ed95_i_aluno
            inner join amparo on ed81_i_diario = ed95_i_codigo
           WHERE ed95_i_regencia = $regencia
           AND ed95_c_encerrado = 'N'
           ORDER BY ed47_v_nome
          ";
   $result = pg_query($sql);
   $linhas = pg_num_rows($result);
   ?>
   <b>Selecione o aluno:</b><br>
   <select name="alunosdiario" id="alunosdiario" onchange="js_alunoescolha(this.value,<?=$regencia?>);" style="font-size:9px;width:330px;">
    <option value=""><?=$linhas==0?"NÃO EXISTEM ALUNOS PARA ALTERAÇÃO DE AMPARO.":""?></option>
    <?
    for($i=0;$i<$linhas;$i++) {
     db_fieldsmemory($result,$i);
     ?>
     <option value='<?=$ed95_i_codigo?>' <?=$ed95_i_codigo==@$diario?"selected":""?>><?=$ed47_v_nome?></option>
     <?
    }
    ?>
   </select>
  </td>
 </tr>
 <?if(isset($diario)){?>
   <tr>
    <td valign="top">
     <?
     $sql1 = "SELECT DISTINCT ed72_i_procavaliacao,ed09_c_descr
             FROM diario
              inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo
              inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
              inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
             WHERE ed95_i_regencia = $regencia
             AND ed72_i_diario = $diario
             AND ed72_c_amparo = 'N'
            ";
     $result1 = pg_query($sql1);
     $linhas1 = pg_num_rows($result1);
     ?>
     <b>Períodos:</b><br>
     <select name="avaliacoesdiario" id="avaliacoesdiario" onclick="js_desabinc2()" style="font-size:9px;width:330px;height:100px" multiple>
      <?
      for($i=0;$i<$linhas1;$i++) {
       db_fieldsmemory($result1,$i);
       echo "<option value='$ed72_i_procavaliacao'>$ed09_c_descr</option>\n";
      }
      ?>
     </select>
    </td>
    <td align="center">
     <br>
     <table border="0" cellspacing="0" cellpading="0">
      <tr>
       <td>
        <input name="incluirum2" title="Incluir" type="button" value=">" onclick="js_incluir2();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
       </td>
      </tr>
      <tr><td height="1"></td></tr>
      <tr>
       <td>
        <input name="incluirtodos2" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos2();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
       </td>
      </tr>
      <tr><td height="1"></td></tr>
      <tr>
       <td>
        <hr>
       </td>
      </tr>
      <tr><td height="1"></td></tr>
      <tr>
       <td>
        <input name="excluirum2" title="Excluir" type="button" value="<" onclick="js_excluir2();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
       </td>
      </tr>
      <tr><td height="1"></td></tr>
      <tr>
       <td>
        <input name="excluirtodos2" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos2();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
       </td>
      </tr>
     </table>
    </td>
    <td valign="top">
     <table>
      <tr>
       <td valign="top">
        <?
        $sql2 = "SELECT DISTINCT ed72_i_procavaliacao,ed09_c_descr,ed81_i_justificativa,ed06_c_descr,ed81_i_convencaoamp,ed250_c_descr,ed250_c_abrev,ed81_c_aprovch,ed81_c_todoperiodo
                FROM diario
                 inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo
                 inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
                 inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
                 inner join amparo on ed81_i_diario = ed95_i_codigo
                 left join justificativa on ed06_i_codigo = ed81_i_justificativa
                 left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp
                WHERE ed95_i_regencia = $regencia
                AND ed72_i_diario = $diario
                AND ed72_c_amparo = 'S'
               ";
        $result2 = pg_query($sql2);
        $linhas2 = pg_num_rows($result2);
        ?>
        <b>Períodos com Amparo:</b><br>
        <select name="avaliacoes[]" id="avaliacoes" onclick="js_desabexc2()" style="font-size:9px;width:330px;height:100px" multiple>
         <?
         for($i=0;$i<$linhas2;$i++) {
          db_fieldsmemory($result2,$i);
          echo "<option value='$ed72_i_procavaliacao'>$ed09_c_descr</option>\n";
         }
         ?>
        </select>
       </td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
    <td colspan="3">
     <b>Escolha o tipo de amparo:</b>
     <select name="opcaoamparo" onchange="js_escolheamparo(this.value)" style="height:16px;font-size:9px;">
      <option value=""></option>
      <option value="J" <?=@$ed81_i_justificativa!=""?"selected":""?>>Amparo com Justificativa</option>
      <option value="C" <?=@$ed81_i_convencaoamp!=""?"selected":""?>>Ampara com Convenções</option>
     </select>
    </td>
   </tr>
   <tr height="20">
    <td colspan="3">
     <table id="justificativa" style="position:absolute;visibility:hidden;">
      <tr>
       <td nowrap title="<?=@$Ted81_i_justificativa?>">
        <?db_ancora(@$Led81_i_justificativa,"js_pesquisaed81_i_justificativa(true);",$db_opcao);?>
        <?db_input('ed81_i_justificativa',15,$Ied81_i_justificativa,true,'text',$db_opcao," onchange='js_pesquisaed81_i_justificativa(false);'")?>
        <?db_input('ed06_c_descr',@50,@$ed06_c_descr,true,'text',3,'')?>
       </td>
      </tr>
     </table>
     <table id="convencao" style="position:absolute;visibility:hidden;">
      <tr>
       <td nowrap title="<?=@$Ted81_i_convencaoamp?>">
        <?db_ancora(@$Led81_i_convencaoamp,"js_pesquisaed81_i_convencaoamp(true);",$db_opcao);?>
        <?db_input('ed81_i_convencaoamp',15,@$Ied81_i_convencaoamp,true,'text',$db_opcao," onchange='js_pesquisaed81_i_convencaoamp(false);'")?>
        <?db_input('ed250_c_descr',50,@$ed250_c_descr,true,'text',3,'')?>
        <?db_input('ed250_c_abrev',5,@$ed250_c_abrev,true,'text',3,'')?>
       </td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
    <td nowrap title="<?=@$Ted81_c_aprovch?>" colspan="2">
     <br>
     <b>Gerar carga horária para esta disciplina no histórico:</b>
     <?
     $x = array("N"=>"NÃO","S"=>"SIM");
     db_select('ed81_c_aprovch',$x,true,$db_opcao,"");
     ?>
    </td>
    <td align="center">
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" onClick="return js_selecionar();">
     <input name="ed81_c_todoperiodo" type="hidden" value="<?=$ed81_c_todoperiodo?>">
     <input name="regencia" type="hidden" value="<?=$regencia?>">
    </td>
   </tr>
   <script>
    <?if($ed81_i_justificativa!=""){?>
     document.getElementById("justificativa").style.visibility = "visible";
    <?}?>
    <?if($ed81_i_convencaoamp!=""){?>
     document.getElementById("convencao").style.visibility = "visible";
    <?}?>
   </script>
  <?}?>
 <?}?>
</table>
</center>
</form>
<script>
function js_pesquisaed81_i_justificativa(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_justificativa','func_justificativa.php?funcao_js=parent.js_mostrajustificativa1|ed06_i_codigo|ed06_c_descr','Pesquisa de Justificativas',true);
 }else{
  if(document.form1.ed81_i_justificativa.value != ''){
   js_OpenJanelaIframe('','db_iframe_justificativa','func_justificativa.php?pesquisa_chave='+document.form1.ed81_i_justificativa.value+'&funcao_js=parent.js_mostrajustificativa','Pesquisa',false);
  }else{
   document.form1.ed06_c_descr.value = '';
  }
 }
}
function js_mostrajustificativa(chave,erro){
 document.form1.ed06_c_descr.value = chave;
 if(erro==true){
  document.form1.ed81_i_justificativa.focus();
  document.form1.ed81_i_justificativa.value = '';
 }
}
function js_mostrajustificativa1(chave1,chave2){
  document.form1.ed81_i_justificativa.value = chave1;
  document.form1.ed06_c_descr.value = chave2;
  db_iframe_justificativa.hide();
}
function js_pesquisaed81_i_convencaoamp(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_convencaoamp','func_convencaoamp.php?funcao_js=parent.js_mostraconvencaoamp1|ed250_i_codigo|ed250_c_descr|ed250_c_abrev','Pesquisa de Convenções para Amparo',true);
 }else{
  if(document.form1.ed81_i_convencaoamp.value != ''){
   js_OpenJanelaIframe('','db_iframe_convencaoamp','func_convencaoamp.php?pesquisa_chave='+document.form1.ed81_i_convencaoamp.value+'&funcao_js=parent.js_mostraconvencaoamp','Pesquisa',false);
  }else{
   document.form1.ed250_c_descr.value = '';
   document.form1.ed250_c_abrev.value = '';
  }
 }
}
function js_mostraconvencaoamp(chave1,chave2,erro){
 document.form1.ed250_c_descr.value = chave1;
 document.form1.ed250_c_abrev.value = chave2;
 if(erro==true){
  document.form1.ed81_i_convencaoamp.focus();
  document.form1.ed81_i_convencaoamp.value = '';
 }
}
function js_mostraconvencaoamp1(chave1,chave2,chave3){
 document.form1.ed81_i_convencaoamp.value = chave1;
 document.form1.ed250_c_descr.value = chave2;
 document.form1.ed250_c_abrev.value = chave3;
 db_iframe_convencaoamp.hide();
}
///////////////////////////////////////////////////////////////////////////////////////////
function js_alunoescolha(diario,regencia){
 if(diario!=""){
  location.href = "edu1_amparo001.php?opcao=A&diario="+diario+"&regencia="+regencia;
 }
}
function js_incluir2(){
 var Tam = document.form1.avaliacoesdiario.length;
 var F = document.form1;
 for(x=0;x<Tam;x++){
  if(F.avaliacoesdiario.options[x].selected==true){
   F.elements['avaliacoes[]'].options[F.elements['avaliacoes[]'].options.length] = new Option(F.avaliacoesdiario.options[x].text,F.avaliacoesdiario.options[x].value)
   F.avaliacoesdiario.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.avaliacoesdiario.length>0){
  document.form1.avaliacoesdiario.options[0].selected = true;
  document.form1.ed81_c_todoperiodo.value = "N";
 }else{
  document.form1.incluirum2.disabled = true;
  document.form1.incluirtodos2.disabled = true;
  document.form1.ed81_c_todoperiodo.value = "S";
 }
 document.form1.alterar.disabled = false;
 document.form1.excluirtodos2.disabled = false;
 document.form1.avaliacoesdiario.focus();
}
function js_incluirtodos2() {
 var Tam = document.form1.avaliacoesdiario.length;
 var F = document.form1;
 for(i=0;i<Tam;i++){
  F.elements['avaliacoes[]'].options[F.elements['avaliacoes[]'].options.length] = new Option(F.avaliacoesdiario.options[0].text,F.avaliacoesdiario.options[0].value)
  F.avaliacoesdiario.options[0] = null;
 }
 document.form1.incluirum2.disabled = true;
 document.form1.incluirtodos2.disabled = true;
 document.form1.excluirtodos2.disabled = false;
 document.form1.alterar.disabled = false;
 document.form1.avaliacoes.focus();
 document.form1.ed81_c_todoperiodo.value = "S";
}
function js_excluir2() {
 var F = document.getElementById("avaliacoes");
 Tam = F.length;
 for(x=0;x<Tam;x++){
  if(F.options[x].selected==true){
   document.form1.avaliacoesdiario.options[document.form1.avaliacoesdiario.length] = new Option(F.options[x].text,F.options[x].value);
   F.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.avaliacoes.length>0){
  document.form1.avaliacoes.options[0].selected = true;
 }
 if(document.form1.avaliacoesdiario.length>0){
  document.form1.ed81_c_todoperiodo.value = "N";
 }
 if(F.length == 0){
  document.form1.alterar.disabled = true;
  document.form1.excluirum2.disabled = true;
  document.form1.excluirtodos2.disabled = true;
  document.form1.incluirtodos2.disabled = false;
 }
 document.form1.avaliacoes.focus();
}
function js_excluirtodos2() {
 var Tam = document.form1.avaliacoes.length;
 var F = document.getElementById("avaliacoes");
 for(i=0;i<Tam;i++){
  document.form1.avaliacoesdiario.options[document.form1.avaliacoesdiario.length] = new Option(F.options[0].text,F.options[0].value);
  F.options[0] = null;
 }
 if(F.length == 0){
  document.form1.alterar.disabled = true;
  document.form1.excluirum2.disabled = true;
  document.form1.excluirtodos2.disabled = true;
  document.form1.incluirtodos2.disabled = false;
  document.form1.ed81_c_todoperiodo.value = "N";
 }
 document.form1.avaliacoesdiario.focus();
}
function js_desabinc2(){
 for(i=0;i<document.form1.avaliacoesdiario.length;i++){
  if(document.form1.avaliacoesdiario.length>0 && document.form1.avaliacoesdiario.options[i].selected){
   if(document.form1.avaliacoes.length>0){
    document.form1.avaliacoes.options[0].selected = false;
   }
   document.form1.incluirum2.disabled = false;
   document.form1.excluirum2.disabled = true;
  }
 }
}
function js_desabexc2(){
 for(i=0;i<document.form1.avaliacoes.length;i++){
  if(document.form1.avaliacoes.length>0 && document.form1.avaliacoes.options[i].selected){
   if(document.form1.avaliacoesdiario.length>0){
    document.form1.avaliacoesdiario.options[0].selected = false;
   }
   document.form1.incluirum2.disabled = true;
   document.form1.excluirum2.disabled = false;
  }
 }
}
function js_selecionar(){
 if(document.form1.opcaoamparo.value==""){
  alert("Informe o tipo de Amparo!");
  return false;
 }
 if(document.form1.opcaoamparo.value=="C" && document.form1.ed81_i_convencaoamp.value==""){
  alert("Informe a Convenção!");
  return false;
 }
 if(document.form1.opcaoamparo.value=="J" && document.form1.ed81_i_justificativa.value==""){
  alert("Informe a justificativa Legal!");
  return false;
 }
 var F = document.getElementById("avaliacoes").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 return true;
}
function js_escolheamparo(valor){
 if(valor=="J"){
  document.form1.ed81_i_convencaoamp.value = '';
  document.form1.ed250_c_descr.value = '';
  document.form1.ed250_c_abrev.value = '';
  document.getElementById("justificativa").style.visibility = "visible";
  document.getElementById("convencao").style.visibility = "hidden";
 }else if(valor=="C"){
  document.form1.ed81_i_justificativa.value = '';
  document.form1.ed06_c_descr.value = '';
  document.getElementById("justificativa").style.visibility = "hidden";
  document.getElementById("convencao").style.visibility = "visible";
 }else{
  document.form1.ed81_i_convencaoamp.value = '';
  document.form1.ed250_c_descr.value = '';
  document.form1.ed250_c_abrev.value = '';
  document.form1.ed81_i_justificativa.value = '';
  document.form1.ed06_c_descr.value = '';
  document.getElementById("justificativa").style.visibility = "hidden";
  document.getElementById("convencao").style.visibility = "hidden";
 }
 if(document.form1.avaliacoesdiario.length==0){
  document.form1.ed81_c_todoperiodo.value = 'S';
 }else{
  document.form1.ed81_c_todoperiodo.value = 'N';
 }
}
<?if(isset($diario)){?>
if(document.form1.avaliacoes.length>0){
 document.form1.excluirtodos2.disabled = false;
}
if(document.form1.avaliacoesdiario.length==0){
 document.form1.incluirtodos2.disabled = true;
}
<?}?>
if(document.form1.alunosdiario.length==0){
 alert("Nenhum aluno tem amparo para alteração!");
 document.form1.alterar.disabled = true;
 document.form1.incluirtodos2.disabled = true;
 document.form1.avaliacoesdiario.disabled = true;
 document.form1.avaliacoes.disabled = true;
 document.form1.avaliacoes.style.background = "#CCCCCC";
 document.form1.avaliacoesdiario.style.background = "#CCCCCC";
}
</script>