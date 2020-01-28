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
$clperiodoavaliacao->rotulo->label();
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
if(isset($atualizar)){
 $tam = sizeof($campos);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE periodoavaliacao SET
           ed09_i_sequencia = ".($i+1)."
          WHERE ed09_i_codigo = $campos[$i]
         ";
  $query = db_query($sql);
 }
 echo "<script>location.href='".$clperiodoavaliacao->pagina_retorno."'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="95%" cellspacing='0' cellpading='0'>
 <tr>
  <td>
   <table border="0" cellspacing='0' cellpading='0'>
    <tr>
     <td nowrap title="<?=@$Ted09_i_codigo?>">
      <?=@$Led09_i_codigo?>
     </td>
     <td>
      <?db_input('ed09_i_codigo',10,$Ied09_i_codigo,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted09_c_descr?>">
      <?=@$Led09_c_descr?>
     </td>
     <td>
      <?db_input('ed09_c_descr',40,$Ied09_c_descr,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted09_c_abrev?>">
      <?=@$Led09_c_abrev?>
     </td>
     <td>
      <?db_input('ed09_c_abrev',10,$Ied09_c_abrev,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted09_c_somach?>">
      <?=@$Led09_c_somach?>
     </td>
     <td>
      <?
      $x = array('S'=>'SIM','N'=>'NÃO');
      db_select('ed09_c_somach',$x,true,$db_opcao,"");
      ?>
      <?=@$Led09_c_controlfreq?>
      <?
      $x = array('S'=>'SIM','N'=>'NÃO');
      db_select('ed09_c_controlfreq',$x,true,$db_opcao,"");
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table border="0" cellspacing='0' cellpading='0'>
    <tr>
     <td>
      <b>Ordenar Períodos:</b><br>
      <select name="campos[]" id="campos" size="8" style="font-size:9px;width:175px" multiple>
      <?
       $sql = "SELECT ed09_i_codigo,ed09_c_descr from periodoavaliacao order by ed09_i_sequencia,ed09_i_codigo";
       $query = db_query($sql);
       $linhas = pg_num_rows($query);
       if($linhas>0){
        for($i=0;$i<$linhas;$i++){
        $dados = pg_fetch_array($query);
         echo "<option value=\"".$dados["ed09_i_codigo"]."\">".trim($dados["ed09_c_descr"])."</option>\n";
        }
       }
      ?>
      </select>
     </td>
     <td valign="top">
      <br/>
      <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
      <br/>
      <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
      <br/>
      <input name="atualizar" type="submit" value="Atualizar" onclick="js_selecionar()" />
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed09_i_codigo"=>@$ed09_i_codigo,"ed09_c_descr"=>@$ed09_c_descr,"ed09_c_abrev"=>@$ed09_c_abrev,"ed09_c_somach"=>@$ed09_c_somach,"ed09_c_controlfreq"=>@$ed09_c_controlfreq);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clperiodoavaliacao->sql_query($ed09_i_codigo,"*","ed09_i_sequencia,ed09_i_codigo");
   $cliframe_alterar_excluir->campos  ="ed09_i_codigo,ed09_c_abrev,ed09_c_descr,ed09_c_somach,ed09_c_controlfreq";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
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
</center>
<script>
function js_sobe() {
 var F = document.getElementById("campos");
 if(F.selectedIndex != -1 && F.selectedIndex > 0) {
  var SI = F.selectedIndex - 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
  F.options[SI + 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_desce() {
 var F = document.getElementById("campos");
 if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
  var SI = F.selectedIndex + 1;
  var auxText = F.options[SI].text;
  var auxValue = F.options[SI].value;
  F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
  F.options[SI - 1] = new Option(auxText,auxValue);
  F.options[SI].selected = true;
 }
}
function js_selecionar() {
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
</script>