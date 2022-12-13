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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cledutabelasdump->rotulo->label();
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
if(isset($atualizar1)){
 $tam = sizeof($camposse);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE edutabelasdump SET
           ed130_i_sequencia = ".($i+1)."
          WHERE ed130_i_codigo = $camposse[$i]
         ";
  $query = db_query($sql);
 }
 echo "<script>location.href='".$cledutabelasdump->pagina_retorno."'</script>";
}
if(isset($atualizar2)){
 $tam = sizeof($camposes);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE edutabelasdump SET
           ed130_i_sequencia = ".($i+1)."
          WHERE ed130_i_codigo = $camposes[$i]
         ";
  $query = db_query($sql);
 }
 echo "<script>location.href='".$cledutabelasdump->pagina_retorno."'</script>";
}

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted130_i_codigo?>">
   <?=@$Led130_i_codigo?>
  </td>
  <td>
   <?db_input('ed130_i_codigo',20,$Ied130_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted130_c_tabela?>">
   <?=@$Led130_c_tabela?>
  </td>
  <td>
   <?db_input('ed130_c_tabela',50,$Ied130_c_tabela,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted130_c_tipo?>">
   <?=@$Led130_c_tipo?>
  </td>
  <td>
   <?
   $x = array('SE'=>'SECRETARIA -> ESCOLA','ES'=>'ESCOLA -> SECRETARIA');
   db_select('ed130_c_tipo',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted130_c_dumpseq?>">
   <?=@$Led130_c_dumpseq?>
  </td>
  <td>
   <?
   $x = array('S'=>'SIM','N'=>'NÃO');
   db_select('ed130_c_dumpseq',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted130_c_dumptrigger?>">
   <?=@$Led130_c_dumptrigger?>
  </td>
  <td>
   <?
   $x = array('S'=>'SIM','N'=>'NÃO');
   db_select('ed130_c_dumptrigger',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table border="0">
 <tr>
  <td>
   <table>
    <tr>
     <td valign="top"><br>
     <?
      $chavepri= array("ed130_i_codigo"=>@$ed130_i_codigo,"ed130_c_tabela"=>@$ed130_c_tabela,"ed130_c_tipo"=>@$ed130_c_tipo,"ed130_c_dumpseq"=>@$ed130_c_dumpseq,"ed130_i_sequencia"=>@$ed130_i_sequencia);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      @$cliframe_alterar_excluir->sql = $cledutabelasdump->sql_query("","*","ed130_i_sequencia"," ed130_c_tipo = 'SE'");
      $cliframe_alterar_excluir->campos  ="ed130_c_tabela,ed130_c_dumpseq,ed130_c_dumptrigger";
      $cliframe_alterar_excluir->legenda="SECRETARIA -> ESCOLA";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="#DEB887";
      $cliframe_alterar_excluir->textocorpo ="#444444";
      $cliframe_alterar_excluir->fundocabec ="#444444";
      $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
      $cliframe_alterar_excluir->iframe_height ="200";
      $cliframe_alterar_excluir->iframe_width ="340";
      $cliframe_alterar_excluir->tamfontecabec = 9;
      $cliframe_alterar_excluir->tamfontecorpo = 9;
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
     </td>
    </tr>
   </table>
  </td>
  <td>
   <table>
    <tr>
     <td valign="top"><br>
     <?
      $chavepri= array("ed130_i_codigo"=>@$ed130_i_codigo,"ed130_c_tabela"=>@$ed130_c_tabela,"ed130_c_tipo"=>@$ed130_c_tipo,"ed130_c_dumpseq"=>@$ed130_c_dumpseq,"ed130_i_sequencia"=>@$ed130_i_sequencia);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      @$cliframe_alterar_excluir->sql = $cledutabelasdump->sql_query("","*","ed130_i_sequencia"," ed130_c_tipo = 'ES'");
      $cliframe_alterar_excluir->campos  ="ed130_c_tabela,ed130_c_dumpseq,ed130_c_dumptrigger";
      $cliframe_alterar_excluir->legenda="ESCOLA -> SECRETARIA";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="#DEB887";
      $cliframe_alterar_excluir->textocorpo ="#444444";
      $cliframe_alterar_excluir->fundocabec ="#444444";
      $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
      $cliframe_alterar_excluir->iframe_height ="200";
      $cliframe_alterar_excluir->iframe_width ="340";
      $cliframe_alterar_excluir->tamfontecabec = 9;
      $cliframe_alterar_excluir->tamfontecorpo = 9;
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<table border="0">
 <tr>
  <td valign="top">
   <table border="0">
    <tr>
     <td>
      <?
      $sql = "SELECT ed130_i_codigo,ed130_i_sequencia,ed130_c_tabela from edutabelasdump where ed130_c_tipo = 'SE' order by ed130_i_sequencia";
      $query = db_query($sql);
      $linhas = pg_num_rows($query);
      ?>
      <b>Ordenar Tabelas(<?=$linhas?>) SE:</b><br>
      <select name="camposse[]" id="camposse" size="20" style="font-size:10px;width:230px" multiple>
      <?
       if($linhas>0){
        for($i=0;$i<$linhas;$i++){
         $dados = pg_fetch_array($query);
         echo "<option value=\"".$dados["ed130_i_codigo"]."\">".str_pad($dados["ed130_i_sequencia"],3,0,str_pad_left)." - ".trim($dados["ed130_c_tabela"])."</option>\n";
        }
       }
      ?>
      </select>
     </td>
     <td valign="top">
      <br/>
      <img style="cursor:hand" onClick="js_sobe1();return false;" src="skins/img.php?file=Controles/seta_up.png" />
      <br/>
      <img style="cursor:hand" onClick="js_desce1()" src="skins/img.php?file=Controles/seta_down.png" />
      <br/>
      <input name="atualizar1" type="submit" value="Atualizar" onclick="js_selecionar1()" />
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table border="0">
    <tr>
     <td>
      <?
      $sql = "SELECT ed130_i_codigo,ed130_i_sequencia,ed130_c_tabela from edutabelasdump where ed130_c_tipo = 'ES' order by ed130_i_sequencia";
      $query = db_query($sql);
      $linhas = pg_num_rows($query);
      ?>
      <b>Ordenar Tabelas(<?=$linhas?>) ES:</b><br>
      <select name="camposes[]" id="camposes" size="20" style="font-size:10px;width:230px" multiple>
      <?
       if($linhas>0){
        for($i=0;$i<$linhas;$i++){
         $dados = pg_fetch_array($query);
         echo "<option value=\"".$dados["ed130_i_codigo"]."\">".str_pad($dados["ed130_i_sequencia"],3,0,str_pad_left)." - ".trim($dados["ed130_c_tabela"])."</option>\n";
        }
       }
      ?>
      </select>
     </td>
     <td valign="top">
      <br>
      <img style="cursor:hand" onClick="js_sobe2();return false" src="skins/img.php?file=Controles/seta_up.png" width="20" height="20" border="0">
      <br>
      <img style="cursor:hand" onClick="js_desce2()" src="skins/img.php?file=Controles/seta_down.png" width="20" height="20" border="0">
      <br>
      <input name="atualizar2" type="submit" value="Atualizar" onclick="js_selecionar2()">
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_sobe1() {
 var F = document.getElementById("camposse");
 if(F.selectedIndex != -1 && F.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce1() {
 var F = document.getElementById("camposse");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_selecionar1() {
 var F = document.getElementById("camposse").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 return true;
}

function js_sobe2() {
 var F = document.getElementById("camposes");
 if(F.selectedIndex != -1 && F.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce2() {
 var F = document.getElementById("camposes");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_selecionar2() {
 var F = document.getElementById("camposes").options;
 for(var i = 0;i < F.length;i++) {
   F[i].selected = true;
 }
 return true;
}
</script>