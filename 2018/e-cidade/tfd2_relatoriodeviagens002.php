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
require_once('fpdf151/pdf.php');
require_once("libs/db_stdlib.php");
require_once('libs/db_utils.php');

define('MSG_RELATORIOVIAGEM', 'saude.tfd.tfd2_relatoriodeviagens002.');
$oMsgErro = new stdClass();

$oGet = db_utils::postMemory($_GET);

$oDataInicial = new DBDate($oGet->dataInicial);
$oDataFinal   = new DBDate($oGet->dataFinal);


$sCampos  = " distinct                                    ";
$sCampos .= " tf01_i_codigo          as pedido,           ";
$sCampos .= " tf03_i_codigo          as cod_destino,      ";
$sCampos .= " tf03_c_descr           as destino,          ";
$sCampos .= " tf26_i_codigo          as cod_situacao,     ";
$sCampos .= " tf26_c_descr           as situacao,         ";
$sCampos .= " tf10_i_prestadora      as cod_prestadora,   ";
$sCampos .= " z01_v_nome             as paciente,         ";
$sCampos .= " z01_i_cgsund           as cgs,              ";
$sCampos .= " tf17_d_datasaida       as data_saida,       ";
$sCampos .= " tf17_c_horasaida       as hora_saida,       ";
$sCampos .= " tf16_d_dataagendamento as data_agendamento, ";
$sCampos .= " tf16_c_horaagendamento as hora_agendamento, ";
$sCampos .= " cgmprest.z01_nome      as prestadora,       ";
$sCampos .= " tf17_c_localsaida      as local_saida,      ";
$sCampos .= " trim( tf28_c_obs )     as observacao,       ";
$sCampos .= " tf28_i_codigo                               ";

$sIntevalo = $oDataInicial->getDate()."' and '".$oDataFinal->getDate();
$aWhere    = array();
$aWhere[]  = " tf16_d_dataagendamento between '$sIntevalo' ";
$aWhere[]  = " tf16_i_pedidotfd is not null ";

if( isset( $oGet->iSituacao ) && !empty( $oGet->iSituacao ) ) {
  $aWhere[] = " tf26_i_codigo = {$oGet->iSituacao} ";
}

if ($oGet->iDestino != '') {
  $aWhere[] = " tf03_i_codigo = $oGet->iDestino";
}

$sWhere = implode(" and ", $aWhere);
$sOrdem = "tf10_i_prestadora, tf26_i_codigo, tf16_d_dataagendamento, tf01_i_codigo, paciente, tf28_i_codigo desc";

$oDaoPedido = new cl_tfd_pedidotfd();
$sSql       = $oDaoPedido->sql_query_pedido_relatorio('', $sCampos, $sOrdem, $sWhere);
$aPedidos   = array();

try {

  $rs = db_query($sSql);

  if (!$rs) {

    $oMsgErro->sErro = pg_last_error();
    throw new Exception( _M( MSG_RELATORIOVIAGEM . "erro_buscar_dados", $oMsgErro) );
  }
  $iLinhas = pg_num_rows($rs);
  if ($iLinhas == 0) {
    throw new Exception( _M( MSG_RELATORIOVIAGEM . "nenhum_registro_encontrado") );
  }

  $aControlaPedidosDuplicados = array();

  for ($i=0; $i < $iLinhas; $i++) {

    $oDados = db_utils::fieldsMemory($rs, $i);

    if( in_array( $oDados->pedido, $aControlaPedidosDuplicados ) ) {
      continue;
    }

    $aControlaPedidosDuplicados[] = $oDados->pedido;

    $iDestino  = $oDados->cod_destino;
    $iSituacao = $oDados->cod_situacao;
    if ( !array_key_exists($iDestino, $aPedidos) ) {

      $oDestino            = new stdClass();
      $oDestino->iDestino  = $oDados->cod_destino;
      $oDestino->sDestino  = $oDados->destino;
      $oDestino->aSituacao = array();

      $aPedidos[$oDados->cod_destino] = $oDestino;
    }

    if ( !array_key_exists($iSituacao, $aPedidos[$iDestino]->aSituacao) ) {

      $oSituacao            = new stdClass();
      $oSituacao->iSituacao = $oDados->cod_situacao;
      $oSituacao->sSituacao = $oDados->situacao;
      $oSituacao->aPedidos  = array();
      $aPedidos[$iDestino]->aSituacao[$iSituacao] = $oSituacao;
    }


    $aPedidos[$iDestino]->aSituacao[$iSituacao]->aPedidos[] = $oDados;
  }

} catch (Exception $e) {

  $sMsg = $e->getMessage();
  db_redireciona('db_erros.php?fechar=true&db_erro='. trim($sMsg) );
}

/**
 * Configura as larguras das colunas
 * @var stdClass
 */
$oConfig = new stdClass();
$oConfig->iColunaPedido     = 13;
$oConfig->iColunaPaciente   = 85;
$oConfig->iColunaData       = 15;
$oConfig->iColunaHora       = 10;
$oConfig->iColunaPrestadora = 70;

$head1 = 'RELATÓRIO DE VIAGENS';
$head2 = 'Período: ' . $oDataInicial->convertTo(DBDate::DATA_PTBR) . ' até ' . $oDataFinal->convertTo(DBDate::DATA_PTBR);

$head3 = "";
$head4 = "ATIVO: Pedido em andamento";
$head5 = "ENCERRADO: Paciente viajou";
$head6 = "DESISTÊNCIA: Paciente não viajou";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(230);
$oPdf->AddPage();

foreach ($aPedidos as $oDestino) {

  $oPdf->SetFont('arial', 'B', 8);
  $oPdf->cell(193, 4, "Destino: {$oDestino->sDestino}", 'B', 1);

  foreach ($oDestino->aSituacao as $oSituacao) {

    $oPdf->SetFont('arial', 'B', 8);
    $oPdf->ln(2.5);
    $oPdf->cell(193, 4, $oSituacao->sSituacao, 'B', 1);
    $oPdf->ln(0.4);

    $lImprimeCabecalho = true;
    foreach ($oSituacao->aPedidos as $oPedido) {

      if ($lImprimeCabecalho) {

        $lImprimeCabecalho = false;
        imprimeCabecalhoPedidos($oPdf, $oConfig);
      }

      $oPdf->SetFont('arial', '', 7);

      $sPaciente = "{$oPedido->cgs} - {$oPedido->paciente}";
      $oData     = new DBDate($oPedido->data_agendamento);

      $oPdf->cell($oConfig->iColunaPedido,     4, $oPedido->pedido,  1, 0, "C");
      $oPdf->cell($oConfig->iColunaPaciente,   4, $sPaciente,  1, 0, "L");
      $oPdf->cell($oConfig->iColunaData,       4, $oData->convertTo(DBDate::DATA_PTBR),  1, 0, "C");
      $oPdf->cell($oConfig->iColunaHora,       4, $oPedido->hora_agendamento,  1, 0, "C");
      $oPdf->cell($oConfig->iColunaPrestadora, 4, $oPedido->prestadora,  1, 1, "L");

      if( $oPedido->cod_situacao == 4 && $oPedido->observacao != '' ) {

        $oPdf->SetFont('arial', '', 7);
        $oPdf->MultiCell( 193, 4, str_repeat( ' ', 19 ) . "Motivo: {$oPedido->observacao}", 1, 'L' );
      }
    }
  }

  $oPdf->ln();
}



/**
 * Imprime o totalizador
 */
$iTotalAtivos      = 0;
$iTotalEncerrados  = 0;
$iTotalDesistentes = 0;
$iTotalGeral       = 0;

$oPdf->AddPage();
$oPdf->SetFont('arial', 'B', 7);
$oPdf->cell(73, 4, "Destino",      "BTR", 0, 'C', 1);
$oPdf->cell(30,  4, "Ativos",          1, 0, 'C', 1);
$oPdf->cell(30,  4, "Encerrados",      1, 0, 'C', 1);
$oPdf->cell(30,  4, "Desistentes",     1, 0, 'C', 1);
$oPdf->cell(30,  4, "Total",       "BTL", 1, 'C', 1);

foreach ($aPedidos as $oDestino) {

  $iAtivos      = isset($oDestino->aSituacao[1]) ? count($oDestino->aSituacao[1]->aPedidos) : 0;
  $iEncerrados  = isset($oDestino->aSituacao[2]) ? count($oDestino->aSituacao[2]->aPedidos) : 0;
  $iDesistentes = isset($oDestino->aSituacao[4]) ? count($oDestino->aSituacao[4]->aPedidos) : 0;
  $iTotal       = $iAtivos + $iEncerrados + $iDesistentes;

  $oPdf->SetFont('arial', '', 7);
  $oPdf->cell(73, 4, $oDestino->sDestino, "BTR", 0, 'L');
  $oPdf->cell(30, 4, "{$iAtivos}",            1, 0, 'R');
  $oPdf->cell(30, 4, "{$iEncerrados}",        1, 0, 'R');
  $oPdf->cell(30, 4, "{$iDesistentes}",       1, 0, 'R');
  $oPdf->cell(30, 4, "{$iTotal}",         "BTL", 1, 'R');


  $iTotalAtivos      += $iAtivos;
  $iTotalEncerrados  += $iEncerrados;
  $iTotalDesistentes += $iDesistentes;
  $iTotalGeral       += $iTotal;

}
$oPdf->SetFont('arial', 'B', 7);
$oPdf->cell(73, 4, "TOTAL GERAL",       "BTR", 0, 'R');
$oPdf->cell(30, 4, $iTotalAtivos,           1, 0, 'R');
$oPdf->cell(30, 4, $iTotalEncerrados,       1, 0, 'R');
$oPdf->cell(30, 4, $iTotalDesistentes,      1, 0, 'R');
$oPdf->cell(30, 4, $iTotalGeral,        "BTL", 1, 'R');


/**
 * Imprime o cabeçalho dos pedidos
 * @param  FPDF     $oPdf
 * @param  stdClass $oConfig
 * @return void
 */
function imprimeCabecalhoPedidos(FPDF $oPdf, $oConfig) {

  $oPdf->SetFont('arial', 'B', 7);
  $oPdf->cell($oConfig->iColunaPedido,     4, "Pedido",         1, 0, "C", 1);
  $oPdf->cell($oConfig->iColunaPaciente,   4, "CGS - Paciente", 1, 0, "C", 1);
  $oPdf->cell($oConfig->iColunaData,       4, "Data ",          1, 0, "C", 1);
  $oPdf->cell($oConfig->iColunaHora,       4, "Hora ",          1, 0, "C", 1);
  $oPdf->cell($oConfig->iColunaPrestadora, 4, "Prestadora",     1, 1, "C", 1);

}

$oPdf->Output();