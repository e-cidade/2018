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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oGet       = db_utils::postMemory($_GET);
$iAnoSessao = db_getsession("DB_anousu");

$sBtnProcessar = "Processar";
if (isset($oGet->lDesprocessar) && $oGet->lDesprocessar == "true") {
  $sBtnProcessar = "Desprocessar";
}

if (isset($oGet->iTipo) && $oGet->iTipo == RestosAPagar::TIPO_RP_NAO_PROCESSADO) {

  $sLegend = "Processar Inscrições de RPs Não Processados";
  if (isset($oGet->lDesprocessar) && $oGet->lDesprocessar == "true") {
  	$sLegend = "Desprocessar Inscrições de RPs Não Processados";
  }
} else if (isset($oGet->iTipo) && $oGet->iTipo == RestosAPagar::TIPO_RP_PROCESSADO) {

  $sLegend = "Processar Inscrições de RPs Processados";
  if (isset($oGet->lDesprocessar) && $oGet->lDesprocessar == "true") {
    $sLegend = "Desprocessar Inscrições de RPs Processados";
  }
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class="container">

    <form name='form1'>

      <fieldset style="width: 550px">
        <legend><?php echo $sLegend;?></legend>

        <table width="100%">

          <tr>
            <td nowrap="nowrap"><b>Ano:</b></td>
            <td nowrap="nowrap">
              <?php
                db_input("iAnoSessao", 10, null, true, 'text', 3);
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap="nowrap" style="width: 30%;">
              <b>Valor do Exercício Atual:</b>
            </td>
            <td nowrap="nowrap">
              <?php
                db_input("nValorExercicioAtual", 20, null, true, "text", 3);
              ?>
            </td>
          </tr>

          <tr>
          	<td nowrap="nowrap">
          		<b>Valor dos Exercícios Anteriores:</b>
          	</td>
          	<td nowrap="nowrap">
          		<?php
          		  db_input("nValorExerciciosAnteriores", 20, null, true, "text", 3);
          		?>
          	</td>
          </tr>

          <tr>
            <td nowrap="nowrap" colspan="2">
              <fieldset>
                <legend>Observações</legend>
                <textarea name="sObservacao" id="sObservacao" style="width:100%; height: 100px" ></textarea>
              </fieldset>
            </td>
          </tr>

        </table>

      </fieldset>

      <input type="hidden" name="iTipo" id="iTipo" value="<?php echo $oGet->iTipo ?>">
      <input type="button" name="btnProcessar" id="btnProcessar" value="<?= $sBtnProcessar ?>" disabled="disabled" />

    </form>

  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">
  var oGet    = js_urlToObject();
  var sUrlRpc = 'con4_restosapagar.RPC.php';

  /**
   * Busca o valor dos RPs para o ano da sessao
   */
  function js_buscaValorRestoAPagar() {

    js_divCarregando("Aguarde, buscando valor...", "msgBox");

    var oObject          = new Object();
    oObject.exec         = "getDadosRestosAPagar";
    oObject.iTipo        = $F('iTipo');
    oObject.lProcessados = oGet.lDesprocessar == 'true' ? 'false' : 'true';

    var oAjax = new Ajax.Request (sUrlRpc, { method:'post',
                                             parameters:'json='+Object.toJSON(oObject),
                                             onComplete:js_retornoValorRestoAPagar});
  }

  function js_retornoValorRestoAPagar(oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode());
      return false;
    }

    $('nValorExercicioAtual').value       = js_formatar(oRetorno.nValorExercicioAtual, 'f');
    $('nValorExerciciosAnteriores').value = js_formatar(oRetorno.nValorExerciciosAnteriores, 'f');

    if ( oRetorno.lBloquearTela ) {

      $('sObservacao').disabled              = true;
      $('sObservacao').style.backgroundColor = '#DEB887';
      $('btnProcessar').disabled             = true;

      var sMsgAviso = "As inscrições de RP já foram processadas";
      if (oGet.lDesprocessar == 'true') {
        sMsgAviso = "As inscrições de RP já foram desprocessadas";
      }
      sMsgAviso += "\nVocê não pode executar essa rotina novamente";

      alert(sMsgAviso);
    }
  }

  js_buscaValorRestoAPagar();

  /**
   * Verificamos se o campo observacao foi devidamente preenchido
   */
  $('sObservacao').observe('keyup', function() {

    if ($F('sObservacao').trim() == "") {
      $('btnProcessar').disabled = true;
    } else {
      $('btnProcessar').disabled = false;
    }
  });

  $('btnProcessar').observe('click', function() {

    var oObject         = new Object();
    oObject.exec        = oGet.lDesprocessar == 'true' ? 'desprocessar' : 'processar';
    oObject.sObservacao = encodeURIComponent(tagString($F('sObservacao')));
    oObject.iTipo       = $F('iTipo');

    js_divCarregando("Aguarde, processando ...", "msgBox");

    var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                            parameters:'json='+Object.toJSON(oObject),
                                            onComplete:js_retornoProcessamento});
  });

  function js_retornoProcessamento(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode());
      return false;
    }

    $('sObservacao').disabled              = true;
    $('sObservacao').style.backgroundColor = '#DEB887';
    $('sObservacao').style.color           = '#333333';
    $('btnProcessar').disabled             = true;
    alert('Processamento concluído com sucesso.');
  }
  </script>
  </body>
</html>
