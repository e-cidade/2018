<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

$clRotulo = new rotulocampo();
$clRotulo->label("v13_certid");
$clRotulo->label("v14_vlrhis");

$oGet = db_utils::postMemory($_GET);

if (isset($oGet->iCdaParcelIni) && isset($oGet->iCdaParcelFim)) {
	$v13_certid       = $oGet->iCdaParcelIni;
	$v13_certid_final = $oGet->iCdaParcelFim;
}

?>
<html>
<style type="text/css">

#v13_certid, #v13_certid_final, #v14_vlrhis, #v14_vlrhis_maximo {
  width: 83px;
  font-size:12px;
}

</style>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="expires" content="0">
	<?php
		db_app::load("scripts.js");
		db_app::load("prototype.js");
		db_app::load("estilos.css");
	?>
</head>
<body bgcolor="#CCCCCC">
<form class="container" name="form1" id="form1">
  <fieldset>
  	<legend>Certidão de Parcelamento de Divida Ativa</legend>
  	<table class="form-container">

  		<tr>
  			<td>
          <?php
            db_ancora($Lv13_certid, "js_pesquisaCertidao(true, true)", 1);
          ?>
        </td>
        <td>
          <?php
            db_input("v13_certid", 10, $Iv13_certid, true, "text", 1, "onchange='js_validaCDA()'");

            db_ancora("<strong>à</strong>", "js_pesquisaCertidao(true, false)", 1);

            db_input("v13_certid", 10, $Iv13_certid, true, "text", 1, null, "v13_certid_final");
          ?>
  			</td>
  		</tr>

  		<tr>
  			<td><label for="v14_vlrhis">Valores:</label></td>
  			<td>
  				<?php
  					db_input("v14_vlrhis", 15, 4, true, "text", 1);

  					echo "<strong>à</strong>";
  					db_input("v14_vlrhis", 15, 4, true, "text", 1, null, "v14_vlrhis_maximo");
  				?>
  			</td>
  		</tr>

  	</table>
  </fieldset>
	<input type="button" name="processar" id="processar" value="Processar" onclick="js_processar()" />
  <?php
    if (!isset($oGet->iCdaParcelIni) && !isset($oGet->iCdaParcelFim)) {
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    }
  ?>
</form>
<script>

function js_validaCDA() {

  var iCertidaoInicial = document.form1.v13_certid.value;
  var iCertidaoFinal   = document.form1.v13_certid_final.value;

  if (iCertidaoFinal == '' || iCertidaoFinal < iCertidaoInicial) {
    document.form1.v13_certid_final.value = iCertidaoInicial;
  }
}

function js_processar() {

  var iCertidaoInicial = document.form1.v13_certid.value;
  var iCertidaoFinal   = document.form1.v13_certid_final.value;
  var nValorMinimo     = document.form1.v14_vlrhis.value;
  var nValorMaximo     = document.form1.v14_vlrhis_maximo.value;
  var lReemissao       = 'f';
	var sUrl             = '';

  if (iCertidaoInicial == '') {

    alert('Campo Certidão é de preenchimento obrigatório.');
    return false;
  }


  sUrl += 'div2_certidaodivida002.php';
  sUrl += '?certid='      + iCertidaoInicial;
  sUrl += '&certid1='     + iCertidaoFinal;
  sUrl += '&valorminimo=' + nValorMinimo;
  sUrl += '&valormaximo=' + nValorMaximo;
  sUrl += '&reemissao='   + lReemissao;
  sUrl += '&tipo=1';

  jan = window.open(sUrl, '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');

	jan.moveTo(0, 0);
}

function js_pesquisaCertidao(lMostra, lInicial) {

  if (lMostra == true) {

    sFuncao = lInicial == true ? 'js_mostraTermoInicial' : 'js_mostraTermoFinal';

    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_certidao','func_certter.php?funcao_js=parent.'+sFuncao+'|0','Pesquisa',true);
  }
}

function js_mostraTermoInicial(iCertidao) {

  document.form1.v13_certid.value = iCertidao;

  if (document.form1.v13_certid_final.value == '' || document.form1.v13_certid_final.value < iCertidao) {
  	document.form1.v13_certid_final.value = iCertidao;
  }

  document.form1.v13_certid_final.focus();
  db_iframe_certidao.hide();
}

function js_mostraTermoFinal(iCertidao) {

	document.form1.v13_certid_final.value = iCertidao;

	db_iframe_certidao.hide();
}

</script>
</body>
</html>
<script>

$("v13_certid").addClassName("field-size2");
$("v13_certid_final").addClassName("field-size2");
$("v14_vlrhis").addClassName("field-size2");
$("v14_vlrhis_maximo").addClassName("field-size2");
$("reemissao").setAttribute("rel","ignore-css");
$("reemissao").addClassName("field-size2");

</script>