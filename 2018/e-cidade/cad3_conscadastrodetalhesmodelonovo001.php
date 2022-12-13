<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oPost      = db_utils::postMemory($_POST);
$oGet       = db_utils::postMemory($_GET);

$iMatricula = '';
if (isset($matricula)) {
	$iMatricula = $matricula;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<script type="text/javascript">

function js_emite() {

  var sQuery      = '';
  var sAtribuicao = '';
  var aElemOpcao  = $$("input[type='checkbox']");
  var iMatricula  = '<?=$iMatricula?>';
  var lChecked    = false;

  aElemOpcao.each(

    function (oElemOpcao, iInd) {

      if (oElemOpcao.checked == true) {

        sQuery  += sAtribuicao+oElemOpcao.name+'=true';
        lChecked = true;
        if (iInd < aElemOpcao.length) {
          sAtribuicao = '&';
        }
      }
    }
  );

  if (lChecked == false) {

    alert("Selecione uma opção de impressão!");
    return false;
  }

  sQuery += sAtribuicao+'matricula='+iMatricula;
  jan = window.open('cad3_conscadastrodetalhesmodelonovo002.php?'+sQuery,'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

  jan.moveTo(0,0);
}
</script>
</head>
<body class="body-default">
<form name="form1" method="post" action="">
<center>
<table align="center" width="100%" border="0">
  <tr>
    <td align="center">
      <u>Imprime BIC - Modelo Novo</u>
    </td>
  </tr>
  <tr>
    <td align="left">
      <table width="55%" border="0">
        <tr>
          <td>
            <strong>Informações padrões:&nbsp;</strong>
          </td>
          <td>
            <?
              $aOpcao = array("1" => "Completo",
                              "2" => "Resumido");
              db_select("opcaoimpressao", $aOpcao, true, 2, " onchange='js_marcaropcao();'");
            ?>
          </td>
	        <td>
	          <input type="button" name="imprimir" id="imprimir" value="Imprimir" onclick="return js_emite();">
	        </td>
        </tr>

        <tr>
          <td>
            <strong>Imprimir Detalhamento sem Informações:</strong>
          </td>
          <td colspan="2">
            <input type="checkbox" id='imprimeNulo' name='imprimeNulo' class="checkbox" checked="checked" >
          </td>
        </tr>

      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="85%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td>
			      <fieldset>
			        <legend>
			          <strong>Opção de impressão</strong>
			        </legend>
			        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="checkbox">
			          <tr>
			            <td>
			              <input type="checkbox" name="dadosimovel" id="dadosimovel" checked="checked">
			              <label>Dados do Imóvel</label>
			            </td>
			            <td>
			              <input type="checkbox" name="caracteristicaslote" id="caracteristicaslote" checked="checked"/>
			              <label>Características do Lote</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="caracteristicasface" id="caracteristicasface" checked="checked"/>
			              <label>Características da Face</label>
			            </td>
			            <td>
			              <input type="checkbox" name="caracteristicasconstrucoes" id="caracteristicasconstrucoes"
			                     checked="checked" onclick="js_marcarcaracteristicas(this.name);">
			              <label>Características das Construções</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="testadaslote" id="testadaslote" checked="checked">
			              <label>Testadas do Lote</label>
			            </td>
			            <td>
			              <input type="checkbox" name="testadasinternas" id="testadasinternas"
			                     checked="checked">
			              <label>Testadas Internas</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="proprietarios" id="proprietarios" checked="checked">
			              <label>Proprietários</label>
			            </td>
			            <td>
			              <input type="checkbox" name="outrosproprietarios" id="outrosproprietarios" checked="checked"/>
			              <label>Outros Proprietários</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="promitentes" id="promitentes" checked="checked">
			              <label>Promitentes</label>
			            </td>
			            <td>
			              <input type="checkbox" name="outrospromitentes" id="outrospromitentes"
			                     checked="checked">
			              <label>Outros Promitentes</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="imobiliaria" id="imobiliaria" checked="checked">
			              <label>Imobiliária</label>
			            </td>
			            <td>
			              <input type="checkbox" name="enderecoentrega" id="enderecoentrega" checked="checked">
			              <label>Endereço de Entrega</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="edificacoes" id="edificacoes" checked="checked"
			                     onclick="js_marcarcaracteristicas(this.name);">
			              <label>Edificações</label>
			            </td>
			            <td>
			              <input type="checkbox" name="dadosregistroimoveis" id="dadosregistroimoveis" checked="checked"/>
			              <label>Dados do Registro de Imóveis</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="isencoes" id="isencoes" checked="checked">
			              <label>Isenções</label>
			            </td>
			            <td>
			              <input type="checkbox" name="averbacoes" id="averbacoes" checked="checked">
			              <label>Averbações</label>
			            </td>
			          </tr>
			          <tr>
			            <td>
			              <input type="checkbox" name="calculos" id="calculos" checked="checked"
			                     onclick="js_marcarcalculo(this.name);">
			              <label>Cálculos</label>
			            </td>
			            <td>
			              <input type="checkbox" name="calculosanteriores" id="calculosanteriores" checked="checked">
			              <label>Cálculos Exercícios Anteriores</label>
			            </td>
			          </tr>
                <tr>
                  <td>
                    <input type="checkbox" name="outrosdados" id="outrosdados" checked="checked">
                    <label>Outros Dados</label>
                  </td>
                </tr>
			        </table>
			      </fieldset>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</center>
</form>
</body>
<script type="text/javascript">
function js_marcaropcao() {

  var opcaoimpressao = $('opcaoimpressao').value;
  if (opcaoimpressao != 1) {

    $('dadosimovel').checked                = true;
    $('caracteristicaslote').checked        = true;
    $('caracteristicasface').checked        = true;
    $('caracteristicasconstrucoes').checked = true;
    $('testadaslote').checked               = true;
    $('testadasinternas').checked           = false;
    $('proprietarios').checked              = true;
    $('outrosproprietarios').checked        = true;
    $('promitentes').checked                = true;
    $('outrospromitentes').checked          = true;
    $('imobiliaria').checked                = true;
    $('enderecoentrega').checked            = true;
    $('edificacoes').checked                = true;
    $('dadosregistroimoveis').checked       = false;
    $('isencoes').checked                   = false;
    $('averbacoes').checked                 = false;
    $('calculos').checked                   = false;
    $('calculosanteriores').checked         = false;
    $('calculosanteriores').disabled        = true;
    $('outrosdados').checked                = false;
  } else {

    $('dadosimovel').checked                = true;
    $('caracteristicaslote').checked        = true;
    $('caracteristicasface').checked        = true;
    $('caracteristicasconstrucoes').checked = true;
    $('testadaslote').checked               = true;
    $('testadasinternas').checked           = true;
    $('proprietarios').checked              = true;
    $('outrosproprietarios').checked        = true;
    $('promitentes').checked                = true;
    $('outrospromitentes').checked          = true;
    $('imobiliaria').checked                = true;
    $('enderecoentrega').checked            = true;
    $('edificacoes').checked                = true;
    $('dadosregistroimoveis').checked       = true;
    $('isencoes').checked                   = true;
    $('averbacoes').checked                 = true;
    $('calculos').checked                   = true;
    $('calculosanteriores').checked         = true;
    $('calculosanteriores').disabled        = false;
    $('outrosdados').checked                = true;
  }
}

function js_marcarcaracteristicas(sOpcao) {

  if (sOpcao == 'caracteristicasconstrucoes') {

	  if ($('caracteristicasconstrucoes').checked) {
	    $('edificacoes').checked                = true;
	  } else {
	    $('edificacoes').checked                = false;
	  }
  } else if (sOpcao == 'edificacoes') {

	  if ($('edificacoes').checked) {
	    $('caracteristicasconstrucoes').checked = true;
	  } else {
	    $('caracteristicasconstrucoes').checked = false;
	  }
  }
}

function js_marcarcalculo(sOpcao) {

  if (sOpcao == 'calculos') {

    if ($('calculos').checked) {

      $('calculosanteriores').checked  = false;
      $('calculosanteriores').disabled = false;
    } else {

      $('calculosanteriores').checked  = false;
      $('calculosanteriores').disabled = true;
    }
  }
}
</script>
</html>