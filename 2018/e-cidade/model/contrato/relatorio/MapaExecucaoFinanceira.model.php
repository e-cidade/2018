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

require_once(modification('fpdf151/PDFDocument.php'));
/**
 * Class MapaExecucaoFinanceira
 */
class MapaExecucaoFinanceira {

  /**
   * @type PDFDocument
   */
  private $oPdf;

  /**
   * @type Acordo
   */
  private $oAcordo;

  /**
   * @type stdClass[]
   */
  private $aEmpenhos = array();

  /**
   * @param Acordo $oAcordo
   */
  public function __construct(Acordo $oAcordo) {

    $this->oAcordo = $oAcordo;
    $this->getEmpenhosAcordo();

    $oDepartamento = new DBDepartamento($this->oAcordo->getDepartamentoResponsavel());
    $this->oPdf    = new PDFDocument();
    $this->oPdf->open();
    $this->oPdf->addHeaderDescription('');
    $this->oPdf->addHeaderDescription('MAPA DE EXECUÇÃO FINANCEIRA');
    $this->oPdf->addHeaderDescription('');
    $this->oPdf->addHeaderDescription("Departamento: {$oDepartamento->getNomeDepartamento()}");
    $this->oPdf->addHeaderDescription("Código do Acordo: {$this->oAcordo->getCodigoAcordo()}");
    $this->oPdf->AddPage();
    $this->oPdf->SetFillColor(235);
    $this->oPdf->setFontSize(8);
  }

  public function emitir() {

    $this->imprimirCabecalho();

    foreach ($this->aEmpenhos as $iCodigo => $oStdEmpenho) {

      $this->imprimirEmpenho($iCodigo);
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, '', 'T', 1);
    }
    $this->oPdf->showPDF("MapaExecucaoFinanceira_{$this->oAcordo->getCodigoAcordo()}");
  }

  private function imprimirCabecalho() {

    $iAltura = 4;
    $this->oPdf->setBold(true);
    $this->oPdf->cell(15, $iAltura, 'Nº/Ano:');
    $this->oPdf->setBold(false);
    $this->oPdf->cell(40, $iAltura, $this->oAcordo->getNumeroAcordo().'/'.$this->oAcordo->getAno());

    $this->oPdf->setBold(true);
    $this->oPdf->cell(15, $iAltura, 'Valor:');
    $this->oPdf->setBold(false);
    $this->oPdf->cell(30, $iAltura, trim(db_formatar($this->oAcordo->getValorContrato(), 'f')), 0, false);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(18, $iAltura, 'Código:');
    $this->oPdf->setBold(false);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), $iAltura, $this->oAcordo->getCodigo(), 0, 1);

    /* segunda linha */
    $oDataInicial = $this->oAcordo->getDataInicialVigenciaOriginal();
    $oDataFinal   = $this->oAcordo->getDataFinalVigenciaOriginal();
    $this->oPdf->setBold(true);
    $this->oPdf->cell(15, $iAltura, 'Vigência:');
    $this->oPdf->setBold(false);
    $this->oPdf->cell(40, $iAltura, $oDataInicial->getDate(DBDate::DATA_PTBR) .' até '. $oDataFinal->getDate(DBDate::DATA_PTBR));

    $this->oPdf->setBold(true);
    $this->oPdf->cell(15, $iAltura, 'Processo:');
    $this->oPdf->setBold(false);
    $this->oPdf->cell(30, $iAltura, $this->oAcordo->getProcesso());


    $sContratado = $this->oAcordo->getContratado()->getCodigo() .' - '. substr($this->oAcordo->getContratado()->getNome(), 0, 32);
    $this->oPdf->setBold(true);
    $this->oPdf->cell(18, $iAltura, 'Contratado:');
    $this->oPdf->setBold(false);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), $iAltura, $sContratado, 0, true);
    $this->oPdf->ln(5);
  }


  private function imprimirEmpenho($iCodigo) {

    $oStdEmpenho = $this->aEmpenhos[$iCodigo];

    if ($this->oPdf->getAvailHeight() < 30) {
      $this->oPdf->addPage();
    }
    $iAltura = 4;
    $this->oPdf->setBold(true);
    $this->oPdf->cell(15, $iAltura, 'Empenho:', 0, 0, 'L', 1);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(30, $iAltura, $oStdEmpenho->numero, 0, 0, '', 1);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(10, $iAltura, 'Valor:', 0, 0, '', 1);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(30, $iAltura, trim(db_formatar($oStdEmpenho->valor, 'f')), 0, 0, '', 1);

    $this->oPdf->setBold(true);
    $this->oPdf->cell(10, $iAltura, 'Saldo:', 0, 0, '', 1);
    $this->oPdf->setBold(false);
    $this->oPdf->cell(30, $iAltura, trim(db_formatar($oStdEmpenho->saldo, 'f')), 0, 0, '', 1);
    $this->oPdf->cell($this->oPdf->getAvailWidth(), $iAltura, '', 0, 1, '', 0);

    foreach ($oStdEmpenho->ordens as $iCodigoOrdem => $oStdOrdem) {

      if ($this->oPdf->getAvailHeight() < 30) {
        $this->oPdf->addPage();
      }
      $this->oPdf->setBold(true);
      $this->oPdf->cell(10, $iAltura, '', "TL", 0, '', 0);
      $this->oPdf->cell(25, $iAltura, 'Ordem de Compra:', "T", 0, '', 0);
      $this->oPdf->setBold(false);
      $this->oPdf->cell(20, $iAltura, $iCodigoOrdem, 'T', 0, '', 0);
      $this->oPdf->cell($this->oPdf->getAvailWidth(), $iAltura, '', 'TR', 1, '', 0);

      $this->imprimirCabecalhoNota();
      foreach ($oStdOrdem->notas as $iCodigoNota => $oStdNota) {

        $this->oPdf->cell(40, $iAltura, $oStdNota->codigo, 'L', 0, 'C');
        $this->oPdf->cell(40, $iAltura, trim(db_formatar($oStdNota->valor, 'f')), 0, 0, 'R');
        $this->oPdf->cell(40, $iAltura, trim(db_formatar($oStdNota->valorpago, 'f')), 0, 0, 'R');
        $this->oPdf->cell($this->oPdf->getAvailWidth(), $iAltura, '', 'R', 1, 'R');
      }
      $this->oPdf->cell($this->oPdf->getAvailWidth(), 3, '', 'LR', 1, 'R');
    }
  }


  private function imprimirCabecalhoNota() {

    if ($this->oPdf->getAvailHeight() < 30) {
      $this->oPdf->addPage();
    }
    $iAltura = 4;
    $this->oPdf->setBold(true);
    $this->oPdf->cell(40, $iAltura, 'Nota Fiscal', "L", 0, 'C');
    $this->oPdf->cell(40, $iAltura, 'Valor', 0, 0, 'C');
    $this->oPdf->cell(40, $iAltura, 'Valor Pago', 0, 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), $iAltura, '', 'R', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * @return \stdClass[]
   * @throws \BusinessException
   * @throws \DBException
   */
  private function getEmpenhosAcordo() {

    $aWhere = array(
      "acordo.ac16_sequencial = {$this->oAcordo->getCodigo()}",
      "(pagordemnota.e71_anulado is false or pagordemnota.e71_anulado is null)"
    );
    $sCampos = "e69_numero, e69_codnota, e70_valor, e53_vlrpag, m51_codordem, e60_numemp, e60_codemp, e60_anousu, e60_vlremp";
    $oDaoAcordo      = new cl_acordo();
    $sSqlBuscaAcordo = $oDaoAcordo->sql_query_movimentacao_empenho(null, $sCampos, null, implode(' and ', $aWhere));

    $rsBuscaAcordos  = db_query($sSqlBuscaAcordo);
    $iTotalRegistros = pg_num_rows($rsBuscaAcordos);

    if (!$rsBuscaAcordos) {
      throw new DBException('Não foi possível buscar os dados do acordo.');
    }

    if ($iTotalRegistros == 0) {
      throw new BusinessException('Nenhum empenho vinculado ao acordo.');
    }

    for ($iRowEmpenho = 0; $iRowEmpenho < $iTotalRegistros; $iRowEmpenho++) {

      $oStdEmpenho = db_utils::fieldsMemory($rsBuscaAcordos, $iRowEmpenho);
      if ( !array_key_exists($oStdEmpenho->e60_numemp, $this->aEmpenhos) ) {

        $oStdInformacaoEmpenho = $this->getDadosEmpenho($oStdEmpenho);
        $oStdDadosOrdem        = $this->getDadosOrdem($oStdEmpenho);

        if (!empty($oStdDadosOrdem->codigo)) {
          $oStdDadosNota = $this->getDadosNota($oStdEmpenho);

          if (!empty($oStdDadosNota->codigo)) {
            $oStdDadosOrdem->notas[$oStdEmpenho->e69_codnota] = $oStdDadosNota;
          }

          $oStdInformacaoEmpenho->ordens[$oStdEmpenho->m51_codordem] = $oStdDadosOrdem;
        }

        $this->aEmpenhos[$oStdEmpenho->e60_numemp] = $oStdInformacaoEmpenho;

      } else {

        $aOrdensDoEmpenho = $this->aEmpenhos[$oStdEmpenho->e60_numemp]->ordens;
        if ( !empty($oStdEmpenho->m51_codordem) && array_key_exists($oStdEmpenho->m51_codordem, $aOrdensDoEmpenho) ) {

          $oStdDadosNota = $this->getDadosNota($oStdEmpenho);

          if (!empty($oStdDadosNota->codigo)) {
            $this->aEmpenhos[$oStdEmpenho->e60_numemp]->ordens[$oStdEmpenho->m51_codordem]->notas[$oStdEmpenho->e69_codnota] = $oStdDadosNota;
          }

        } else if (!empty($oStdEmpenho->m51_codordem)) {

          $oStdDadosOrdem = $this->getDadosOrdem($oStdEmpenho);
          $oStdDadosNota  = $this->getDadosNota($oStdEmpenho);

          if (!empty($oStdDadosNota->codigo)) {
            $oStdDadosOrdem->notas[$oStdEmpenho->e69_codnota] = $oStdDadosNota;
          }

          $this->aEmpenhos[$oStdEmpenho->e60_numemp]->ordens[$oStdEmpenho->m51_codordem] = $oStdDadosOrdem;
        }
      }
    }
    $this->processarSaldoEmpenho();
    return $this->aEmpenhos;
  }

  /**
   * Processa o saldo dos empenhos vinculados ao acordo.
   * @return bool
   */
  private function processarSaldoEmpenho() {

    foreach ($this->aEmpenhos as $iCodigoEmpenho => $oStdEmpenho) {

      $nSaldoEmpenho = 0;
      foreach ($oStdEmpenho->ordens as $iCodigoOrdem => $oStdOrdem) {

        foreach ($oStdOrdem->notas as $iCodigoNota => $oStdNota) {
          $nSaldoEmpenho += $oStdNota->valorpago;
        }
      }
      $this->aEmpenhos[$iCodigoEmpenho]->saldo = ($oStdEmpenho->valor - $nSaldoEmpenho);
    }
    return true;
  }


  /**
   * Monta o objeto com os dados da nota.
   * @param stdClass $oStdDadosEmpenho
   * @return stdClass
   */
  private function getDadosNota(stdClass $oStdDadosEmpenho) {


    $oStdDadosNota            = new stdClass();
    $oStdDadosNota->codigo    = $oStdDadosEmpenho->e69_numero;
    $oStdDadosNota->valor     = $oStdDadosEmpenho->e70_valor;
    $oStdDadosNota->valorpago = $oStdDadosEmpenho->e53_vlrpag;
    return $oStdDadosNota;
  }

  /**
   * @param stdClass $oStdDadosEmpenho
   * @return stdClass
   */
  private function getDadosOrdem(stdClass $oStdDadosEmpenho) {

    $oStdDadosOrdem         = new stdClass();
    $oStdDadosOrdem->codigo = $oStdDadosEmpenho->m51_codordem;
    $oStdDadosOrdem->notas  = array();
    return $oStdDadosOrdem;
  }

  /**
   * @param stdClass $oStdDadosEmpenho
   * @return stdClass
   */
  private function getDadosEmpenho(stdClass $oStdDadosEmpenho) {

    $oStdInformacaoEmpenho         = new stdClass();
    $oStdInformacaoEmpenho->codigo = $oStdDadosEmpenho->e60_numemp;
    $oStdInformacaoEmpenho->numero = $oStdDadosEmpenho->e60_codemp.'/'.$oStdDadosEmpenho->e60_anousu;
    $oStdInformacaoEmpenho->valor  = $oStdDadosEmpenho->e60_vlremp;
    $oStdInformacaoEmpenho->saldo  = 0;
    $oStdInformacaoEmpenho->ordens = array();
    return $oStdInformacaoEmpenho;
  }
}
