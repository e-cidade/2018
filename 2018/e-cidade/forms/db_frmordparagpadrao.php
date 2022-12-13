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
$cldb_documentopadrao->rotulo->label();
$clrotulo =new  rotulocampo;
$clrotulo->label("db61_codparag");
$clrotulo->label("db61_descr");
$clrotulo->label("db08_descr");

if ($db_opcao == 1) {
  $db60_instit = db_getsession("DB_instit");
}

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
  var texto=document.form1.db61_descr.value;
  var valor=document.form1.db61_codparag.value;
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
   texto=document.form1.db61_descr.value="";
   valor=document.form1.db61_codparag.value="";
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
  if(document.form1.db60_descr.value==""){
    alert("Preencha a descrição!");
    return false;
  }
return true;
}
</script>

<form name="form1" method="post" onSubmit="return js_selecionar()">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?//=@$Tdb60_coddoc?>">
       <?//=@$Ldb60_coddoc?>
    </td>
    <td>
<?
db_input('db60_coddoc',8,$Idb60_coddoc,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?//=@$Tdb60_descr?>">
       <?//=@$Ldb60_descr?>
    </td>
    <td>
<?
db_input('db60_descr',40,$Idb60_descr,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>



  <tr>
    <td nowrap title="<?//=@$Tdb60_tipodoc?>">
       <?
      // db_ancora(@$Ldb60_tipodoc,"js_pesquisadb60_tipodoc(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('db60_tipodoc',10,$Idb60_tipodoc,true,'hidden',$db_opcao," onchange='js_pesquisadb60_tipodoc(false);'")
?>
       <?
db_input('db08_descr',40,$Idb08_descr,true,'hidden',3,'')
       ?>
    </td>
  </tr>

  </table>
  </center>
<table>
  <tr>
    <td nowrap >
      <fieldset><Legend>Ordena os Páragrafos</legend>
      <table border="0">
         <tr>
           <td nowrap title="<?//=@$Tdb61_codparag?>" colspan="2">
            <?
             // db_ancora(@$Ldb61_codparag,"js_pesquisadb61_codparag(true);",$db_opcao);
            ?>
            <?
             // db_input('db61_codparag',8,$Idb61_codparag,true,'text',$db_opcao," onchange='js_pesquisadb61_codparag(false);'")
            ?>
            <?
             // db_input('db61_descr',25,$Idb61_descr,true,'text',3,'')
            ?>
	    <!--<input name="lanca" type="button" value="Lançar"  <?=($db_botao==false?"disabled":"")?> >-->
           </td>
	 </tr>
         <tr>
	   <td align="right" colspan="" width="80%">

              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?
              if(isset($chavepesquisa)){

	 $resulta = $cldb_docparagpadrao->sql_record($cldb_docparagpadrao->sql_query($chavepesquisa,"","db_docparagpadrao.*,db61_descr","db62_ordem"));

		 if($cldb_docparagpadrao->numrows!=0){
                      $numrows = $cldb_docparagpadrao->numrows;
		    for($i = 0;$i < $numrows;$i++) {
		      db_fieldsmemory($resulta,$i);
                      echo "<option value=\"$db62_codparag \">$db61_descr</option>";
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
	          </td>
         </tr>
      </table>
      </fieldset>
    </td>
  </tr>
</table>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_veri();" >

</form>
<script>
function js_pesquisadb61_codparag(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_paragrafopadrao.php?funcao_js=parent.js_mostradb_paragrafopadrao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_paragrafopadrao.php?pesquisa_chave='+document.form1.db61_codparag.value+'&funcao_js=parent.js_mostradb_paragrafopadrao';
  }
}
function js_mostradb_paragrafopadrao(chave,erro){
  document.form1.db61_descr.value = chave;
  if(erro==true){
    document.form1.db61_codparag.focus();
    document.form1.db61_codparag.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;

}
function js_mostradb_paragrafopadrao1(chave1,chave2){
  document.form1.db61_codparag.value = chave1;
  document.form1.db61_descr.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisa(){
<?
   if($db_opcao !=1){
    echo " db_iframe.jan.location.href = 'func_db_documentopadrao.php?funcao_js=parent.js_preenchepesquisa|0';";
   }
?>

  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}




function js_pesquisadb60_tipodoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_tipodoc','func_db_tipodoc.php?funcao_js=parent.js_mostradb_tipodoc1|db08_codigo|db08_descr','Pesquisa',true);
  }else{
     if(document.form1.db60_tipodoc.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_tipodoc','func_db_tipodoc.php?pesquisa_chave='+document.form1.db60_tipodoc.value+'&funcao_js=parent.js_mostradb_tipodoc','Pesquisa',false);
     }else{
       document.form1.db08_descr.value = '';
     }
  }
}
function js_mostradb_tipodoc(chave,erro){
  document.form1.db08_descr.value = chave;
  if(erro==true){
    document.form1.db60_tipodoc.focus();
    document.form1.db60_tipodoc.value = '';
  }
}
function js_mostradb_tipodoc1(chave1,chave2){
  document.form1.db60_tipodoc.value = chave1;
  document.form1.db08_descr.value = chave2;
  db_iframe_db_tipodoc.hide();
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