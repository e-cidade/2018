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
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconceito->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed37_i_codigo");
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
  $sql = "UPDATE conceito SET
           ed39_i_sequencia = ".($i+1)."
          WHERE ed39_i_codigo = $campos[$i]
         ";
  $query = db_query($sql);
 }
 ?><script>parent.location.href="edu1_formaavaliacao002.php?chavepesquisa=<?=$ed39_i_formaavaliacao?>";</script><?
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted39_c_conceito?>">
   <label for="ed39_c_conceito"><?=@$Led39_c_conceito?></label>
   <?db_input('ed39_c_conceito',3,$Ied39_c_conceito,true,'text',$db_opcao,"")?>
   <label for="ed39_c_nome"><?=@$Led39_c_nome?></label>
   <?db_input('ed39_c_nome',30,$Ied39_c_nome,true,'text',$db_opcao,"")?>
   <br>
   <label for="ed39_c_conceitodescr"><?=@$Led39_c_conceitodescr?></label>
   <?db_input('ed39_c_conceitodescr',100,$Ied39_c_conceitodescr,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td>
   <input id='ed39_i_codigo' name="ed39_i_codigo" type="hidden" value="<?=@$ed39_i_codigo?>">
   <input id='ed39_i_formaavaliacao' name="ed39_i_formaavaliacao" type="hidden" value="<?=@$ed39_i_formaavaliacao?>">
   <input id='btnAcao'  name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
   <input id='cancelar' name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
  </td>
</table>
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed39_i_codigo"=>@$ed39_i_codigo,"ed37_c_descr"=>@$ed37_c_descr,"ed39_i_formaavaliacao"=>@$ed39_i_formaavaliacao,"ed39_c_conceito"=>@$ed39_c_conceito,"ed39_c_conceitodescr"=>@$ed39_c_conceitodescr,"ed39_c_nome"=>@$ed39_c_nome);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clconceito->sql_query("","*","ed39_i_sequencia"," ed39_i_formaavaliacao = $ed39_i_formaavaliacao");
   $cliframe_alterar_excluir->campos  ="ed39_i_codigo,ed39_c_conceito,ed39_c_nome,ed39_c_conceitodescr";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="120";
   $cliframe_alterar_excluir->iframe_width ="690";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
  <td valign="top" align="center">
   <fieldset style="width:90%;"><legend align="center"><b>Ordenar Níveis</b></legend>
   <table border="0" cellspacing="0" cellpading="0">
    <tr>
     <td align="center">
      <select name="campos[]" id="campos" size="4" style="width:125px" multiple>
      <?
       $sql = "SELECT ed39_i_codigo,ed39_c_conceito from conceito where ed39_i_formaavaliacao = $ed39_i_formaavaliacao order by ed39_i_sequencia";
       $query = db_query($sql);
       $linhas = pg_num_rows($query);
       if($linhas>0){
        for($i=0;$i<$linhas;$i++){
        $dados = pg_fetch_array($query);
         echo "<option value=\"".$dados["ed39_i_codigo"]."\">".trim($dados["ed39_c_conceito"])."</option>\n";
        }
       }
      ?>
      </select>
      <br><br>
      <input name="atualizar" type="submit" value="Atualizar" onclick="js_selecionar()">
     </td>
     <td valign="top">
      <br/>
      <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
      <br/>
      <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
     </td>
    </tr>
    <tr>
     <td>
      * Ordem Crescente
     </td>
    </tr>
   </table>
   </fieldset>
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

function js_removerAspas(field) {

  field.observe("blur", function() {

    var sExpressaoRegular = /[\'\"]/g;
    field.value = field.value.replace(sExpressaoRegular, '');
  });

  field.observe('keydown', function(event) {

    var iTecla = event.which;
    if (iTecla == 222) {

      event.preventDefault();
      event.stopPropagation();
      return false;
    }

    return true;
  });
}

js_removerAspas($('ed39_c_conceito'));
js_removerAspas($('ed39_c_nome'));
js_removerAspas($('ed39_c_conceitodescr'));

</script>