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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_configdbpref_classe.php");

$clconfigdbpref = new cl_configdbpref();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$rsParametro = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession("DB_instit"),"w13_exigecpfcnpjmatricula,w13_exigecpfcnpjinscricao"));
$oRetorno    = db_utils::fieldsMemory($rsParametro,0);

if ( ($oRetorno->w13_exigecpfcnpjinscricao == "t" && ( $Cab == "alvara_cab" || $Cab == "alvara_rod"))
    || ($oRetorno->w13_exigecpfcnpjmatricula == "t" && ($Cab == "imovel_cab" || $Cab == "imovel_rod") ) ) {
  $Cab = $Cab."_cpfcnpj";
	$Rod = $Rod."_cpfcnpj";
}

$instit = db_getsession("DB_instit");
function mens_help() {
  global $larg_div;
  global $alt_div;
  global $x_div;
  global $y_div;
  global $cor_div;
  global $nome_help;
  global $texto_help;
  global $HTTP_ENV_VARS;

  $uo = strrpos(@$HTTP_ENV_VARS['PATH_INFO'],"/") + 1;
  $ponto = strpos(@$HTTP_ENV_VARS['PATH_INFO'],".");
  $mens = substr(@$HTTP_ENV_VARS['PATH_INFO'],$uo,$ponto - $uo);

  $result = @db_query("select * from db_confmensagem where cod = '".$mens."_help' and instit = $instit");

  $nome_help = @pg_result($result,0,"cod");
  $texto_help = @pg_result($result,0,"mens");
  $param_help = split("&",@pg_result($result,0,"alinhamento"));
  $larg_div = $param_help[0];
  $alt_div  = @$param_help[1];
  $x_div    = @$param_help[2];
  $y_div    = @$param_help[3];
  $cor_div  = @$param_help[4];
}
mens_help();

if(isset($HTTP_POST_VARS["enviar"])) {

  $oPost = db_utils::postMemory($_POST);
  $sMensagemCabecalhoRodape = $oPost->RG_cabrod;
  $sMensagemCorpo           = $oPost->resultado;
  $sAlinhamento             = $oPost->RG_alinhamento;

  $cod = $sMensagemCabecalhoRodape == "CB" ? "$Cab" : "$Rod";

  $sSql  = " update db_confmensagem                                             ";
  $sSql .= "    set mens = '{$sMensagemCorpo}', alinhamento = '{$sAlinhamento}' ";
  $sSql .= " where cod    = '$cod'                                              ";
  $sSql .= "   and instit = $instit                                             ";
  $result = db_query($sSql);

  if( $result ) {

     db_query("COMMIT");
   	 db_msgbox("Mensagem atualizada");
  } else {

    db_query("ROLLBACK");
	  echo "Erro atualizando tabela db_confmensagens.<br /> <a href=\"mensprincipal.php?Cab=$Cab&Rod=$Rod\"> Voltar </a>";
	  exit();
  }
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<?
$result = db_query("select mens,alinhamento from db_confmensagem where instit = $instit and cod = '$Cab' or cod = '".@$Rod."' order by cod");
echo "
<script>
function js_iniciar() {
  var F = document.form1;

  AlinhamentoCabecalho = '".@str_replace("\r"," ",str_replace("\n"," ",pg_result($result,0,1)))."';
  AlinhamentoRodape = '".@str_replace("\r"," ",str_replace("\n"," ",pg_result($result,1,1)))."';
  TextoCabecalho = '".@str_replace("\r"," ",str_replace("\n"," ",pg_result($result,0,0)))."';
  TextoRodape = '".@str_replace("\r"," ",str_replace("\n"," ",pg_result($result,1,0)))."';

  if(TextoRodape == '') {
    F.RG_cabrod[0].disabled = true;
	F.RG_cabrod[1].disabled = true;
  }
  if(F.RG_cabrod[0].checked == true) {
    document.getElementById('di').innerHTML = TextoCabecalho;

	for(i = 0;i < 4;i++)
	  if(F.RG_alinhamento[i].value == AlinhamentoCabecalho)
	    F.RG_alinhamento[i].click();
  } else {
    document.getElementById('di').innerHTML = TextoRodape;

	for(i = 0;i < 4;i++)
	  if(F.RG_alinhamento[i].value == AlinhamentoRodape)
	    F.RG_alinhamento[i].click();
  }
  F.result_text.value = document.getElementById('di').innerHTML;
  F.resultado.value = document.getElementById('di').innerHTML;
  F.t1.focus();
}
 </script>";
?>
<script>
function js_ins_imagem() {
  var F = document.form1;
  var arq = F.arq_img.value;
  if(arq == '') {
    alert("Selecione um arquivo primeiro");
	return false;
  }
  alert('Voce deverá clicar no botão Salvar para a imagem aparecer na tela');
  //pega o basename do arquivo
  if(arq.indexOf("/") != -1)
    arq = arq.split("/");
  else if(arq.indexOf("\\") != -1)
    arq = arq.split("\\");
  arq = arq.pop();
  //ve se tem largura e altura
  if(F.img_altura.value != '' && !isNaN(F.img_altura.value))
    var alt = 'height="' + F.img_altura.value + '"';
  else
    var alt = '';
  if(F.img_largura.value != '' && !isNaN(F.img_largura.value))
    var larg = ' width="' + F.img_largura.value + '"';
  else
    var larg = '';
   document.getElementById('di').innerHTML = document.getElementById('di').innerHTML + '<img src="imagens/usuarios/' + arq + '" border="' + (isNaN(F.img_borda)?"0":F.img_borda) + '" ' + alt + larg + ' align="' + F.img_align.value + '">';
  F.resultado.value = document.getElementById('di').innerHTML;
  if(F.RG_cabrod[0].checked == true)
    TextoCabecalho = document.getElementById('di').innerHTML;
  else
    TextoRodape = document.getElementById('di').innerHTML;
}
function js_interpretar() {
  var F = document.form1;

  document.getElementById('di').innerHTML = F.resultado.value;
  if(F.RG_cabrod[0].checked == true)
    TextoCabecalho = document.getElementById('di').innerHTML;
  else
    TextoRodape = document.getElementById('di').innerHTML;
}
function js_visualizar() {
  var F = document.form1;
  var S = F.t1.value;
  var aux = "";

  if(S == '') {
    alert('Campo Vazio');
	F.t1.focus();
	return false;
  }
  //tamanho
  if(isNaN(F.tamanho.value) || F.tamanho.value == '')
    F.tamanho.value = 15;
  if(S.indexOf('font') != -1) {
    S = S.replace('<font','<font style="font-size:' + F.tamanho.value + 'px"');
  } else {
    aux = '<font style="font-size:' + F.tamanho.value + 'px">' + S + '</font>';
	S = aux;
  }
  //negrito
  if(F.negrito.checked == true) {
    if(S.indexOf('font') != -1) {
	  if(S.indexOf('style="') != -1) {
	    S = S.replace('style="','style="font-weight:bold;');
	  } else {
	    S = S.replace('<font','<font style="font-weight:bold;');
	  }
	}
  }
  //italico
  if(F.italico.checked == true) {
    if(S.indexOf('font') != -1) {
	  if(S.indexOf('style="') != -1) {
	    S = S.replace('style="','style="font-style: italic;');
	  } else {
	    S = S.replace('<font','<font style="font-style: italic;');
	  }
	}
  }
  //sublinhado
  if(F.sublinhado.checked == true) {
    if(S.indexOf('font') != -1) {
	  if(S.indexOf('style="') != -1) {
	    S = S.replace('style="','style="text-decoration: underline;');
	  } else {
	    S = S.replace('<font','<font style="text-decoration: underline;');
	  }
	}
  }
  // tipo de fonte
  if(F.fonte.value != 'FP') {
    if(S.indexOf('<font') != -1) {
      S = S.replace('<font','<font face="' + F.fonte.value + '"');
	}
  }
  //cor
  if(F.cor.value != 'CD') {
    if(S.indexOf('font') != -1) {
	  S = S.replace('<font','<font color="' + F.cor.value + '"');
	}
  }

  document.getElementById('di').innerHTML = document.getElementById('di').innerHTML + S

  F.resultado.value = document.getElementById('di').innerHTML;
  F.t1.value = '';
  if(F.RG_cabrod[0].checked == true)
    TextoCabecalho = document.getElementById('di').innerHTML;
  else
    TextoRodape = document.getElementById('di').innerHTML;
  F.t1.focus();
}
function js_delete() {

  var F = document.form1;
  var S = document.getElementById('di').innerHTML;
  var P = document.getElementById('di').innerText;

  S = S.split("");
  for(i = 0;i < S.length;i++)
    document.write(S[i]+'<br>');
}

function js_apagar() {

  var F = document.form1;

  document.getElementById('di').innerHTML = '';
  F.resultado.value = '';
  if(F.RG_cabrod[0].checked == true)
    TextoCabecalho = document.getElementById('di').innerHTML;
  else
    TextoRodape = document.getElementById('di').innerHTML;
  F.t1.focus();
}
function js_novalinha() {

  var F = document.form1;

  document.getElementById('di').innerHTML = document.getElementById('di').innerHTML + '<Br>';
  F.resultado.value = document.getElementById('di').innerHTML;
  if(F.RG_cabrod[0].checked == true)
    TextoCabecalho = document.getElementById('di').innerHTML;
  else
    TextoRodape = document.getElementById('di').innerHTML;
  F.t1.focus();
}
function js_submeter() {
  var F = document.form1;
  F.RG_cabrod[0].disabled = false;
  F.RG_cabrod[1].disabled = false;
  F.result_text.value = document.getElementById('di').innerHTML;
}

function js_cabrod() {
  var F = document.form1;

  if(F.RG_cabrod[0].checked == true) {
    document.getElementById('di').innerHTML = TextoCabecalho;
	for(i = 0;i < 4;i++)
	  if(F.RG_alinhamento[i].value == AlinhamentoCabecalho)
	    F.RG_alinhamento[i].click();

	F.resultado.value = document.getElementById('di').innerHTML;
  } else {
    document.getElementById('di').innerHTML = TextoRodape;
	for(i = 0;i < 4;i++)
	  if(F.RG_alinhamento[i].value == AlinhamentoRodape)
	    F.RG_alinhamento[i].click();
	F.resultado.value = document.getElementById('di').innerHTML;
  }
  F.t1.focus();
}
function js_alinhamento() {
  var F = document.form1;

  for(i = 0;i < 4;i++)
    if(F.RG_alinhamento[i].checked == true) {
      document.getElementById('di').style.textAlign = F.RG_alinhamento[i].value;
	  if(F.RG_cabrod[0].checked == true)
        AlinhamentoCabecalho = F.RG_alinhamento[i].value
	  else
	    AlinhamentoRodape = F.RG_alinhamento[i].value;
	}
}
</script>
<style type="text/css">
<!--
fieldset {
	border: 1px solid #000000;
}
.tamFonte {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 8px;
	font-style: normal;
	font-weight: bold;
	text-decoration: none;
}
-->
</style>
</head>
<body class="body-default" onLoad="js_iniciar()">

<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
<?
echo "<br /> <b><u>Mensagem de Cabeçalho e Rodapé do Link ".ucfirst(substr($Cab,0,(strlen($Cab) - 4)))."</u></b>\n";
?>
<form method="post" name="form1" onSubmit="js_submeter()">
        <table width="72%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap>
			  <fieldset>
              <legend>Texto de Entrada:</legend>
              <input name="t1" type="text" id="t14" size="90">
              </fieldset>
			  </td>
          </tr>
          <tr>
            <td><table width="86%" border="0" cellspacing="3" cellpadding="0">
                <tr align="left" valign="top">
                  <td width="19%" nowrap> <fieldset>
                    <legend>Estilo:</legend>
                    <input name="negrito" type="checkbox" id="negrito3" value="N">
                    <label for="negrito3">Negrito</label>
                    <br>
                    <input name="italico" type="checkbox" id="italico3" value="I">
                    <label for="italico3">Itálico</label>
                    <br>
                    <input name="sublinhado" type="checkbox" id="sublinhado3" value="S">
                    <label for="sublinhado3">Sublinhado</label>
                    </fieldset></td>
                  <td width="26%" nowrap> <fieldset>
                    <legend>Alinhamento:</legend>
                    <input name="RG_alinhamento" id="ali1" type="radio" value="left" onClick="js_alinhamento()">
                    <label for="ali1">E</label>
                    <input name="RG_alinhamento" id="ali2" type="radio" value="center" onClick="js_alinhamento()">
                    <label for="ali2">C</label>
                    <input name="RG_alinhamento" id="ali3" type="radio" value="right" onClick="js_alinhamento()">
                    <label for="ali3">D</label>
                    <input name="RG_alinhamento" id="ali4" type="radio" value="justify" onClick="js_alinhamento()">
                    <label for="ali4">J</label>
                    </fieldset></td>
                  <td width="55%" nowrap> <fieldset>
                    <legend>Mensagem de:</legend>
                    <input name="RG_cabrod" id="cabro1" type="radio" value="CB" onClick="js_cabrod()" <? echo isset($HTTP_POST_VARS["enviar"])?(@$HTTP_POST_VARS["RG_cabrod"]=="CB"?"checked":""):"checked"; ?>>
                    <label for="cabro1">Cabeçalho</label>
                    <br>
                    <input type="radio" id="cabrod2" name="RG_cabrod" value="RP" onClick="js_cabrod()" <? echo @$HTTP_POST_VARS["RG_cabrod"]=="RP"?"checked":""; ?>>
                    <label for="cabrod2">Rodapé</label>
                    <br>
                    </fieldset></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td> <fieldset>
              <legend>Fonte:</legend>
              <table width="61%" border="0" cellpadding="0" cellspacing="0">
                <tr align="left" valign="baseline" class="tamFonte">
                  <td width="37%">tipo:</td>
                  <td width="15%">cor:</td>
                  <td width="48%">tamanho:</td>
                </tr>
                <tr align="left" valign="top">
                  <td nowrap> <select name="fonte" id="select5">
                      <option value="FP">Fonte Padrão</option>
                      <option value="Arial, Helvetica, sans-serif">Arial, Helvetica,
                      sans-serif</option>
                      <option value="Times New Roman, Times, serif">Times New
                      Roman, Times, serif</option>
                      <option value="Courier New, Courier, mono">Courier New,
                      Courier, mono</option>
                      <option value="Georgia, Times New Roman, Times, serif">Georgia,
                      Times New Roman, Times, serif</option>
                      <option value="Verdana, Arial, Helvetica, sans-serif">Verdana,
                      Arial, Helvetica, sans-serif</option>
                      <option value="Geneva, Arial, Helvetica, san-serif">Geneva,
                      Arial, Helvetica, san-serif</option>
                    </select> &nbsp;</td>
                  <td nowrap> <select name="cor" id="select4">
                      <option value="CD">Cor Default</option>
                      <option value="white" style="background-color:white;color:black">Branco</option>
                      <option value="black" style="background-color:black;color:white">Preto</option>
                      <option value="blue" style="background-color:blue;color:white">Azul</option>
                      <option value="yellow" style="background-color:yellow;color:white">Amarelo</option>
                      <option value="green" style="background-color:green;color:white">Verde</option>
                      <option value="red" style="background-color:red;color:white">Vermelho</option>
                      <option value="aqua" style="background-color:aqua;color:white">Aqua</option>
                      <option value="lime" style="background-color:lime;color:white">Lime</option>
                      <option value="maroon" style="background-color:maroon;color:white">Marron</option>
                      <option value="navy" style="background-color:navy;color:white">Navy</option>
                      <option value="olive" style="background-color:olive;color:white">Oliva</option>
                      <option value="purple" style="background-color:purple;color:white">Purple</option>
                      <option value="silver" style="background-color:silver;color:white">Prata</option>
                      <option value="teal" style="background-color:teal;color:white">teal</option>
                    </select> &nbsp;</td>
                  <td nowrap> <input name="tamanho" type="text" id="tamanho" value="15" size="3" maxlength="3">
                    px </td>
                </tr>
              </table>
              </fieldset></td>
          </tr>
          <tr>
            <td> <fieldset>
              <legend>Comandos:</legend>
              <input name="visualizar"  type="button" id="visualizar"  value="Visualizar"  onClick="js_visualizar()" />
              <input name="delete"      type="button" id="delete2"     value="Del"         onClick="js_delete()" />
              <input name="apagar"      type="button" id="apagar2"     value="Apagar Tudo" onClick="js_apagar()" />
              <input name="nova_linha"  type="button" id="nova_linha2" value="Nova Linha"  onClick="js_novalinha()" />
              <input name="interpretar" type="button" id="interpretar" value="Interpretar" onClick="js_interpretar()" />
              <input name="enviar"      type="submit" id="enviar2"     value="Salvar" />
              <br>
              </fieldset></td>
          </tr>
          <tr>
            <td height="29"> <fieldset>
              <legend>Sa&iacute;da Interpretado:</legend>
              <p align="center">
              <table border="1" width="60%">
                <tr>
                  <td><div id="di"></div></td>
                </tr>
              </table></p>
              </fieldset></td>
          </tr>
          <tr>
            <td> <fieldset>
              <legend>Sa&iacute;da Fonte:</legend>
              <textarea name="resultado" cols="83" rows="10" wrap="VIRTUAL" id="textarea"></textarea>
              </fieldset></td>
          </tr>
        </table>
  <input type="hidden" name='result_text'>
</form>
	</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>