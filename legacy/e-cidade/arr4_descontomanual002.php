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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("libs/exceptions/BusinessException.php"));

$oRotulo = new rotulocampo;
$oRotulo->label("k00_numpre");
$oRotulo->label("z01_nome");
$oRotulo->label("z01_numcgm");
$oRotulo->label("k00_valor");
$oRotulo->label("k00_descr");
$oRotulo->label("k00_numpar");
$oRotulo->label("k00_receit");
$oRotulo->label("k00_histtxt");

$aParcelas = array('0' => 'Todas parcelas');
$aReceitas = array('0' => 'Todas receitas');

$nValorHistorico     = 0;
$nValorTotal         = 0;
$nValorDesconto      = 0;
$nPercentual         = 0;
$nPercentualCompleto = 0;

$oPost = db_utils::postMemory($_POST);

if ( empty($oPost->k00_numpre) ) {
  db_msgbox( 'Nenhum numpre informado.' );
  db_redireciona("arr4_descontomanual001.php");
}

try {

  $iNumpre = $oPost->k00_numpre;

  if ( !DBNumber::isInteger($iNumpre) ) {
    throw new ParameterException("Numpre não é válido");
  }

  if ( !Desconto::validarProcessamento($iNumpre) ) {
    // throw new BusinessException(" Débito com recibos válidos emitidos, desconto não pode ser aplicado.");
  }

  $sDataHoje = date( "Y-m-d", db_getsession("DB_datausu") );

	$rsDebitosNumpre = debitos_numpre($iNumpre, 0, 0, db_getsession("DB_datausu"), db_getsession("DB_anousu"), 0, '', '', " and y.k00_hist <> 918");

  if ( !$rsDebitosNumpre || pg_num_rows($rsDebitosNumpre) == 0 ) {
		throw new Exception('Débitos para o numpre ' . $iNumpre .', não encontrados');
	}

	$oDaoArrecad   = db_utils::getDao('arrecad');
	$sWhereArrecad = "arrecad.k00_numpre = {$iNumpre} and arreinstit.k00_instit = ".db_getsession('DB_instit');
	$sSqlArrecad   = $oDaoArrecad->sql_query(null, 'cgm.z01_nome, arretipo.k00_descr, cgm.z01_numcgm', null, $sWhereArrecad);
	$rsArrecad     = $oDaoArrecad->sql_record($sSqlArrecad);

	if ( $oDaoArrecad->numrows == 0) {
		throw new Exception('Erro ao buscar dono do débito');
	}

	$oArrecad = db_utils::fieldsMemory($rsArrecad, 0);

	$z01_nome   = $oArrecad->z01_nome;
	$z01_numcgm = $oArrecad->z01_numcgm;
	$k00_descr  = $oArrecad->k00_descr;
	$k00_numpre = $iNumpre;

	$aDebitosNumpre = db_utils::getCollectionByRecord($rsDebitosNumpre);

	foreach ( $aDebitosNumpre as $oDebitoNumpre ) {

		$aParcelas[$oDebitoNumpre->k00_numpar] = $oDebitoNumpre->k00_numpar;
		$aReceitas[$oDebitoNumpre->k00_receit] = $oDebitoNumpre->k02_descr;
		$nValorHistorico += $oDebitoNumpre->vlrhis;
		$nValorTotal     += $oDebitoNumpre->total;

	}

	$nValorHistorico = trim(db_formatar($nValorHistorico, 'f'));
	$nValorLimite    = $nValorHistorico;
	$nValorTotal     = trim(db_formatar($nValorTotal, 'f'));

	/**
	 * Recordset com historicos de calculo
	 */
	$rsHistoricos = db_query("select * from histcalc order by k01_codigo, k01_descr");

} catch(Exception $oErro) {

	db_msgbox( $oErro->getMessage() );
	db_redireciona("arr4_descontomanual001.php");
	exit;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css, scripts.js, numbers.js, strings.js, prototype.js, datagrid.widget.js, grid.style.css"); ?>
  <style>
    #k00_numpar, #k00_receit {
      width: 316px;
    }
  </style>
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" action="" method="post">

      <fieldset>

        <legend>Numpre do Débito</legend>

        <table>

          <tr>
            <td nowrap>
              <strong>Numpre:</strong>
            </td>
            <td nowrap>
              <?php db_input('k00_numpre', 8, $Ik00_numpre, true, 'text', 3); ?>
            </td>
          </tr>

        </table>

      </fieldset>

      <div id="dadosNumpre">

        <fieldset>

          <legend>Informações do Débito</legend>

          <table width="100%">

            <tr>
              <td nowrap title="<?php echo $Tz01_numcgm; ?>">
                <?php echo $Lz01_numcgm; ?>
              </td>
              <td nowrap colspan="3">
                <?php db_input('z01_numcgm', 42, $Iz01_numcgm, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tz01_nome; ?>">
                <?php echo $Lz01_nome; ?>
              </td>
              <td nowrap colspan="3">
                <?php db_input('z01_nome', 42, $Iz01_nome, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tk00_descr; ?>">
                <?php echo $Lk00_descr; ?>
              </td>
              <td nowrap colspan="3">
                <?php db_input('k00_descr', 42, $Ik00_descr, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tk00_numpar; ?>" width="160">
                <?php echo $Lk00_numpar; ?>
              </td>
              <td nowrap colspan="3">
                <?php db_select('k00_numpar', $aParcelas, true, 2, "onChange=\"js_calculaValorComDesconto();\""); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tk00_receit; ?>">
                <?php echo $Lk00_receit; ?>
              </td>
              <td nowrap colspan="3">
                <?php db_select('k00_receit', $aReceitas, true, 2, "onChange=\"js_calculaValorComDesconto();\""); ?>
              </td>
            </tr>

            <tr>
            	<td colspan='4'>
            	  <fieldset>
            	  	<legend>Composição</legend>
            			<div id="gridComposicao"></div>
            		</fieldset>
            	</td>
            </tr>

            <?php
            	db_input('k00_valor', 15, 0, true,'hidden', 3, '', 'nValorTotal');
            	db_input('k00_valor', 15, "", true, 'hidden', 3,'', 'nValorHistorico');
            ?>
          </table>

        </fieldset>

        <fieldset>

          <legend>Desconto</legend>

          <table>

            <tr>
              <td nowrap title="Valor Total" width="160">
                <strong>Liberado para desconto:</strong>
              </td>
              <td nowrap>
                <?php db_input('k00_valor', 15, 0, true,'text', 3, '', 'nValorLimite'); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Percentual de desconto">
                <strong>Percentual:</strong>
              </td>
              <td nowrap>
                <?php db_input('nPercentual', 15, 4, true, 'text', 1, 'onChange="js_calculaDesconto(\'porcentagem\');"'); ?>
                <?php db_input('nPercentualCompleto', 15, 4, true, 'hidden', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Valor de desconto">
                <strong>Valor:</strong>
              </td>
              <td nowrap>
                <?php db_input('nValorDesconto', 15, 4, true, 'text', 1, 'onChange="js_calculaDesconto(\'valor\');"'); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tk00_histtxt; ?>" colspan="4">
                <fieldset>
                  <legend><?php echo $Lk00_histtxt; ?> </legend>
                  <?php db_textarea('k00_histtxt', 5, 73, $Ik00_histtxt, true, 'text', 2); ?>
                </fieldset>
              </td>
            </tr>
          </table>

        </fieldset>

        <input type="button" name="incluir" onClick="js_incluirDesconto();" value="Incluir Desconto" />
        <input type="button" name="voltar"  onClick="js_voltar()"           value="Voltar"  />

      </div>
    </form>

</div>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>
<script type="text/javascript">

/**
 * Arquivo de RPC
 */
var sUrlRPC = 'arr4_descontomanual.RPC.php';

js_gridComposicao();
js_calculaValorComDesconto();

function js_gridComposicao() {

	oGridComposicao = new DBGrid('gridComposicao');
	oGridComposicao.nameInstance = 'gridComposicao';
	oGridComposicao.setCellAlign(new Array('left', 'right', 'right', 'right'));
	oGridComposicao.setCellWidth(new Array('15%', '25%', '30%', '30%'));
	oGridComposicao.setHeader(new Array('Débito' , 'Antes', 'Desconto',  'Após'));
	oGridComposicao.show($('gridComposicao'));
}

function js_calculaValorComDesconto() {

	if ( $F('k00_numpre') == '' ) {
    return false;
  }

  var oParametros          = new Object();
  oParametros.exec         = 'getValorComDesconto';
  oParametros.iNumpre      = $F('k00_numpre');
  oParametros.iNumpar      = $F('k00_numpar');
  oParametros.iReceita     = $F('k00_receit');
  oParametros.nPercentual  = $F('nPercentualCompleto').getNumber();

  js_divCarregando("Buscando dados do numpre...\nAguarde", 'msgBox');

  var oAjax = new Ajax.Request(sUrlRPC,
                               {method     : 'post',
                                parameters : 'json=' + Object.toJSON(oParametros),
                                onComplete : js_retornoCalculaValorComDesconto }
  );
}

function js_retornoCalculaValorComDesconto(oAjax) {

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  if ( oRetorno.iStatus > 1 ) {

    alert( sMensagem );
    return false;

  }

  js_montaGridComposicao(oRetorno);

  $('nValorHistorico').value = oRetorno.nValorHistorico;
  $('nValorLimite').value    = js_formatar(oRetorno.nValorLimite, 'f');

  js_removeObj('msgBox');
}

function js_montaGridComposicao(oValores) {

	sLabelHistorico         = '<strong>Histórico</strong>';
  sLabelCorrigido         = '<strong>Corrigido</strong>';
  sLabelJuros             = '<strong>Juros</strong>';
  sLabelMulta             = '<strong>Multa</strong>';
  sLabelTotal             = '<strong>Total</strong>';

  nValorHistoricoAnterior = oValores.nValorHistoricoAnterior;
  nValorCorrigidoAnterior = oValores.nValorCorrigidoAnterior;
  nValorJurosAnterior     = oValores.nValorJurosAnterior    ;
  nValorMultaAnterior     = oValores.nValorMultaAnterior    ;
  nValorTotalAnterior     = oValores.nValorTotalAnterior    ;

  nValorHistoricoDepois   = oValores.nValorHistorico;
  nValorCorrigidoDepois   = oValores.nValorCorrigido;
  nValorJurosDepois       = oValores.nValorJuros    ;
  nValorMultaDepois       = oValores.nValorMulta    ;
  nValorTotalDepois       = oValores.nValorTotal    ;

  nValorHistoricoDesconto = nValorHistoricoAnterior - nValorHistoricoDepois;
  nValorCorrigidoDesconto = nValorCorrigidoAnterior - nValorCorrigidoDepois;
  nValorJurosDesconto     = nValorJurosAnterior     - nValorJurosDepois    ;
  nValorMultaDesconto     = nValorMultaAnterior     - nValorMultaDepois    ;
  nValorTotalDesconto     = nValorTotalAnterior     - nValorTotalDepois    ;

  var aLinhaHistorico = [sLabelHistorico,
                         js_formatar( nValorHistoricoAnterior, "f"),
                         js_formatar( nValorHistoricoDesconto, "f"),
                         js_formatar( nValorHistoricoDepois, "f")];
  var aLinhaCorrigido = [sLabelCorrigido,
                         js_formatar( nValorCorrigidoAnterior, "f"),
                         js_formatar( nValorCorrigidoDesconto, "f"),
                         js_formatar( nValorCorrigidoDepois, "f")];
  var aLinhaJuros     = [sLabelJuros,
                         js_formatar( nValorJurosAnterior, "f"),
                         js_formatar( nValorJurosDesconto, "f"),
                         js_formatar( nValorJurosDepois, "f")];
  var aLinhaMulta     = [sLabelMulta,
                         js_formatar( nValorMultaAnterior, "f"),
                         js_formatar( nValorMultaDesconto, "f"),
                         js_formatar( nValorMultaDepois, "f")];
  var aLinhaTotal     = [sLabelTotal,
                         js_formatar( nValorTotalAnterior, "f"),
                         js_formatar( nValorTotalDesconto, "f"),
                         js_formatar( nValorTotalDepois, "f")];

  oGridComposicao.clearAll(true);
	oGridComposicao.addRow(aLinhaHistorico);
	oGridComposicao.addRow(aLinhaCorrigido);
	oGridComposicao.addRow(aLinhaJuros);
	oGridComposicao.addRow(aLinhaMulta);
	oGridComposicao.addRow(aLinhaTotal);
	oGridComposicao.renderRows();

}

/**
 * Incluir desconto
 *
 * @access public
 * @return void
 */
function js_incluirDesconto() {

  if ( $F('k00_numpre') == '' || !js_validaFormulario() ) {
    return false;
  }

  var oParametros                 = new Object();
  oParametros.exec                = 'incluirDesconto';
  oParametros.iCgm                = $F('z01_numcgm');
  oParametros.iNumpre             = $F('k00_numpre');
  oParametros.iNumpar             = $F('k00_numpar');
  oParametros.iReceita            = $F('k00_receit');
  oParametros.nPercentualLimitado = $F('nPercentual').getNumber();
  oParametros.nValorHistorico     = $F('nValorHistorico').getNumber();
  oParametros.nValorDesconto      = $F('nValorDesconto').getNumber();
  oParametros.nPercentual         = $F('nPercentualCompleto').getNumber();
  oParametros.sObservacao         = encodeURIComponent(tagString( $F('k00_histtxt') ) );

  js_divCarregando("Incluindo desconto...\nAguarde", 'msgBox');

  var oAjax = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoIncluirDesconto
    }
  );

}

/**
 * Chamada pela funcao js_incluirDesconto no retorno do rpc
 *
 * @param oAjax $oAjax
 * @access public
 * @return bool
 */
function js_retornoIncluirDesconto(oAjax) {

  js_removeObj('msgBox');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  /**
   * Erro
   */
  if ( oRetorno.iStatus > 1 ) {

    alert( sMensagem );
    return false;
  }

  alert(sMensagem);
  location.href = 'arr4_descontomanual001.php';
}

/**
 * Funcao para validar formulario
 *
 * @access public
 * @return bool
 */
function js_validaFormulario() {

  /**
   * Valida se foi informado valor/porcentagem do desconto
   */
  var nValorLimite    = parseFloat($F('nValorLimite').getNumber());
  var nValorHistorico = parseFloat($F('nValorHistorico').getNumber());

  if ( nValorHistorico == nValorLimite ) {

    alert('Desconto não Informado.');
    return false;
  }

  /**
   * Valida o campo com observacao
   */
  if ( $F('k00_histtxt') == '' ) {

    alert('Campo Observação não informado.');
    return false;
  }

  return true;
}

/**
 * Calcula desconto
 *
 * @param sTipo $sTipo - tipo de calculo, pelo valor ou por porcentagem
 * @access public
 * @return void
 */
function js_calculaDesconto(sTipo) {

  var nValorTotal    = $F('nValorTotal');
  var nValorDesconto = $F('nValorDesconto');
  var nPercentual    = $F('nPercentual');
  var nValorLimite   = $F('nValorLimite');
  var nValorLimite   = parseFloat($F('nValorLimite').getNumber());
  var oRegex         = /-/;

  if ( oRegex.test(nPercentual) || oRegex.test(nValorDesconto) ) {

    nPercentual    = 0;
    nValorDesconto = 0;
    alert('Desconto não pode ser negativo.');
  }

  /**
   * Calcular valor desconto
   */
  if ( sTipo == 'valor' ) {

    nPercentual = nValorDesconto * 100 / nValorLimite;

    /**
     * Percentual invalido, erro no calculo
     */
    if ( isNaN(nPercentual) ) {
      return false;
    }
  }

  /**
   * Calcular valor desconto pela porcentagem
   */
  if ( sTipo == 'porcentagem' ) {

    nValorDesconto = nPercentual * nValorLimite / 100;

    /**
     * valor de desconto invalido, erro no calculo
     */
    if ( isNaN(nValorDesconto) ) {
      return false;
    }
  }






  nValorDesconto = new Number(nValorDesconto);
  nValorLimite   = new Number(nValorLimite);

  if ( nValorDesconto >= nValorLimite ) {

    var sErro  = 'Valor calculado para o desconto igual ao valor histórico.\n';
        sErro += 'Para cancelamento total de um debito, use a opção Cancelamento de débito na consulta geral financeira.';
    alert(sErro);
    return false;
  }

  /**
   * Atualiza campos
   */
  $('nPercentualCompleto').value = nPercentual;
  $('nValorDesconto').value      = js_formatar(nValorDesconto, 'f');
  $('nPercentual').value         = js_formatar(nPercentual, 'f');

  js_calculaValorComDesconto();

}

function js_voltar() {
  window.location.href = 'arr4_descontomanual001.php'
}
</script>
