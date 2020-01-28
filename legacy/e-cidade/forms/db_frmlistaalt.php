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

//MODULO: caixa
//$clarretipo = new
$cllista->rotulo->label();
$clrotulo =new  rotulocampo;
$clrotulo->label("k00_tipo");
$clrotulo->label("k00_descr");
?>

<script>
function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
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
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
	js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelect() {
  var texto=document.form1.k00_descr.value;
  var valor=document.form1.k00_tipo.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor || F.options[x].text == texto){
        testa = true;
	break;
      }
    }
    if(testa == false){
      F.options[F.length] = new Option(texto,valor);
      js_trocacordeselect();
    }
 }
   texto=document.form1.k00_descr.value="";
   valor=document.form1.k00_tipo.value="";
 document.form1.lanca.onclick = '';
}
function js_selecionar() {
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
function js_veri(){
  if(document.form1.k00_descr.value==""){
    alert("Preencha a descrição!");
    return false;
  }
return true;
}
</script>


<form name="form1" method="post" onSubmit="return js_selecionar()" >
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk60_codigo?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?=@$Lk60_codigo?>
    </td>
    <td>
<?
if ($db_opcao == 1){
  $xopcao = 3;
}else{
  $xopcao = $db_opcao;
}
db_input('k60_codigo',6,$Ik60_codigo,true,'text',$xopcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk60_descr?>">
       <?=@$Lk60_descr?>
    </td>
    <td>
<?
db_textarea('k60_descr',0,70,$Ik60_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk60_tipo?>">
       <?=@$Lk60_tipo?>
    </td>
    <td>
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('k60_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk60_datadeb?>">
       <?=@$Lk60_datadeb?>
    </td>
    <td>
<?
db_inputdata('k60_datadeb',@$k60_datadeb_dia,@$k60_datadeb_mes,@$k60_datadeb_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>

<table>
  <tr>
    <td nowrap >
      <fieldset><Legend>Selecione o Tipo de Débito</legend>
      <table border="0">
         <tr>
           <td nowrap title="<?=@$Tk00_tipo?>" colspan="2">
            <?
              db_ancora(@$Lk00_tipo,"js_pesquisatipo(true);",$db_opcao);
            ?>
            <?
              db_input('k00_tipo',8,$Ik00_tipo,true,'text',$db_opcao," onchange='js_pesquisatipo(false);'")
            ?>
            <?
              db_input('k00_descr',25,$Ik00_descr,true,'text',3,'')
            ?>
	    <input name="lanca" type="button" value="Lançar"  <?=($db_botao==false?"disabled":"")?> >
           </td>
	 </tr>
         <tr>
	   <td align="right" colspan="" width="80%">

              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?
              if(isset($chavepesquisa)){

	 $resulta = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa,"","k00_tipo,k00_descr","k00_tipo"));

		 if($clarretipo->numrows!=0){
                      $numrows = $clarretipo->numrows;
		    for($i = 0;$i < $numrows;$i++) {
		      db_fieldsmemory($resulta,$i);
                      echo "<option value=\"$k00_tipo\">$k00_descr</option>";
                   }

		 }


              }
              ?>

             </select>
		   </td>
            <td align="left" valign="middle" width="20%">
			        <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
              <br/><br/>
              <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
              <br/><br/>
			        <img style="cursor:hand" onClick="js_excluir()" src="skins/img.php?file=Controles/bt_excluir.png" />
      	   </td>
         </tr>
      </table>
      </fieldset>
    </td>
  </tr>
</table>

<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisatipo(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_arretipo.php?funcao_js=parent.js_mostratipo1|k00_tipo|k00_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_arretipo.php?pesquisa_chave='+document.form1.k00_tipo.value+'&funcao_js=parent.js_mostratipo';
  }
}
function js_mostratipo1(chave1,chave2){
  document.form1.k00_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_mostratipo(chave,erro){
  document.form1.k00_descr.value = chave;
  if(erro==true){
    document.form1.k00_tipo.focus();
    document.form1.k00_tipo.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Tipos" ;

}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>