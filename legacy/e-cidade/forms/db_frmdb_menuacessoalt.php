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

//MODULO: configuracoes
$cldb_menuacesso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m_descricao");
$clrotulo->label("db05_descr");
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
  var texto=document.form1.m_descricao.value;
  var valor=document.form1.db06_m_codigo.value;
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
   texto=document.form1.m_descricao.value="";
   valor=document.form1.db06_m_codigo.value="";
 document.form1.lanca.onclick = '';
 document.form1.db06_m_codigo.focus();
}
function js_selecionar() {
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}
function js_veri(){
  if(document.form1.db03_descr.value==""){
    alert("Preencha a descrição!");
    return false;
  }
return true;
}
</script>
<form name="form1" method="post" action="" onSubmit="return js_selecionar()">
<table border="0" width="60%">
  <tr>
    <td align="left" nowrap title="<?=@$Tdb06_idtipo?>">
       <?
         db_ancora(@$Ldb06_idtipo,"js_pesquisadb06_idtipo(true);",$db_opcao);
         db_input('db06_idtipo',6,$Idb06_idtipo,true,'text',$db_opcao," onchange='js_pesquisadb06_idtipo(false);'");
         db_input('db05_descr',40,$Idb05_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap >
      <fieldset><Legend>Selecione os Menus</legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tdb06_m_codigo?>" colspan="2">
              <?
                db_ancora(@$Ldb06_m_codigo,"js_pesquisadb06_m_codigo(true);",$db_opcao);
                db_input('db06_m_codigo',6,$Idb06_m_codigo,true,'text',$db_opcao," onchange='js_pesquisadb06_m_codigo(false);'");
                db_input('m_descricao',40,$Im_descricao,true,'text',3,'');
              ?>
	      <input name="lanca" type="button" value="Lançar" <?=($db_botao==false?"disabled":"")?> >
            </td>
	  </tr>
          <tr>
	  <td>
	  <table cellpadding="0" cellspacing="0" border="0" width="100%">
	  <tr align="center">
	    <td align="right" colspan="" width="80%">
              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?
              if(isset($chavepesquisa)){
	        $resulta = $cldb_menupref->sql_record($cldb_menupref->sql_queryacesso("","*"," inner join db_menuacesso on db06_m_codigo = m_codigo", "m_codigo"," db06_idtipo = $chavepesquisa1"));
	        if($cldb_menupref->numrows!=0){
                  $numrows = $cldb_menupref->numrows;
		  for($i = 0;$i < $numrows;$i++) {
		    db_fieldsmemory($resulta,$i);
                    echo "<option value=\"$m_codigo \">$m_descricao</option>\n";
                  }
	        }
              echo "<script>js_trocacordeselect();</script>";
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
           </td>
	   </table>
	 </tr>
      </table>
    </fieldset>
  </td>
</tr>
</table>
<?
if(isset($chavepesquisa)){
  echo "<input type=\"hidden\" name=\"chavepesquisa1\" value=\"$chavepesquisa1\">\n";
}
?>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
<?
if(isset($chavepesquisa)){
  echo "document.form1.db06_m_codigo.value = '';\n";
  echo "document.form1.m_descricao.value = '';\n";
}
?>
function js_pesquisadb06_m_codigo(mostra){
  document.form1.lanca.onclick = "";
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_menupref.php?funcao_js=parent.js_mostradb_menupref1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_menupref.php?pesquisa_chave='+document.form1.db06_m_codigo.value+'&funcao_js=parent.js_mostradb_menupref';
  }
}
function js_mostradb_menupref(chave,erro){
  document.form1.m_descricao.value = chave;
  if(erro==true){
    document.form1.db06_m_codigo.focus();
    document.form1.db06_m_codigo.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
}
function js_mostradb_menupref1(chave1,chave2){
  document.form1.db06_m_codigo.value = chave1;
  document.form1.m_descricao.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisadb06_idtipo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_tipoacesso.php?funcao_js=parent.js_mostradb_tipoacesso1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_tipoacesso.php?pesquisa_chave='+document.form1.db06_idtipo.value+'&funcao_js=parent.js_mostradb_tipoacesso';
  }
}
function js_mostradb_tipoacesso(chave,erro){
  document.form1.db05_descr.value = chave;
  if(erro==true){
    document.form1.db06_idtipo.focus();
    document.form1.db06_idtipo.value = '';
  }
}
function js_mostradb_tipoacesso1(chave1,chave2){
  document.form1.db06_idtipo.value = chave1;
  document.form1.db05_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_db_menuacesso.php?funcao_js=parent.js_preenchepesquisa|0|1';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave,chave1){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&chavepesquisa1="+chave1;
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