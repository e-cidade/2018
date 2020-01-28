<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: educa��o
require_once ('model/educacao/progressaoparcial/ProgressaoParcialAlunoRepository.model.php');
?>
<center>
<form name="form1" method="post" action="">
<center>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    <tr>
     <td>
      <b>Selecione o Curso:</b><br>
      <select name="curso"  style="font-size:9px;width:200px;height:18px;" onchange="js_botao(this.value)">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql = "SELECT DISTINCT ed29_i_codigo,ed29_c_descr,ed10_c_abrev
               FROM cursoedu
                inner join cursoescola on ed71_i_curso = ed29_i_codigo
                inner join ensino on ed10_i_codigo = ed29_i_ensino
               WHERE ed71_i_escola = $escola
               ORDER BY ed10_c_abrev ASC
              ";
       $sql_result = pg_query($sql);
       while($row=pg_fetch_array($sql_result)){
        $cod_curso=$row["ed29_i_codigo"];
        $desc_curso=$row["ed29_c_descr"];
        ?>
        <option value="<?=$cod_curso;?>" <?=$cod_curso==@$curso?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td valign='bottom'>
      <input type="button" name="procurar" value="Procurar" onclick="js_procurar()">
     </td>
    </tr>
   </table>
  </td>
 </tr> 
 <?if(isset($curso)){?>
 <tr>
  <td valign="top">
   <?
   $sql = "SELECT ed47_i_codigo,ed47_v_nome
           FROM historico
            inner join aluno on ed47_i_codigo = ed61_i_aluno
           WHERE ed61_i_curso = $curso
           AND ed61_i_escola = $escola           
           AND (ed61_i_anoconc is null or ed61_i_anoconc = 0) 
           AND (ed61_i_periodoconc is null or ed61_i_periodoconc =0)
           ORDER BY ed47_v_nome
          ";
   
   $result = pg_query($sql);
   $linhas = pg_num_rows($result);   
   ?>
   <b>Alunos:</b><br>
   <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()" style="font-size:9px;width:330px;height:120px" multiple>
    <?
    for ($i = 0; $i < $linhas; $i++) {
      
      db_fieldsmemory($result,$i);
      
      /**
       * Verificamos se o aluno possui alguma Progressao Parcial em aberto. Caso possua, nao sera permitido concluir 
       * o curso
       */
      $aProgressoesNaoEncerradas = ProgressaoParcialAlunoRepository::getProgressoesNaoEncerradasDoAluno(new Aluno($ed47_i_codigo));
      if (count($aProgressoesNaoEncerradas) == 0) {
        echo "<option value='$ed47_i_codigo'>$ed47_v_nome - $ed47_i_codigo</option>\n";
      }
    }
    ?>
   </select>
  </td>
  <td align="center">
   <br>
   <table border="0">
    <tr>
     <td>
      <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;">
     </td>
    </tr>
    <tr><td height="3"></td></tr>
    <tr>
     <td>
      <hr>
     </td>
    </tr>
    <tr><td height="3"></td></tr>
    <tr>
     <td>
      <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
    <tr><td height="1"></td></tr>
    <tr>
     <td>
      <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table>
    <tr>
     <td valign="top">
      <b>Alunos para conclus�o de curso:</b><br>
      <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" style="font-size:9px;width:330px;height:120px" multiple>
      </select>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" colspan="3">
   <input type="submit" name="concluir" value="Concluir" disabled onClick="js_selecionar();">
  </td>
 </tr>
  <?}?>
</table>
</center>
</form>
</center>
<script>
function js_incluir() {
 var Tam = document.form1.alunosdiario.length;
 var F = document.form1;
 for(x=0;x<Tam;x++){
  if(F.alunosdiario.options[x].selected==true){
   F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
   F.alunosdiario.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunosdiario.length>0){
  document.form1.alunosdiario.options[0].selected = true;
 }else{
  document.form1.incluirum.disabled = true;
  document.form1.incluirtodos.disabled = true;
 }
 document.form1.concluir.disabled = false;
 document.form1.excluirtodos.disabled = false;
 document.form1.alunosdiario.focus();
}
function js_incluirtodos() {
 var Tam = document.form1.alunosdiario.length;
 var F = document.form1;
 for(i=0;i<Tam;i++){
  F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value)
  F.alunosdiario.options[0] = null;
 }
 document.form1.incluirum.disabled = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 document.form1.concluir.disabled = false;
 document.form1.alunos.focus();
}
function js_excluir() {
 var F = document.getElementById("alunos");
 Tam = F.length;
 for(x=0;x<Tam;x++){
  if(F.options[x].selected==true){
   document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
   F.options[x] = null;
   Tam--;
   x--;
  }
 }
 if(document.form1.alunos.length>0){
  document.form1.alunos.options[0].selected = true;
 }
 if(F.length == 0){
  document.form1.concluir.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunos.focus();
}
function js_excluirtodos() {
 var Tam = document.form1.alunos.length;
 var F = document.getElementById("alunos");
 for(i=0;i<Tam;i++){
  document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
  F.options[0] = null;
 }
 if(F.length == 0){
  document.form1.concluir.disabled = true;
  document.form1.excluirum.disabled = true;
  document.form1.excluirtodos.disabled = true;
  document.form1.incluirtodos.disabled = false;
 }
 document.form1.alunosdiario.focus();
}
function js_desabinc(){
 for(i=0;i<document.form1.alunosdiario.length;i++){
  if(document.form1.alunosdiario.length>0 && document.form1.alunosdiario.options[i].selected){
   if(document.form1.alunos.length>0){
    document.form1.alunos.options[0].selected = false;
   }
   document.form1.incluirum.disabled = false;
   document.form1.excluirum.disabled = true;
  }
 }
}
function js_desabexc(){
 for(i=0;i<document.form1.alunos.length;i++){
  if(document.form1.alunos.length>0 && document.form1.alunos.options[i].selected){
   if(document.form1.alunosdiario.length>0){
    document.form1.alunosdiario.options[0].selected = false;
   }
   document.form1.incluirum.disabled = true;
   document.form1.excluirum.disabled = false;
  }
 }
}
function js_selecionar(){
 var F = document.getElementById("alunos").options;
 for(var i = 0;i < F.length;i++) {
  F[i].selected = true;
 }
 return true;
}
function js_botao(valor){
 if(valor!=""){
  document.form1.procurar.disabled = false;
 }else{
  document.form1.procurar.disabled = true;
 }
}
function js_procurar(){
 if(document.form1.curso.value!=""){
  location.href = "edu1_conclusao001.php?curso="+document.form1.curso.value;
 }
}
<?if(isset($curso)){?>
 if(document.form1.alunosdiario.length==0){
  <?if(!isset($concluir)){?>
   alert("N�o existem alunos para cancelar conclus�o para este curso!");
   location.href = "edu1_conclusao001.php";
  <?}?>
 }
<?}?>
</script>