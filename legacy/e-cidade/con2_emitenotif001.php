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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('d02_contri');
$clrotulo->label('d07_matric');
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$db_botao = true;
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
  if(F.length ==1)
     F.options[0].selected = true;

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
       alert('Escolha uma contribuição!');
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
    return true;
}

function js_emiteseed(){

    var val1 = new Number(document.form1.d02_contri.value);
    if(val1.valueOf() < 1){
       alert('Escolha uma contribuição!');
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
//    return true;



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



  jan = window.open('con2_emitenotif004.php?contribuicao='+document.form1.d02_contri.value+'&'+campo+'&tipo='+document.form1.tipo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_emitear(){

    var val1 = new Number(document.form1.d02_contri.value);
    if(val1.valueOf() < 1){
       alert('Escolha uma contribuição!');
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
//    return true;



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



  jan = window.open('con2_emitenotif_ar_001.php?contribuicao='+document.form1.d02_contri.value+'&'+campo+'&tipo='+document.form1.tipo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}




function js_emite(tiporel){

    var val1 = new Number(document.form1.d02_contri.value);
    if(val1.valueOf() < 1){
       alert('Escolha uma contribuição!');
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
//    return true;



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



  jan = window.open('con2_emitenotif002.php?tiporel='+tiporel+'&contribuicao='+document.form1.d02_contri.value+'&'+campo+'&tipo='+document.form1.tipo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
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
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="<?=@$Td02_contri?>" >
          <?
	   db_ancora(@$Ld02_contri,"js_contri(true);",4)
          ?>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
	  db_input('d02_contri',4,$Id02_contri,true,'text',4,"onchange='js_contri(false);'");
          ?>
        </td>
      </tr>

      <tr >
        <td> <strong>Opção de Seleção :<strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
	  $x = array("2"=>"Somente Selecionados","3"=>"Menos os Selecionados");
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
         <input name="db_opcao" type="button" id="db_opcao" value="Notificação" onClick="js_emite(1);">
         <input name="db_opcao1" type="button" id="db_opcao" value="Lista" onClick="js_emite(2);">
         <input name="db_opcao2" type="button" id="db_opcao" value="SEED" onClick="js_emiteseed();">
         <input name="db_opcao3" type="button" id="db_opcao" value="AR" onClick="js_emitear();">
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
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>