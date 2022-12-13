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
include("classes/db_notificacao_classe.php");
include("classes/db_notidebitos_classe.php");
include("classes/db_notimatric_classe.php");
include("classes/db_notiinscr_classe.php");
include("classes/db_notinumcgm_classe.php");
include("classes/db_notiusu_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_parcontrib_classe.php");
include("classes/db_editalrua_classe.php");
include("classes/db_contricalc_classe.php");
include("classes/db_contrinot_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$clrotulo->label('d07_matric');
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clnotificacao   = new cl_notificacao;
$cleditalrua     = new cl_editalrua;
$clnotidebitos   = new cl_notidebitos;
$clnotimatric    = new cl_notimatric;
$clnotiinscr     = new cl_notiinscr;
$clnotinumcgm    = new cl_notinumcgm;
$clnotiusu       = new cl_notiusu;
$clcontrib       = new cl_contrib;
$clparcontrib    = new cl_parcontrib;
$clcontricalc    = new cl_contricalc;
$clcontrinot     = new cl_contrinot;
$instit = db_getsession("DB_instit");
$clnotificacao->k50_instit = $instit;
$db_opcao = 1;
$db_botao = true;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  $xcampo = '';
  if(isset($campos)){
    $xcampo = ' and j01_matric in (';
    $tamanho=sizeof($campos);
    $virgula = '';
    for($i=0; $i < $tamanho; $i++){
      $xcampo .= $virgula.$campos[$i];
      $virgula = " , ";
    }
    $xcampo .= ') ';
  }
  $xtipo='';
  if ($tipo == 1){
    $xtipo = ' and d08_notif is null ';
  }

  db_inicio_transacao();
  $erro1 = false;

  $sqlmatricula = $clcontrib->sql_query_not("","","j01_matric,d08_notif",""," d07_contri = ".$d02_contri." and j01_matric in (select d09_matric from contricalc where d09_contri = ".$d02_contri.") ".$xcampo.$xtipo);
  $resultmatric = db_query($sqlmatricula);
  if (pg_numrows($resultmatric) == 0){
    echo "<script>alert('Não existem notificações a serem geradas para esta contribuição!')</script>";
    echo "<script>location.href='con2_geranotif001.php?d02_contri=$d02_contri'</script>";
    exit;
  }

  for($xx = 0;$xx < pg_numrows($resultmatric);$xx++){
    db_fieldsmemory($resultmatric,$xx);

    $clnotificacao->k50_dtemite = date('Y-m-d',db_getsession('DB_datausu'));
    $clnotificacao->k50_procede = $k51_procede;
    $clnotificacao->k50_obs     = $k51_descr;
    $clnotificacao->incluir(null);

		$erromsg = $clnotificacao->erro_msg;
    if($clnotificacao->erro_status =="0"){
		  $erromsg = "notificacao - ".$clnotificacao->erro_msg;
      $erro1 = true;
			break;
    }
    $clnotiusu->k52_id_usuario = db_getsession("DB_id_usuario");
    $clnotiusu->k52_data       = date('Y-m-d');
    $clnotiusu->k52_hora       = date('H:i');
    $clnotiusu->incluir($clnotificacao->k50_notifica);
    if($clnotiusu->erro_status =="0"){
      $erro1 = true;
      $erromsg = "notiusu - ".$clnotiusu->erro_msg;
			break;
    }


    $clnotimatric->incluir($clnotificacao->k50_notifica,$j01_matric);
    if($clnotimatric->erro_status =="0"){
      $erro1 = true;
      $erromsg = "notimatric - ".$clnotimatric->erro_msg;
			break;
    }
    $resultnumpre = $clcontricalc->sql_record($clcontricalc->sql_query(null," d09_contri,d09_numpre ",null," d09_contri = $d02_contri and d09_matric = $j01_matric"));
    for ($xy = 0;$xy < pg_numrows($resultnumpre);$xy++){

      db_fieldsmemory($resultnumpre,$xy);
      $resultarrec = db_query("select k00_numpre,k00_numpar from arrecad where k00_numpre = $d09_numpre");
      if (pg_numrows($resultarrec) > 0){
        for($xarrec = 0;$xarrec < pg_numrows($resultarrec);$xarrec++){

          db_fieldsmemory($resultarrec,$xarrec);
          $clnotidebitos->k53_notifica = $clnotificacao->k50_notifica;
          $clnotidebitos->k53_numpre   = $k00_numpre;
          $clnotidebitos->k53_numpar   = $k00_numpar;
          $clnotidebitos->incluir($clnotificacao->k50_notifica,$k00_numpre,$k00_numpar);
          if($clnotidebitos->erro_status =="0"){
            $erro1 = true;
            $erromsg = "notidebitos - ".$clnotidebitos->erro_msg;
			      break;
          }
        }
      }

     $resultcontrinot = $clcontrinot->sql_record($clcontrinot->sql_query(null,"*",null,"contrib.d07_contri = $d02_contri and contrib.d07_matric = $j01_matric and notificacao.k50_instit = $instit and notificacao.k50_notifica = ".$clnotificacao->k50_notifica));
      if ($clcontrinot->numrows == 0 ){
				$rsContricalc = db_query(" select d09_sequencial from contricalc where contricalc.d09_contri = $d02_contri and d09_matric = $j01_matric ");
				if (pg_numrows($rsContricalc) > 0 ){

					db_fieldsmemory($rsContricalc,0);
				}else{
					$erro1   = true;
          $erromsg = " contrinot - calculo da contribuicao:$d02_contri nao encontrado para a matricula:$d09_matric ";
          break;
				}
 				$clcontrinot->d08_contricalc = $d09_sequencial;
 				$clcontrinot->d08_notif      = $clnotificacao->k50_notifica;
        $clcontrinot->incluir(null);
        if($clcontrinot->erro_status =="0"){
          $erro1 = true;
          $erromsg = "contrinot - ".$clcontrinot->erro_msg;
		      break;
        }
      }
    }
		if ($erro1) {
			break;
		}
  }

  db_fim_transacao($erro1);

  if ($erro1 == false){
    db_msgbox('Processamento Concluído Com Sucesso!');
  }else{
    db_msgbox($erromsg);
	}
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
  var texto=document.form1.z01_nome.value;
  var valor=document.form1.d07_matric.value;
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
  texto=document.form1.z01_nome.value="";
  valor=document.form1.d07_matric.value="";
  document.form1.lanca.onclick = '';
}
function js_verifica(){
  var val1 = new Number(document.form1.d02_contri.value);
  if(val1.valueOf() < 1){
    alert('Selecione uma contribuição de melhoria.');
    return false;
  }
  var val2 = new Number(document.form1.k51_procede.value);
  if(val2.valueOf() < 1){
    alert('Selecione uma procedência para a notificacao.');
    return false;
  }
  var F = document.getElementById("campos").options;
  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }
  return true;
}





function js_emite(){

  var texto=document.form1.z01_nome.value;
  var valor=document.form1.d07_matric.value;
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
  texto=document.form1.z01_nome.value="";
  valor=document.form1.d07_matric.value="";
  document.form1.lanca.onclick = '';


  //  jan = window.open('cai2_emitenotif002.php?lista='+document.form1.k60_codigo.value+'&opcao='+document.form1.opcao.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  //  jan.moveTo(0,0);
}
</script>
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

<table  align="center">
<form name="form1" method="post" action="" onsubmit="return js_verifica();" >
<tr>
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>

<tr>
<td colspan="2" align="left" nowrap title="<?=@$Td02_contri?>">
<?
db_ancora(@$Ld02_contri,"js_contri(true);",$db_opcao);
?>
&nbsp;&nbsp;&nbsp;
<?
db_input('d02_contri',6,$Id02_contri,true,'text',$db_opcao," onchange='js_contri(false);'");
?>
</td>
</tr>

<tr >
<td align="right" nowrap title="<?=@$Tk51_procede?>" >
<?
db_ancora(@$Lk51_procede,"js_pesquisanotitipo(true);",4)
?>
</td>
<td align="left">&nbsp;&nbsp;&nbsp;
<?
if(empty($k51_procede)){
  $result01 = $clparcontrib->sql_record($clparcontrib->sql_query("","d12_notitipo as k51_procede,k51_descr"));
  $linhascontrib = $clparcontrib->numrows;
  if($linhascontrib>0){
    db_fieldsmemory($result01,0);
  }

}
db_input('k51_procede',4,$Ik51_procede,true,'text',4,"onchange='js_pesquisanotitipo(false);'")
?>
<?
db_input('k51_descr',40,$Ik51_descr,true,'text',3,'')
?>
</td>
</tr>

<tr >
<td align="right"> <strong>Seleção :<strong>
</td>
<td align="left">&nbsp;&nbsp;&nbsp;
<?
$x = array("1"=>"Não Notificados","2"=>"Todos");
db_select('tipo',$x,true,2);
?>
</td>
</tr>


<tr>
<td colspan="2">
<table align="center" >
<tr>
<td nowrap title="Escolha os contribuintes a serem notificados ou deixe em branco para listar todos" >
<fieldset><Legend>Selecione os Contribuintes</legend>
<table border="0">

<tr>
<td nowrap title="<?=@$Td07_matric?>" colspan="2">
<?
db_ancora($Ld07_matric,"js_contrib(true);",2);
?>
<?
db_input('d07_matric',8,'',true,'text',2," onchange='js_contrib(false);'")
?>
<?
db_input('z01_nome',25,'',true,'text',3,'')
?>
<input name="lanca" type="button" value="Lançar" >
</td>
</tr>
<tr>
<td align="right" colspan="" width="80%">

<select name="campos[]" id="campos" size="7" style="width:250px" multiple>
<?
/*              if(isset($chavepesquisa)){
  $sql = "select matric as codigo,
  numcgm,
  z01_nome as descr,
  sum(valor_vencidos)
  from listadeb a
  inner join devedores b on a.k61_numpre = b.numpre
  inner join cgm on z01_numcgm = b.numcgm
  where k61_codigo = 3 and matric = $codigo
  group by matric,numcgm,z01_nome";
  $resulta = db_query($sql);
  if(pg_numrows($resulta)!=0){
    $numrows = pg_numrows($resulta);
    for($i = 0;$i < $numrows;$i++) {
      db_fieldsmemory($resulta,$i);
      echo "<option value=\"$codigo \">$descr</option>";
    }

  }


}
*/              ?>

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
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>
<tr>
<td colspan="2" align = "center">
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
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


function js_pesquisanotitipo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_notitipo.php?funcao_js=parent.js_mostranotitipo1|k51_procede|k51_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_notitipo.php?pesquisa_chave='+document.form1.k51_procede.value+'&funcao_js=parent.js_mostranotitipo';
  }
}
function js_mostranotitipo(chave,erro){
  document.form1.k51_descr.value = chave;
  if(erro==true){
    document.form1.k51_descr.focus();
    document.form1.k51_descr.value = '';
  }
}
function js_mostranotitipo1(chave1,chave2){
  document.form1.k51_procede.value = chave1;
  document.form1.k51_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisalista(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
  }
}

function js_contrib(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_contribalt.php?contribuicao='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontrib1|0|z01_nome';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_contribalt.php?contribuicao='+document.form1.d02_contri.value+'&pesquisa_chave='+document.form1.d07_matric.value+'&funcao_js=parent.js_mostracontrib';
    //     db_iframe.mostraMsg();
    //     db_iframe.show();
    //     db_iframe.focus();
  }
}
function js_mostracontrib(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.d07_matric.focus();
    document.form1.d07_matric.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
  parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;

}
function js_mostracontrib1(chave1,chave2){
  document.form1.d07_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}


function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalrua.php?funcao_js=parent.js_mostracontri1|d02_contri','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalrua.php?pesquisa_chave='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){
    alert("Contribuição inválida.");
    document.form1.d02_contri.focus();
  }
}
function js_mostracontri1(chave1){
  document.form1.d02_contri.value = chave1;
  db_iframe.hide();
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
if(isset($db_opcao)){
  echo "<script>  js_emite();  </script>";
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>