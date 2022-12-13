<?php
/**
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

/**
 * Class RelatorioNotasPendentes
 */
class RelatorioNotasPendentes {

  const PATH_MENSAGEM = 'financeiro/empenho/classificacaocredor/RelatorioNotasPendentes.';

  private $iAltura = 4;

  /**
   * @type PDFDocument
   */
  private $oPdf;

  /**
   * @type Instituicao
   */
  private $oInstituicao;

  /**
   * @type stdClass[]
   */
  private $aMovimentosJustificar = array();

  /**
   * @type stdClass[]
   */
  private $aMovimentosImpressao = array();

  /**
   * @param array       $aMovimentosJustificar
   * @param Instituicao $oInstituicao
   * @throws ParameterException
   */
  public function __construct(array $aMovimentosJustificar, Instituicao $oInstituicao) {

    $this->aMovimentosJustificar = $aMovimentosJustificar;
    if (count($this->aMovimentosJustificar) == 0) {
      throw new ParameterException(_M(self::PATH_MENSAGEM . 'movimento_invalidos'));
    }
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Emite o documento PDF
   * @throws DBException
   */
  public function emitir() {

    $this->prepararDados();

    $this->oPdf = new PDFDocument('P');
    $this->oPdf->SetFillColor(220);
    $this->oPdf->addHeaderDescription('MOVIMENTOS PENDENTES DE PAGAMENTO');
    $this->oPdf->addHeaderDescription('');
    $this->oPdf->addHeaderDescription($this->oInstituicao->getDescricao());
    $this->oPdf->Open();
    $this->oPdf->AddPage();
    $this->oPdf->SetFontSize(8);

    $lPrimeiraImpressao = true;
    foreach ($this->aMovimentosImpressao as $iCodigoClassificacao => $oStdClassificacao) {

      if (!$lPrimeiraImpressao) {
        $this->oPdf->Ln(5);
      }
      $this->imprimirCabecalho($oStdClassificacao->descricao);
      foreach ($oStdClassificacao->movimentos as $oStdMovimento) {

        if ($this->oPdf->getAvailHeight() < 30) {

          $this->oPdf->AddPage();
          $this->imprimirCabecalho($oStdClassificacao->descricao);
        }
        $this->oPdf->Cell(20, $this->iAltura, "{$oStdMovimento->e60_codemp}/{$oStdMovimento->e60_anousu}", "LR", 0, 'C');
        $this->oPdf->Cell(20, $this->iAltura, $oStdMovimento->e69_codnota, "LR", 0, 'C');
        $this->oPdf->Cell(20, $this->iAltura, $oStdMovimento->e53_codord, "LR", 0, 'C');
        $this->oPdf->Cell(90, $this->iAltura, substr($oStdMovimento->z01_nome, 0, 85), "LR", 0, 'L');
        $this->oPdf->Cell(20, $this->iAltura, $oStdMovimento->e69_dtvencimento->getDate(DBDate::DATA_PTBR), "LR", 0, 'C');
        $this->oPdf->Cell(20, $this->iAltura, trim(db_formatar(round($oStdMovimento->e81_valor, 2), 'f')), "LR", 1, 'R');
      }

      $this->oPdf->setBold(true);
      $this->oPdf->Cell(170, $this->iAltura, "TOTAL:", 1, 0, 'R');
      $this->oPdf->Cell(20, $this->iAltura, trim(db_formatar(round($oStdClassificacao->valor_total, 2), 'f')), 1, 1, 'R');
      $this->oPdf->setBold(false);
      $lPrimeiraImpressao = false;
    }
    $this->oPdf->showPDF();
  }

  /**
   * @param $sDescricao string Descrição da Lista de Classificação configurada pelo usuario
   */
  private function imprimirCabecalho($sDescricao) {

    $this->oPdf->setBold(true);
    $sClassificacao = "Lista de Classificação: ".$sDescricao;
    $this->oPdf->Cell($this->oPdf->getAvailWidth(), 4, $sClassificacao, 1, 1, "L", 1);
    $this->oPdf->Cell(20, $this->iAltura, "Empenho", 1, 0, 'C');
    $this->oPdf->Cell(20, $this->iAltura, "Liquidação", 1, 0, 'C');
    $this->oPdf->Cell(20, $this->iAltura, "O. P.", 1, 0, 'C');
    $this->oPdf->Cell(90, $this->iAltura, "Credor", 1, 0, 'C');
    $this->oPdf->Cell(20, $this->iAltura, "Vencimento", 1, 0, 'C');
    $this->oPdf->Cell(20, $this->iAltura, "Valor", 1, 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Configura os dados a serem impressos no relatório
   * @return bool
   * @throws DBException
   */
  private function prepararDados() {

    $aOrdemSelecionada = array();
    foreach ($this->aMovimentosJustificar as $iCodigoClassificacao => $oStdMovimentosSelecionado) {
      array_push($aOrdemSelecionada, $oStdMovimentosSelecionado->iCodNota);
    }
    $sOrdensSelecionadas = !empty($aOrdemSelecionada) ? implode(',', $aOrdemSelecionada) : null;

    foreach ($this->aMovimentosJustificar as $oStdMovimento) {

      list($iCodigoEmpenho, $iAno) = explode('/', $oStdMovimento->sNumeroEmpenho);
      $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($iCodigoEmpenho, $iAno, $this->oInstituicao);
      $iCodigoClassificacao = $oEmpenho->getCodigoListaClassificacaoCredor();

      $aCampos = array(
        'e60_numemp',
        'e60_codemp',
        'e60_anousu',
        'e69_codnota',
        'e69_dtvencimento',
        'e81_valor',
        'e81_codmov',
        'e53_codord',
        'z01_nome',
        'cc30_descricao',
      );

      $oDataVencimento = new DBDate($oStdMovimento->dtVencimento);
      $aWhere   = array();
      $aWhere[] = "cc31_classificacaocredores = {$iCodigoClassificacao}";
      $aWhere[] = "e69_dtvencimento < '{$oDataVencimento->getDate()}'";
      $aWhere[] = "e69_dtvencimento is not null";
      $aWhere[] = "e71_anulado is false";
      $aWhere[] = "e81_cancelado is null";
      $aWhere[] = "e53_vlrpag < (e70_valor-e70_vlranu)";
      $aWhere[] = "e85_codmov is null";
      $aWhere[] = "e60_instit = {$this->oInstituicao->getCodigo()}";
      if (!empty($sOrdensSelecionadas)) {
        $aWhere[] = "e50_codord not in ({$sOrdensSelecionadas})";
      }
      $oDaoEmpnota         = new cl_empnota();
      $sSqlBuscaMovimentos = $oDaoEmpnota->sql_query_classificacaocredores(implode(',',$aCampos), implode(' and ', $aWhere));
      $rsBuscaNotas        = db_query($sSqlBuscaMovimentos);

      if (!$rsBuscaNotas) {
        throw new DBException(_M(self::PATH_MENSAGEM . "erro_busca_movimento_pendente"));
      }

      $iTotalRegistros = pg_num_rows($rsBuscaNotas);
      if ($iTotalRegistros == 0) {
        continue;
      }

      for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

        $oStdMovimentoPendente = db_utils::fieldsMemory($rsBuscaNotas, $iRow);
        $oStdMovimentoPendente->e69_dtvencimento = new DBDate($oStdMovimentoPendente->e69_dtvencimento);
        if (empty($this->aMovimentosImpressao[$iCodigoClassificacao])) {

          $this->aMovimentosImpressao[$iCodigoClassificacao] = array();
          $this->aMovimentosImpressao[$iCodigoClassificacao] = new stdClass();
          $this->aMovimentosImpressao[$iCodigoClassificacao]->descricao   = $oStdMovimentoPendente->cc30_descricao;
          $this->aMovimentosImpressao[$iCodigoClassificacao]->valor_total = $oStdMovimentoPendente->e81_valor;
          $this->aMovimentosImpressao[$iCodigoClassificacao]->movimentos[$oStdMovimentoPendente->e81_codmov] = $oStdMovimentoPendente;
        } else {

          $this->aMovimentosImpressao[$iCodigoClassificacao]->movimentos[$oStdMovimentoPendente->e81_codmov] = $oStdMovimentoPendente;
          $this->aMovimentosImpressao[$iCodigoClassificacao]->valor_total += $oStdMovimentoPendente->e81_valor;
        }
      }
    }

    if (empty($this->aMovimentosImpressao)) {
      throw new BusinessException(_M(self::PATH_MENSAGEM . "nenhum_movimento_pendente"));
    }

    return true;
  }
}