<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("dbforms/db_funcoes.php");
include("classes/db_tipoisen_classe.php");
$cltipoisen = new cl_tipoisen;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt12');
$clrotulo->label('DBtxt13');
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('j45_tipo');
$clrotulo->label('j45_descr');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
  var texto=document.form1.j45_descr.value;
  var valor=document.form1.j45_tipo.value;
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
   texto=document.form1.j45_descr.value="";
   valor=document.form1.j45_tipo.value="";
 document.form1.lanca.onclick = '';
}

function js_emite(){

//  itemselecionado = 0;
//  numElems = document.form1.ordem.length;
//  for (i=0;i<numElems;i++) {
//      if (document.form1.ordem[i].checked) itemselecionado = i;
//  }
//  ordem = document.form1.ordem[itemselecionado].value;

  var mes1 = new Number(document.form1.DBtxt21_mes.value);
  var val1 = new Date(document.form1.DBtxt21_ano.value,mes1-1,document.form1.DBtxt21_dia.value,0,0,0);
  var mes2 = new Number(document.form1.DBtxt22_mes.value);
  var val2 = new Date(document.form1.DBtxt22_ano.value,mes2-1,document.form1.DBtxt22_dia.value,0,0,0);
  if(val1.valueOf() > val2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }

  var valini = new Number(document.form1.DBtxt12.value);
  var valfin = new Number(document.form1.DBtxt13.value);
  if(valini.valueOf() > valfin.valueOf()){
    alert('Ano inicial maior que ano final. Verifique!');
    return false;
  }


  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }

  var H = document.getElementById("campos").options;
  if(H.length > 0){
     campo = 'campo=';
     virgula = '';
     for(var i = 0;i < H.length;i++) {
       campo += virgula+H[i].value;
       virgula = '-';
     }
  }else{
     campo = '';
  }

  jan = window.open('cad2_relisentos002.php?'+campo+'&order='+document.form1.order.value+'&isencoes='+document.form1.isencoes.value+'&datai='+document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value+'&dataf='+document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value+'&anoini='+document.form1.DBtxt12.value+'&anofin='+document.form1.DBtxt13.value+'&tipodata='+document.form1.tipodata.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
//alert('Deu Certo'+campo);
}

</script>

<?
if(isset($ordem)){
  if(isset($campos)){
    $xcampo = '';
    $tamanho=sizeof($campos);
    $virgula = '';
    for($i=0; $i < $tamanho; $i++){
      $xcampo .= $virgula.$campos[$i];
      $virgula = "-";
    }
  }
}
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <tr>
    <td>&nbsp;
    </td>
  </tr>
  <table  border="0" align="center">
    <form name="form1" method="post" action="" >
      <tr>
        <td>Tipo da data:&nbsp;&nbsp;
        <select name="tipodata">
			    <option value="dtinc">Data de Inclusão da Isenção</option>
			    <option value="dtini">Data de Inicio da Isenção</option>
			    <option value="dtfim">Data de Fim da Isenção</option>
	     </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" title="Intervalo entre as datas de inclusão das isenções">Período:&nbsp;&nbsp;
	  <?
	  db_inputdata('DBtxt21','','','',true,'text',4)
	  ?>
	  &nbsp;&nbsp;&nbsp;
	  Até
	  <?
	  db_inputdata('DBtxt22','','','',true,'text',4)
	  ?>
      </tr>
      <tr>
        <td colspan="2" title="Anos das isenções">Exercícios das Isenções :&nbsp;&nbsp;
          <?
	    db_input('DBtxt12',15,$IDBtxt13,true,'text',2);
	  ?>
        &nbsp;&nbsp;
        Até
          <?
	    db_input('DBtxt13',15,$IDBtxt13,true,'text',2);
	  ?>

        </td>
      </tr>
      <tr>
	<td colspan="2">
	<table border="0">
	<tr>
	<td nowrap width="50">
	  <fieldset>
	  <legend><strong>Tipo de isenção: </strong></legend>
	  <select name="isencoes">
	    <option value="cad">somente cadastradas</option>
	    <option value="calc">somente calculadas</option>
	  </select>
	  </fieldset>
	</td>
	<td nowrap width="50">
	  <fieldset>
	  <legend><strong>Ordenar: </strong></legend>
	  <select name="order">
	    <option value="z01_nome">por nome</option>
	    <option value="matricula">por matrícula</option>
	    <option value="e">por endereço</option>
	  </select>
	  </fieldset>
	</td>
      </tr>
<tr>
<td colspan="2">
<table align="center" >
   <tr>
    <td nowrap title="Escolha os tipos de isenções a serem listados ou deixe em branco para listar todos" >
      <fieldset><Legend>Selecione os Tipos</legend>
      <table border="0">
         <tr>
           <td nowrap title="<?=@$Tj45_tipo?>" colspan="2">
            <?
              db_ancora(@$Lj45_tipo,"js_pesquisaisencao(true);",2);
            ?>
            <?
              db_input('j45_tipo',8,$Ij45_tipo,true,'text',2," onchange='js_pesquisaisencao(false);'")
            ?>
            <?
              db_input('j45_descr',25,$Ij45_descr,true,'text',3,'')
            ?>
	    <input name="lanca" type="button" value="Lançar" >
           </td>
	 </tr>
         <tr>
	   <td align="right" colspan="" width="80%">

              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?
              if(isset($chavepesquisa)){

	 $resulta = $cltipoisen->sql_record($cltipoisen->sql_query($chavepesquisa,"","j45_tipo,j45_descr",""));

		 if($cltipoisen->numrows!=0){
                      $numrows = $cltipoisen->numrows;
		    for($i = 0;$i < $numrows;$i++) {
		      db_fieldsmemory($resulta,$i);
                      echo "<option value=\"$j45_tipo \">$j45_descr</option>";
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
</td>
</tr>
<!--      <tr>
        <td title="Ordem do relatório">Ordenar por :&nbsp;&nbsp;
          <label for="ordem_valor1" id="lordem1"><input id="ordem_valor1" type="radio" name="ordem" value="z01_nome" checked>Alfabética&nbsp;&nbsp;</label>
          <label for="ordem_valor2" id='lordem2'><input id="ordem_valor2" type="radio" name="ordem" value="tipo">Tipo&nbsp;&nbsp;</label>
          <label for="ordem_valor3" id="lordem4"><input id="ordem_valor4" type="radio" name="ordem" value="numerica">Numérica&nbsp;&nbsp;</label>
          <label for="ordem_valor"  id='lordem3' ><input type="radio" id="ordem_valor" name="ordem" value="valor">Valor&nbsp;&nbsp;</label>

        </td>
        <td title="Tipo de ordem do relatório">Em ordem :&nbsp;&nbsp;
          <input type="radio" name="ordemtipo" value="asc" checked>Ascendente&nbsp;&nbsp;&nbsp;
          <input type="radio" name="ordemtipo" value="desc" >Descendente
        </td>
      </tr>-->
      <tr height="40">
         <td align="center" colspan="2">
           <input  name="emite2" id="emite2" type="button" value="Processar" onClick="js_emite();">
	 </td>
      </tr>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
function js_pesquisaisencao(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_tipoisen.php?funcao_js=parent.js_mostraisencao1|j45_tipo|j45_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_tipoisen.php?pesquisa_chave='+document.form1.j45_tipo.value+'&funcao_js=parent.js_mostraisencao';
  }
}
function js_mostraisencao(chave,erro){
  document.form1.j45_descr.value = chave;
  if(erro==true){
    document.form1.j45_tipo.focus();
    document.form1.j45_tipo.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;

}
function js_mostraisencao1(chave1,chave2){
  document.form1.j45_tipo.value = chave1;
  document.form1.j45_descr.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisa(){
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

if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";
}
?>