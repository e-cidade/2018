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

require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
/* ==================================================================
 *               ESPECIFICAÇÕES GERAÇÃO RELATÓRIO
 * ==================================================================
 *  $tipo    = 0 -> VIAGENS
 *           = 1 -> COM TOTAIS (apenas registros com ajuda de custo)
 *           = 2 -> SEM TOTAIS (com ou sem ajuda de custo)
 *
 *  $destino = CIDADE PRESTADORA -> tf03_i_codigo
 * ==================================================================
 */

$oGet = db_utils::postMemory( $_GET );

$oDaoTfdPedidoTfd = new cl_tfd_pedidotfd();
$oDaoCgsUnd       = new cl_cgs_und();

$oDataInicial = new DBDate($oGet->dataInicial);
$oDataFinal   = new DBDate($oGet->dataFinal);

$sCampos  = "   distinct  tf01_i_codigo ";
$sCampos .= " , z01_v_nome as paciente";
$sCampos .= " , (select coalesce(sum(tf15_f_valoremitido), 0) ";
$sCampos .= "    from tfd_ajudacustopedido ";
$sCampos .= "   inner join tfd_beneficiadosajudacusto on tf14_i_codigo =  tf15_i_ajudacustopedido ";
$sCampos .= "   where tf14_i_pedidotfd = tf01_i_codigo)::real as valor_ajuda";
$sCampos .= " , z01_i_cgsund as cgs";
$sCampos .= " , z01_d_nasc   as nascimento";
$sCampos .= " , z01_v_telcel as celular";
$sCampos .= " , z01_v_telef  as telefone";
$sCampos .= " , rh70_descr  as especialidade";
$sCampos .= " , z01_v_ident as identidade";
$sCampos .= " , tf17_c_localsaida as local_saida";
$sCampos .= " , tf17_d_datasaida  as data_saida";
$sCampos .= " , tf17_c_horasaida  as hora_saida";
$sCampos .= " , cgmprest.z01_numcgm as codigo_prestadora";
$sCampos .= " , cgmprest.z01_nome   as prestadora";
$sCampos .= " , tf03_c_descr        as destino";
$sCampos .= " , tf03_i_codigo       as codigo_destino";
$sCampos .= " , (select s115_c_cartaosus";
$sCampos .= "      from cgs_cartaosus";
$sCampos .= "     where s115_i_cgs = z01_i_cgsund";
$sCampos .= "     order by s115_c_tipo, s115_i_codigo limit 1) as cartaosus";

$sIntevalo = $oDataInicial->getDate()."' and '".$oDataFinal->getDate();

$sWhere  = " tf16_i_pedidotfd is not null ";
$sWhere .= " and tf17_i_pedidotfd is not null ";
$sWhere .= " and tf17_tiposaida <> 2 "; // Tipo 2 passagem
$sWhere .= " and tf17_d_datasaida between '$sIntevalo' ";
$sWhere .= " and tf26_i_codigo in (1, 3) ";

if ( !empty($oGet->destino) ) {
  $sWhere .= " and tf03_i_codigo = {$oGet->destino}";
}

$sOrdem      = 'tf03_i_codigo, cgmprest.z01_numcgm, tf17_d_datasaida, tf17_c_horasaida, paciente ';
$sSqlPedidos = $oDaoTfdPedidoTfd->sql_query_pedido('', $sCampos, $sOrdem, $sWhere);
$rsPedidos   = $oDaoTfdPedidoTfd->sql_record($sSqlPedidos);
$iLinhas     = $oDaoTfdPedidoTfd->numrows;

$aPedidos          = array();
$lRetornouRegistro = false;

for ($i=0; $i < $iLinhas; $i++) {

  $oDadosPedido = db_utils::fieldsMemory($rsPedidos, $i);

  $sHash = "{$oDadosPedido->codigo_destino}#{$oDadosPedido->codigo_prestadora}";

  if (!array_key_exists($sHash, $aPedidos)) {

    $oPrestadora               = new stdClass();
    $oPrestadora->iPrestadora  = $oDadosPedido->codigo_prestadora;
    $oPrestadora->sPrestadora  = $oDadosPedido->prestadora;
    $oPrestadora->iDestino     = $oDadosPedido->codigo_destino;
    $oPrestadora->sDestino     = $oDadosPedido->destino;
    $oPrestadora->iPassageiros = 0;
    $oPrestadora->aPacientes   = array();

    $aPedidos[$sHash] = $oPrestadora;
  }

  $oDadosPedido->aAcompanhante = buscaAcompanhantes( $oDadosPedido->tf01_i_codigo );
  $aPedidos[$sHash]->iPassageiros += 1;
  $aPedidos[$sHash]->iPassageiros += count($oDadosPedido->aAcompanhante);

  $aPedidos[$sHash]->aPacientes[] = $oDadosPedido;

  if ( $oGet->tipo != 1 ) {
    $lRetornouRegistro = true;
  } else if ( !empty($oDadosPedido->valor_ajuda) ) {
    $lRetornouRegistro = true;
  }
}

if ( !$lRetornouRegistro ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

function buscaAcompanhantes( $iPedido ) {

  $sCampos = " '+AC - '||z01_v_nome as paciente, z01_i_cgsund as cgs ";
  $sWhere  = "tipo = 2 and tf01_i_codigo = {$iPedido}";

  $oDaoCgsUnd       = new cl_cgs_und();
  $sSqlAcompanhante = $oDaoCgsUnd->sql_query_cgs_beneficiadosajudacusto(null, $sCampos, null, $sWhere);
  $rsAcompanhante   = db_query($sSqlAcompanhante);
  $aAcompanhante    = array();

  if ($rsAcompanhante && pg_num_rows($rsAcompanhante) > 0) {

    $iLinhas = pg_num_rows($rsAcompanhante);
    for ($i=0; $i < $iLinhas; $i++) {

      $oAcompanhante   = db_utils::fieldsMemory($rsAcompanhante, $i);
      $aAcompanhante[] = $oAcompanhante;
    }
  }

  return $aAcompanhante;
}

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false, 10);
$oPdf->SetFillColor(235);


function imprimeCabecalhoMotorista( $oPdf ) {

  $oPdf->addPage();
  $oPdf->line( 30, $oPdf->getY() + 3 , 90, $oPdf->getY() + 3);
  $oPdf->Cell( 90,  4, "MOTORISTA: ", 0, 1);
  $oPdf->line( 30,  $oPdf->getY() + 3,  90, $oPdf->getY() + 3);
  $oPdf->line( 110, $oPdf->getY() + 3, 135, $oPdf->getY() + 3);
  $oPdf->line( 145, $oPdf->getY() + 3, 165, $oPdf->getY() + 3);
  $oPdf->Cell( 90, 4, "VEÍCULO: ");
  $oPdf->Cell( 35, 4, "DATA: ");
  $oPdf->Cell( 25, 4, "HORA: ");
  $oPdf->Cell( 35, 4, "(D) DESISTENTE",      0, 0, "C" );
  $oPdf->Cell( 35, 4, "(NC) NÃO COMPARECEU", 0, 0, "C" );
  $oPdf->Cell( 35, 4, "(AC) ACOMPANHANTE",   0, 1, "C" );
  $oPdf->Ln();
}


$head2 = "Período.: ".$oDataInicial->convertTo(DBDate::DATA_PTBR)." a ".$oDataFinal->convertTo(DBDate::DATA_PTBR);
$head4 = "Destino.: ".($oGet->destino != ''  ? $oGet->sDestino : 'GERAL');

if ($oGet->tipo == 0) {

  $head1 = "Relatório Diário de Viagens";
  $head3 = "Tipo....: VIAGENS";

  imprimeModeloViagem( $aPedidos, $oPdf );
} else {

  $head1  = "Relatório de Saída";
  $head3  = "Tipo....: ";
  $head3 .=  $oGet->tipo == 1 ? "SAÍDAS COM TOTAIS" : "SAÍDAS SEM TOTAIS";
  imprimeModeloAjudaCusto( $aPedidos, $oPdf, $oGet );
}


function cabecalhoViagem($oPdf) {

  $oPdf->setFont("arial", "B", 7);
  $oPdf->cell(  15, 4, "CGS",         1, 0, "C", 1);
  $oPdf->cell(  95, 4, "PACIENTE",    1, 0, "C", 1);
  $oPdf->cell(  25, 4, "DT HR SAÍDA ",    1, 0, "C", 1);
  $oPdf->cell(  60, 4, "LOCAL SAÍDA", 1, 0, "C", 1);
  $oPdf->cell(  35, 4, "TELEFONE",    1, 0, "C", 1);
  $oPdf->cell(  20, 4, "IDENTIDADE",  1, 0, "C", 1);
  $oPdf->cell(  28, 4, "ASSINATURA",  1, 1, "C", 1);
  $oPdf->setFont("arial", "", 7);
}


function imprimeCabecalhoAjudaCusto( $oPdf ) {

  $oPdf->addPage();
  $oPdf->setFont("arial", "B", 7);
  $oPdf->Cell( 15, 4, "CGS",         1, 0, "C", 1);
  $oPdf->Cell( 90, 4, "PACIENTE",    1, 0, "C", 1);
  $oPdf->Cell( 20, 4, "NASCIMENTO",  1, 0, "C", 1);
  $oPdf->Cell( 23, 4, "CARTÃO SUS",  1, 0, "C", 1);
  $oPdf->Cell( 57, 4, "OBSERVAÇÃO",  1, 0, "C", 1);
  $oPdf->Cell( 54, 4, "DESTINO",     1, 0, "C", 1);
  $oPdf->Cell( 20, 4, "VALOR GASTO", 1, 1, "C", 1);
  $oPdf->setFont("arial", "", 7);
}


function imprimeModeloAjudaCusto( $aPedidos, $oPdf, $oGet ) {

  $aAjudaDestino = array();
  $lPrimeiro     = true;

  foreach ($aPedidos as $oPrestadora) {

    if ( !array_key_exists($oPrestadora->iDestino, $aAjudaDestino) ) {

      $oDestino           = new stdClass();
      $oDestino->sDestino = $oPrestadora->sDestino;
      $oDestino->nValor   = 0;

      $aAjudaDestino[$oPrestadora->iDestino] = $oDestino;
    }

    foreach ( $oPrestadora->aPacientes as $oPaciente ) {

      if ($oGet->tipo == 1 && empty($oPaciente->valor_ajuda)) {
        continue;
      }

      if ($lPrimeiro || $oPdf->getY() >= ($oPdf->h - 20)) {

        imprimeCabecalhoAjudaCusto($oPdf);
        $lPrimeiro = false;
      }

      $nValor = number_format($oPaciente->valor_ajuda, 2, ',', '');
      $oData  = new DBDate($oPaciente->nascimento);

      $oPdf->Cell( 15, 4, $oPaciente->cgs,                      1, 0, "C");
      $oPdf->Cell( 90, 4, $oPaciente->paciente,                 1, 0, "L");
      $oPdf->Cell( 20, 4, $oData->convertTo(DBDate::DATA_PTBR), 1, 0, "C");
      $oPdf->Cell( 23, 4, $oPaciente->cartaosus,                1, 0, "C");
      $oPdf->Cell( 57, 4, "",                                   1, 0, "L");
      $oPdf->Cell( 54, 4, substr($oPaciente->destino, 0, 35),   1, 0, "L");
      $oPdf->Cell( 20, 4, $nValor,                              1, 1, "R");

      $aAjudaDestino[$oPrestadora->iDestino]->nValor += $oPaciente->valor_ajuda;
    }
  }

  $oPdf->Ln();

  $iLinhasAjudaDestino = (count($aAjudaDestino) + 2) * 4;

  if ( $iLinhasAjudaDestino + $oPdf->getY() >= ($oPdf->h - 20) ) {
    $oPdf->addPage();
  }

  $nTotalGasto = 0;
  $oPdf->setX(215);
  $oPdf->setFont("arial", "B", 7);
  $oPdf->Cell( 54, 4, "DESTINO",     1, 0, "C", 1);
  $oPdf->Cell(  20, 4, "VALOR GASTO", 1, 1, "C", 1);
  $oPdf->setFont("arial", "", 7);

  foreach ($aAjudaDestino as $oAjudaDestino ) {

    if ( $oGet->tipo == 1 && empty($oAjudaDestino->nValor) ) {
      continue;
    }

    $nValor = number_format($oAjudaDestino->nValor, 2, ',', '');

    $oPdf->setX(215);
    $oPdf->Cell( 54, 4, substr($oAjudaDestino->sDestino, 0, 35), 1, 0, "L");
    $oPdf->Cell(  20, 4, $nValor,                  1, 1, "R");
    $nTotalGasto += $oAjudaDestino->nValor;
  }

  $nTotalGasto = number_format($nTotalGasto, 2, ',', '');
  $oPdf->setFont("arial", "B", 7);
  $oPdf->setX(215);
  $oPdf->Cell( 54, 4, "TOTAL:",     1, 0, "R", 1);
  $oPdf->Cell(  20, 4, $nTotalGasto, 1, 1, "R", 1);

}

function imprimeModeloViagem( $aPedidos, $oPdf ) {

  imprimeCabecalhoMotorista($oPdf);

  $iTotalPassageiros = 0;

  foreach ($aPedidos as $oPrestadora) {

    $oPdf->setFont("arial", "B", 8);
    $oPdf->Cell(  25, 4, "PRESTADORA: ");
    $oPdf->setFont("arial", "", 8);
    $oPdf->Cell( 145, 4, $oPrestadora->sPrestadora);
    $oPdf->setFont("arial", "B", 8);
    $oPdf->Cell(  20, 4, "DESTINO: ");
    $oPdf->setFont("arial", "", 8);
    $oPdf->Cell(  80, 4, substr($oPrestadora->sDestino, 0, 53), 0, 1);

    $lPrimeiro = true;

    foreach ($oPrestadora->aPacientes as $oPaciente ) {

      if ( $lPrimeiro || $oPdf->getY() >= ($oPdf->h - 20)  ) {

        if ($oPdf->getY() >= ($oPdf->h - 20))  {
          imprimeCabecalhoMotorista($oPdf);
        }

        cabecalhoViagem($oPdf);

        $lPrimeiro = false;
      }

      $aContato   = array();
      if (!empty($oPaciente->telefone)) {
        $aContato[] = $oPaciente->telefone;
      }
      if (!empty($oPaciente->celular)) {
        $aContato[] = $oPaciente->celular;
      }

      $oData = new DBDate($oPaciente->data_saida);

      $oPdf->cell(  15, 4, $oPaciente->cgs,                        1, 0, "C");
      $oPdf->cell(  95, 4, substr($oPaciente->paciente, 0, 57),                   1, 0, "L");
      $oPdf->cell(  25, 4, $oData->convertTo(DBDate::DATA_PTBR) . " - " . $oPaciente->hora_saida,   1, 0, "C");
      $oPdf->cell(  60, 4, substr($oPaciente->local_saida, 0, 34), 1, 0, "L");
      $oPdf->cell(  35, 4, implode(" / ", $aContato),                   1, 0, "L");
      $oPdf->cell(  20, 4, substr($oPaciente->identidade, 0, 13),  1, 0, "L");
      $oPdf->cell(  28, 4, "",                                     1, 1, "C");

      if ( count($oPaciente->aAcompanhante) > 0 ) {

        foreach ($oPaciente->aAcompanhante as $oAcompanhante ) {

          if ($oPdf->getY() >= ($oPdf->h - 20))  {

            imprimeCabecalhoMotorista($oPdf);
            cabecalhoViagem($oPdf);
          }

          $oPdf->cell(  15, 4, $oAcompanhante->cgs,              1, 0, "C");
          $oPdf->cell(  95, 4, substr( $oAcompanhante->paciente, 0, 57 ), 1, 0, "L");
          $oPdf->cell(  25, 4, "",                               1, 0);
          $oPdf->cell(  60, 4, "",                               1, 0);
          $oPdf->cell(  35, 4, "",                               1, 0);
          $oPdf->cell(  20, 4, "",                               1, 0);
          $oPdf->cell(  28, 4, "",                               1, 1);
        }
      }
    }
    $oPdf->setFont("arial", "B", 7);
    $oPdf->cell( 250, 4, "Passageiros:",             1, 0, "R" , 1);
    $oPdf->cell(  28, 4, $oPrestadora->iPassageiros, 1, 1, "L" , 1);

    $iTotalPassageiros += $oPrestadora->iPassageiros;

    $oPdf->Ln();
  }
  if ($oPdf->getY() >= ($oPdf->h - 20))  {
    $oPdf->addPage();
  }

  $oPdf->cell( 250, 4, "Total de Passageiros:", 1, 0, "R" , 1);
  $oPdf->cell(  28, 4, $iTotalPassageiros,      1, 1, "L" , 1);
}

$oPdf->Output();
