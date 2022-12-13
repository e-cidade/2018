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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcaixa.php"));
require_once(modification("classes/db_slip_classe.php"));

$clslip = new cl_slip;
$clrotulo = new rotulocampo;
$clrotulo->label('k17_codigo');
$clrotulo->label('z01_nome');
$clrotulo->label('k17_debito');
$clrotulo->label('k17_credito');
$clrotulo->label('k17_hist');
$clrotulo->label('k17_valor');
$clrotulo->label('k17_texto');
$clrotulo->label('k17_numcgm');
$clrotulo->label('k17_dtanu');
$clrotulo->label('k18_motivo');
$clrotulo->label('k17_motivoestorno');
$clrotulo->label('k17_dtestorno');

$get = db_utils::postMemory($_GET);
$sql = "         select slip.k17_codigo,
                             k17_data,
                             k17_debito,
	                         c1.c60_descr as debito_descr,
		                     k17_credito,
						     c2.c60_descr as credito_descr,
						     k17_valor,
						     k17_hist,
								 c50_descr,
						     k17_texto,
						     k17_dtaut,
						     k17_dtanu,
						     k17_autent,
						     k17_dtestorno,
						     k17_motivoestorno,
						     k17_situacao as k17_tiposituacao,
						     k18_codigo,
						     k18_motivo, k145_numeroprocesso,
								 (case when k17_situacao = 1 then 'Não Autenticado'
								       when k17_situacao = 2 then 'Autenticado'
                       when k17_situacao = 3 then 'Estornado'
											 when k17_situacao = 4 then 'Anulado'
									end
									) as k17_situacao,
						     z01_numcgm,
						     z01_nome,
						     (select rh82_rhslipfolha from rhslipfolhaslip where rh82_slip = {$get->slip}) as slip_folha
                      from slip
	                     left join conplanoreduz r1 on r1.c61_reduz       = k17_debito
	                                               and r1.c61_anousu      = extract(year from k17_data)
	                     left join conplano c1      on c1.c60_codcon      = r1.c61_codcon
	                                               and c1.c60_anousu      = r1.c61_anousu
	                     left join conplanoreduz r2 on r2.c61_reduz       = k17_credito
	                                               and r2.c61_anousu      = extract(year from k17_data)
		                   left join conplano c2      on c2.c60_codcon      = r2.c61_codcon
		                                             and c2.c60_anousu      = r2.c61_anousu
		                   left join slipanul         on k18_codigo         = k17_codigo
                       left join conhist          on k17_hist           = c50_codhist
		                   left join slipnum          on slipnum.k17_codigo = slip.k17_codigo
		                   left join cgm              on cgm.z01_numcgm     = slipnum.k17_numcgm
                       left join slipprocesso on slip.k17_codigo = slipprocesso.k145_slip
                WHERE  slip.k17_codigo = " . $get->slip . "
		          order by slip.k17_codigo";

$rsSlip = $clslip->sql_record($sql);

$oSlip = db_utils::fieldsMemory($rsSlip, 0);

$sqlslipage = "select e81_codage,
                    e91_cheque
               from empageslip
                    left join empagemov     on e89_codmov=e81_codmov
                    left join empageconfche on e91_codmov=e81_codmov and e91_ativo is true
                    left join empage        on e81_codage=e80_codage
              where empageslip.e89_codigo=" . $get->slip . "
                    and e81_cancelado is null";
$result = $clslip->sql_record($sqlslipage);

if ($clslip->numrows > 0) {
  $aSlip = db_utils::fieldsMemory($result, 0);
}

$oSlipCPCA = db_utils::getDao('slipconcarpeculiar');
$sWhereSlip = "k131_slip = {$get->slip}";
$sSqlBuscaCP = $oSlipCPCA
  ->sql_query_concarpeculiar(null, "c58_sequencial, c58_descr, k131_tipo", "k131_tipo", $sWhereSlip);
$rsSqlBuscaCP = $oSlipCPCA->sql_record($sSqlBuscaCP);

$sCodigoCPDebito = "";
$sDescricaoCPDebito = "";

$sCodigoCPCredito = "";
$sDescricaoCPCredito = "";

if ($oSlipCPCA->numrows > 0) {

  for ($iRowCP = 0; $iRowCP < $oSlipCPCA->numrows; $iRowCP++) {

    $oStdDadoCP = db_utils::fieldsMemory($rsSqlBuscaCP, $iRowCP);

    switch ($oStdDadoCP->k131_tipo) {

      case 1:
        $sCodigoCPDebito = $oStdDadoCP->c58_sequencial;
        $sDescricaoCPDebito = $oStdDadoCP->c58_descr;
        break;

      case 2:
        $sCodigoCPCredito = $oStdDadoCP->c58_sequencial;
        $sDescricaoCPCredito = $oStdDadoCP->c58_descr;
        break;
    }
  }
}

$oTransferencia       = TransferenciaFactory::getInstance(null, $oSlip->k17_codigo);
$oFinalidadePagamento = $oTransferencia->getFinalidadePagamentoFundebCredito();

$sCodigoFinalidadeFundeb    = "";
$sDescricaoFinalidadeFundeb = "";
if ( !empty($oFinalidadePagamento) ) {

  $sCodigoFinalidadeFundeb    = $oFinalidadePagamento->getCodigo();
  $sDescricaoFinalidadeFundeb = $oFinalidadePagamento->getDescricao();
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
  db_app::load("datagrid.widget.js, grid.style.css, classes/infoLancamentoContabil.classe.js");
  db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");

  ?>
  <style>
    input[type='text'], textarea {background-color: white;border: 0px}
  </style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td  valign="top" bgcolor="#CCCCCC">
    </td>
  </tr>
</table>
<center>
  <table>
    <tr>
      <td><fieldset><legend><b>SLIP <?=$get->slip; ?></b></legend>
          <table >
            <tr>
              <td>
                <?=$Lk17_codigo; ?>
              </td>
              <td>
                <input type='text' value="<?=$oSlip->k17_codigo; ?>" size="10" readonly >
              </td>
              <td>
                <strong>Situação:</strong>
              </td>
              <td><input type='text' value="<?=$oSlip->k17_situacao; ?>" size=42 readonly '></td>
            </tr>
            <tr>
              <td>
                <?=$Lk17_debito; ?>
              </td>
              <td colspan=4>
                <input type='text' value="<?=$oSlip->k17_debito; ?>" size=10 readonly '>
                &nbsp;
                <input type='text' size=50 readonly value='<?=$oSlip->debito_descr; ?>' '>
              </td>
            </tr>
            <tr>
              <td>
                <b>CP/CA Débito:</b>
              </td>
              <td colspan=4>
                <input type='text' value="<?=$sCodigoCPDebito; ?>" size=10 readonly '>
                &nbsp;
                <input type='text' size=50 readonly value='<?=$sDescricaoCPDebito; ?>' '>
              </td>
            </tr>
            <tr>
              <td>
                <?=$Lk17_credito; ?>
              </td>
              <td colspan=4>
                <input type='text' value="<?=$oSlip->k17_credito; ?>" size=10 readonly '>
                &nbsp;
                <input type='text' size=50 readonly value='<?=$oSlip->credito_descr; ?>' '>
              </td>
            </tr>
            <tr>
              <td>
                <b>CP/CA Crédito:</b>
              </td>
              <td colspan=4>
                <input type='text' value="<?=$sCodigoCPCredito; ?>" size=10 readonly '>
                &nbsp;
                <input type='text' size=80 readonly value='<?=$sDescricaoCPCredito; ?>' '>
              </td>
            </tr>
            <tr>
              <td>
                <b>Finalidade FUNDEB (Crédito):</b>
              </td>
              <td colspan=4>
                <input type='text' value="<?=$sCodigoFinalidadeFundeb; ?>" size=10 readonly '>
                &nbsp;
                <input type='text' size=80 readonly value='<?=$sDescricaoFinalidadeFundeb; ?>' '>
              </td>
            </tr>
            <tr>
              <td>
                <?=$Lk17_hist; ?>
              </td>
              <td colspan=4>
                <input type='text' value="<?=$oSlip->k17_hist; ?>" size=10 readonly '>
                &nbsp;
                <input type='text' size=50 readonly value='<?=$oSlip->c50_descr; ?>' '>
              </td>
            </tr>
            <tr>
              <td nowrap>
                <?=$Lz01_nome; ?>
              </td>
              <td colspan=4>
                <input type='text' value="<?= "{$oSlip->z01_numcgm} - {$oSlip->z01_nome}"; ?>" size=64 readonly '>
              <td>
            </tr>
            <tr>
              <td>
                <?=$Lk17_valor; ?>
              </td>
              <td>
                <input type='text' value="<?=trim(db_formatar($oSlip->k17_valor, "f")); ?>" size=10 readonly '>
              </td>
              <td>
                <strong>Cheque:</strong>
              </td>
              <td><input type='text' value="<?=@$aSlip->e91_cheque; ?>" size=10 readonly '>
                <strong>Agenda:</strong>
                <input type='text' size=6 readonly ' value=<?=@$aSlip->e81_codage; ?>>
              <td>

            </tr>
            <tr>
              <td><b>Data Emissao:</b></td>
              <td><input type='text' value="<?=db_formatar($oSlip->k17_data, "d"); ?>" size=10 readonly />
              <td><b>Anulação:</b></td>
              <td><input type='text' value="<?=db_formatar($oSlip->k17_dtanu, "d"); ?>" size=10 readonly />
            </tr>


            <tr>
              <td><strong>Processo Administrativo:</strong></td>
              <td><input type='text' value="<?=$oSlip->k145_numeroprocesso; ?>" size=10 readonly />
              <? if (!empty($oSlip->slip_folha)) { ?>
                <td><b>Origem:</b></td>
                <td><input type='text' value="Folha de Pagamento" size="20" readonly />
              <? } else { ?>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <? } ?>
            </tr>

            <tr>
              <td valign="top" colspan="4">
                <fieldset>
                  <legend><b><?=$Lk17_texto; ?></b></legend>
                  <textarea style="width: 100%; height: 100px;" readonly><?= str_replace('\n', "\n", $oSlip->k17_texto); ?></textarea>
                </fieldset>
              </td>
            </tr>

            <? if (isset($oSlip->k18_codigo) && $oSlip->k18_codigo != null) { ?>
              <tr>
                <td valign="top" colspan=4>
                  <fieldset>
                    <legend><b><?=$Lk18_motivo; ?></b></legend>
                    <textarea style="width: 100%; height: 100px;" readonly><?=$oSlip->k18_motivo; ?></textarea>
                  </fieldset>
                </td>
              </tr>
            <? } ?>
            <? if (isset($oSlip->k17_tiposituacao) && $oSlip->k17_tiposituacao != null && $oSlip->k17_tiposituacao == 3) { ?>
              <tr>
                <td><b><?=$Lk17_dtestorno; ?></b></td>
                <td colspan="3"><input type='text' value="<?=db_formatar($oSlip->k17_dtestorno, "d"); ?>" size=10 readonly '></td>
              </tr>
              <tr>
                <td valign="top" colspan="4">
                  <fieldset>
                    <legend><b><?=$Lk17_motivoestorno; ?></b></legend>
                    <textarea  style="width: 100%; height: 100px;" readonly><?= str_replace('\n', "\n", $oSlip->k17_motivoestorno); ?></textarea>
                  </fieldset>
                </td>
              </tr>
            <? } ?>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <center>
    <fieldset style="width:890px">
      <legend><b>Autenticações</b></legend>
      <div id="ctnGridAutenticacao">
      </div>
    </fieldset>
  </center>
  <p align="center">
    <input type='button' value='Retornar' onclick='parent.db_iframe_slip2.hide()'>
    <input type='button' value='Imprimir Slip' onclick='js_emite(<?=$oSlip->k17_codigo; ?>)'>
  </p>

</body>
</html>
<script>

  var iCodigoSlip = <?php echo $oSlip->k17_codigo;?>;
  var sUrlRPC     = "cai4_transferencia.RPC.php";

  function js_pesquisaAutenticacaoSlip() {

    js_divCarregando("Aguarde, carregando autenticações...", "msgBox");

    var oParam         = new Object();
    oParam.exec        = "getAutenticacoesSlip";
    oParam.iCodigoSlip = iCodigoSlip;

    var oAjax = new Ajax.Request(sUrlRPC,
      {method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete:js_preencheGrid
      });
  }

  function js_preencheGrid(oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.aAutenticacoes.length == 0) {
      return false;
    }
    oGridAutenticacao.clearAll(true);
    oRetorno.aAutenticacoes.each(function (oAutenticacao, iIndice) {

      var aRow = new Array();
      aRow[0] = oAutenticacao.codigo_lancamento;
      aRow[1] = oAutenticacao.k12_id;
      aRow[2] = js_formatar(oAutenticacao.k12_data, "d");
      aRow[3] = oAutenticacao.k12_hora;
      aRow[4] = oAutenticacao.k12_autent;
      aRow[5] = js_formatar(oAutenticacao.k12_valor, "f");
      aRow[6] = oAutenticacao.e91_cheque;
      aRow[7] = oAutenticacao.descricao.urlDecode();

      oGridAutenticacao.addRow(aRow);
      oGridAutenticacao.aRows[iIndice].sEvents = "onDblClick='js_abreDetalhesLancamento("+oAutenticacao.codigo_lancamento+");'";


    });
    oGridAutenticacao.renderRows();
  }

  function js_abreDetalhesLancamento(iCodigoLancamento) {

    if (iCodigoLancamento == 0 || iCodigoLancamento == null) {

      var sMsgErro = "Esta movimentação não possui vínculo com o lançamento contábil.";
      alert(sMsgErro);
      return false;
    }
    var oViewLancamento = new infoLancamentoContabil(iCodigoLancamento);

  }


  function js_emite(slip){
    window.open('cai1_slip003.php?numslip='+slip,'','location=0');
  }

  var aHeaders = new Array("Codigo Lancamento", "Terminal", "Data", "Hora", "Autenticação", "Valor", "Cheque", "Forma");
  var aAlign   = new Array("center", "center", "center", "center", "center", "right", "right", "right");
  var aWidth   = new Array("10%", "10%", "10%", "10%%", "20%", "10%", "30%");

  var oGridAutenticacao          = new DBGrid('ctnGridAutenticacao');
  oGridAutenticacao.nameInstance = 'oGridAutenticacao';
  oGridAutenticacao.setHeight(200);
  oGridAutenticacao.setHeader(aHeaders);
  oGridAutenticacao.setCellWidth(aWidth);
  oGridAutenticacao.setCellAlign(aAlign);
  oGridAutenticacao.aHeaders[0].lDisplayed = false;
  oGridAutenticacao.show($("ctnGridAutenticacao"));
  oGridAutenticacao.setStatus("Dois cliques para ver os detalhes do lançamento.");
  js_pesquisaAutenticacaoSlip();
</script>
