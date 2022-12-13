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
include("classes/db_arretipo_classe.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

$clpostgresqlutils = new PostgreSQLUtils;
$clarretipo        = new cl_arretipo;
$clrotulo          = new rotulocampo;
$clarretipo->rotulo->label();
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');

db_postmemory($HTTP_POST_VARS);
$instit = db_getsession("DB_instit");

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false;
  $db_opcao = 3;
} else {

  $db_botao = true;
  $db_opcao = 4;
}
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

function js_valor(){

  if (document.form1.quebrar.value == 'f'){
    document.getElementById('lordem3').style.visibility='visible';
  }else{
    document.getElementById('lordem3').style.visibility='hidden';
  }

}

function js_verifica(){
  var val1 = new Number(document.form1.DBtxt10.value);
  var val2 = new Number(document.form1.DBtxt11.value);
  if(val1.valueOf() >= val2.valueOf()){
    alert('Valor máximo menor que o valor mínimo.');
    return false;
  }
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}

function js_emite(){
  itemselecionado = 0;
  numElems = document.form1.grupo.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.grupo[i].checked) itemselecionado = i;
  }
  grupo = document.form1.grupo[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordemtipo.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordemtipo[i].checked) itemselecionado = i;
  }
  ordemtipo = document.form1.ordemtipo[itemselecionado].value;

  itemselecionado = 0;
  numElems = document.form1.origem.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.origem[i].checked) itemselecionado = i;
  }
  origem = document.form1.origem[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordem.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordem[i].checked) itemselecionado = i;
  }
  ordem = document.form1.ordem[itemselecionado].value;


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

  jan = window.open('cai2_devedores_002.php?'+campo+
                    '&ordemtipo='+ordemtipo+
                    '&origem='+origem+
                    '&data='+document.form1.data_ano.value+
                    '-'+document.form1.data_mes.value+
                    '-'+document.form1.data_dia.value+
                    '&quebrar='+document.form1.quebrar.value+
                    '&grupo='+grupo+
                    '&ordem='+ordem+
                    '&numerolista='+document.form1.numerolista2.value+
                    '&valormaximo='+document.form1.DBtxt11.value+
                    '&valorminimo='+document.form1.DBtxt10.value,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
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
?>
<script>

function js_emite1(){
  jan = window.open('cai2_devedores_002.php?<?=(isset($xcampo)?'campo='.$xcampo.'&':'')?>ordemtipo=<?=$ordemtipo?>origem=<?=$origem?>&data=<?=$data_ano.'-'.$data_mes.'-'.$data_dia?>&quebrar='+document.form1.quebrar.value+'&grupo=<?=$grupo?>&ordem=<?=$ordem?>&numerolista=<?=$numerolista2?>&valormaximo=<?=$DBtxt11?>&valorminimo=<?=$DBtxt10?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<?
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
  <table  border="1" align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
        <td title="Data da Geração do Cálculo">Data do Cálculo:&nbsp;&nbsp;
				  <?
					  $sql = "select k115_data as data
				              from datadebitos
				              where k115_instit = $instit
				              order by k115_data desc limit 1";
					  $result = db_query($sql);
					  if (pg_numrows($result) > 0 ) {
					     db_fieldsmemory($result,0);
					     $data_ano = substr($data,0,4);
					     $data_mes = substr($data,5,2);
					     $data_dia = substr($data,8,2);
					  } else {
					     $data_ano = '';
					     $data_mes = '';
					     $data_dia = '';
					  }

					  db_inputdata('data',$data_dia,$data_mes,$data_ano,true,'text',$db_opcao);
				  ?>
        </td>
        <td title="Quantidade de contribuintes a ser listado, ou zero para todos">Quantidade a Listar:&nbsp;&nbsp;
          <?
            db_input('numerolista2',12,'',true,'text',$db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td title="Intervalo de valores a serem listados">Valores de:&nbsp;&nbsp;
          <?
	          db_input('DBtxt10',15,$IDBtxt10,true,'text',$db_opcao);
	        ?>
        &nbsp;&nbsp;
        Até
          <?
	          db_input('DBtxt11',15,$IDBtxt11,true,'text',$db_opcao);
	        ?>
        </td>
        <td >Agrupar:&nbsp;&nbsp;
          <input type="radio" name="grupo" value="nome" checked >Nome</font>
          <input type="radio" name="grupo" value="inscr">Inscrição&nbsp;&nbsp;
          <input type="radio" name="grupo" value="matric">Matrícula&nbsp;&nbsp;
          <input type="radio" name="grupo" value="tipo">Tipo</font>
        </td>
      </tr>
<tr>
<td colspan="2">
<table align="center" >
   <tr>
     <td nowrap title="Escolha os tipos de débitos a serem listados ou deixe em branco para listar todos" >
       <fieldset>
         <Legend>Selecione os Tipos</legend>
       <table border="0">
         <tr>
           <td nowrap title="<?=@$Tk00_tipo?>" colspan="2">
            <?
              db_ancora(@$Lk00_tipo,"js_pesquisadb02_idparag(true);",$db_opcao);
              db_input('k00_tipo',8,$Ik00_tipo,true,'text',$db_opcao," onchange='js_pesquisadb02_idparag(false);'");
              db_input('k00_descr',25,$Ik00_descr,true,'text',3,'');
            ?>
	            <input name="lanca" type="button" value="Lançar" >
           </td>
	       </tr>
         <tr>
	   <td align="right" colspan="" width="80%">

              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?
	              if(isset($chavepesquisa)){

									 $resulta = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa,"","k00_tipo,k00_descr",""));

										 if($clarretipo->numrows!=0){

								        $numrows = $clarretipo->numrows;
										    for($i = 0;$i < $numrows;$i++) {
										      db_fieldsmemory($resulta,$i);
								          echo "<option value=\"$k00_tipo \">$k00_descr</option>";
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
      <tr>
        <td title="Sim - agrupa por tipo de débito dentro da opção escolhida (matrícula,inscrição ou numcgm) <?="\n"?>Não - totaliza pela opção escolhida (matrícula,inscrição ou numcgm)">Quebrar por Tipo:&nbsp;&nbsp;
          <?
		        $aQuebrar = array("f" => "Não",
		                          "t" => "Sim");
		        db_select("quebrar", $aQuebrar, true, $db_opcao, " onchange='js_valor();'");
		      ?>
        </td>
        <td title="Ordem do relatório">Ordenar por :&nbsp;&nbsp;
          <label for="ordem_valor1" id="lordem1">
            <input id="ordem_valor1" type="radio" name="ordem" value="z01_nome" checked>Alfabética&nbsp;&nbsp;
          </label>
          <label for="ordem_valor2" id='lordem2'>
            <input id="ordem_valor2" type="radio" name="ordem" value="tipo">Tipo&nbsp;&nbsp;
          </label>
          <label for="ordem_valor3" id="lordem4">
            <input id="ordem_valor4" type="radio" name="ordem" value="numerica">Numérica&nbsp;&nbsp;
          </label>
          <label for="ordem_valor"  id='lordem3' >
            <input type="radio" id="ordem_valor" name="ordem" value="valor">Valor&nbsp;&nbsp;</label>
        </td>
      </tr>
      <tr>
        <td title="Origem dos dados">Origem :&nbsp;&nbsp;
          <input type="radio" name="origem" value="vencidos" checked>Vencidos&nbsp;&nbsp;&nbsp;
          <input type="radio" name="origem" value="tudo" >Tudo
	 </td>
        <td title="Tipo de ordem do relatório">Em ordem :&nbsp;&nbsp;
          <input type="radio" name="ordemtipo" value="asc" checked>Ascendente&nbsp;&nbsp;&nbsp;
          <input type="radio" name="ordemtipo" value="desc" >Descendente
        </td>
      </tr>
      <tr height="40">
         <td align="center" colspan="2">
           <input name="emite2" id="emite2" type="button" value="Processar" onClick="js_emite();"
                  <?=($db_botao ? '' : 'disabled')?>>
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
function js_pesquisadb02_idparag(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_arretipo.php?funcao_js=parent.js_mostradb_paragrafo1|k00_tipo|k00_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_arretipo.php?pesquisa_chave='+document.form1.k00_tipo.value+'&funcao_js=parent.js_mostradb_paragrafo';
  }
}
function js_mostradb_paragrafo(chave,erro){
  document.form1.k00_descr.value = chave;
  if(erro==true){
    document.form1.k00_tipo.focus();
    document.form1.k00_tipo.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;

}
function js_mostradb_paragrafo1(chave1,chave2){
  document.form1.k00_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
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