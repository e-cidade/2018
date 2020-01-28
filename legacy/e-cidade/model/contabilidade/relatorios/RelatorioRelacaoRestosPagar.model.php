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

class RelatorioRelacaoRestosPagar {

  const RESTOS_PROCESSADOS = 1;
  const RESTOS_NAO_PROCESSADOS = 2;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * @var integer
   */
  private $iExercicio;

  /**
   * @var integer
   */
  private $iTipo;

  /**
   * @var string - Dados do Banco
   */
  private $sBanco = '';

  /**
   * @param integer $iTipo
   * @param integer $iExercicio
   */
  public function __construct($iTipo, $iExercicio) {

    if ($iTipo != self::RESTOS_PROCESSADOS && $iTipo != self::RESTOS_NAO_PROCESSADOS) {
      throw new Exception("O Tipo informado é invalido.");
    }

    $this->iTipo      = $iTipo;
    $this->iExercicio = $iExercicio;
  }

  /**
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  public function gerar() {

    $this->oPdf = new PDFDocument('L');
    $this->oPdf->disableHeaderDefault();
    $this->oPdf->disableFooterDefault();
    $this->oPdf->SetAutoPageBreak(false, 8);
    $this->oPdf->open();
    $this->oPdf->addPage();

    $this->oPdf->setFontFamily('Arial');
    $this->oPdf->setFontSize(8);

    /**
     * Busca os itens para gerar o relatório
     */
    $oDaoRestosAPagar = new cl_empresto();

    $sLiquidar = "(e91_vlremp - (e91_vlranu + e91_vlrliq))";
    $sPagar    = "(e91_vlrliq - e91_vlrpag)";

    $sCampos  = "o58_unidade, o58_funcao, o58_subfuncao, o58_programa,       \n";
    $sCampos .= "o58_projativ, e150_numeroprocesso, z01_nome, o56_elemento,  \n";
    $sCampos .= "o58_codigo, e60_codemp, e60_anousu, e60_numemp, o58_codigo, \n";
    $sCampos .= "{$sLiquidar} as aliquidar,                                  \n";
    $sCampos .= "{$sPagar} as liquidado                                      \n";

    if ($this->iTipo == self::RESTOS_PROCESSADOS) {
      $sWhere = "{$sPagar} > 0";
    } else {
      $sWhere = "{$sLiquidar} > 0";
    }

    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_query_restos( $sCampos,
                                                             "o58_codigo, e60_codemp::integer, e91_anousu",
                                                             "{$sWhere} and e60_anousu = {$this->iExercicio} and e60_instit = {$this->oInstituicao->getCodigo()}" );

    $rsRestosaPagar = $oDaoRestosAPagar->sql_record( $sSqlRestosaPagar );

    if ($oDaoRestosAPagar->numrows > 0) {
      $this->iRecurso = db_utils::fieldsMemory($rsRestosaPagar, 0)->o58_codigo;
    } else {
      $this->escreveCabecalho();
    }

    $nValorTotal = 0;
    $iRecurso    = null;

    for ($iRow = 0; $iRow < $oDaoRestosAPagar->numrows; $iRow++) {

      $oDadosItem = db_utils::fieldsMemory($rsRestosaPagar, $iRow);

      if ($iRow == 0) {

        $this->setBanco($oDadosItem->o58_codigo);
        $this->escreveCabecalho();
        $this->escreveCabecalhoTabela();
      }

      if (empty($iRecurso)) {
        $iRecurso = $oDadosItem->o58_codigo;
      }

      if ($this->oPdf->getAvailHeight() <= 25) {

        $this->escreveTotalizador($nValorTotal);
        $this->escreveAssinatura();
        $this->oPdf->addPage();

        if ($iRecurso != $oDadosItem->o58_codigo) {

          $this->setBanco($oDadosItem->o58_codigo);
          $this->escreveCabecalho();
        }

        $this->escreveCabecalhoTabela();

      } else if ($iRecurso != $oDadosItem->o58_codigo) {

        $this->escreveTotalizador($nValorTotal);
        $this->escreveAssinatura();
        $this->oPdf->addPage();
        $this->setBanco($oDadosItem->o58_codigo);
        $this->escreveCabecalho();
        $this->escreveCabecalhoTabela();
      }

      $nValorTotal += $this->escreveItem( $oDadosItem );
      $iRecurso = $oDadosItem->o58_codigo;
    }

    $this->escreveTotalizador($nValorTotal);
    $this->escreveAssinatura();

    $this->oPdf->showPDF("Modelo5_RestosAPagar_" . time());
  }

  /**
   * Seta os dados do banco para o cabecalho do relatório
   * @param string $iRecurso
   */
  private function setBanco($iRecurso = null) {

    $this->sBanco = '';

    if (empty($iRecurso)) {
      return false;
    }

    $oDaoConplano = new cl_conplano();
    $sSqlConplano = $oDaoConplano->sql_query_dados_banco( null,
                                                          null,
                                                          "conplanoconta.*, c61_reduz",
                                                          "c61_reduz desc",
                                                          "c61_codigo = {$iRecurso} and c61_instit = {$this->oInstituicao->getCodigo()} and c63_codcon is not null" );
    $rsContaBancaria = $oDaoConplano->sql_record( "{$sSqlConplano} limit 1" );

    if ($oDaoConplano->numrows > 0) {

      $oDadosBanco = db_utils::fieldsMemory($rsContaBancaria, 0);
      $this->sBanco = "{$oDadosBanco->c63_banco} / {$oDadosBanco->c63_conta}-{$oDadosBanco->c63_dvconta} / {$oDadosBanco->c63_agencia}-{$oDadosBanco->c63_dvagencia}";
    }

    return true;
  }

  /**
   * Escreve a assinatura do relatório
   */
  private function escreveAssinatura() {

    $iAvailHeight = $this->oPdf->getAvailHeight();
    if ($iAvailHeight > 16) {
      $this->oPdf->setY($this->oPdf->getY() + $iAvailHeight - 16);
    }

    $oLibDocumento = new libdocumento(5015);
    $aParagrafos   = $oLibDocumento->getDocParagrafos();

    if (isset($aParagrafos[1])) {
      eval($aParagrafos[1]->oParag->db02_texto);
    }
  }

  /**
   * Escreve o totalizador no final de cada página
   * @param  float $nValor Valor a ser impresso
   */
  private function escreveTotalizador($nValor) {

    $this->oPdf->setBold(true);

    $nWidth = $this->oPdf->getAvailWidth();
    $this->oPdf->cell($nWidth*0.79, 4, '', 'T');
    $this->oPdf->cell($nWidth*0.11, 4, "TOTAL/TRANSPORTE", 'T:B:L');
    $this->oPdf->cell($nWidth*0.10, 4, number_format($nValor, 2, ',', '.'), 'T:B:R', 1, 'R');
  }

  /**
   * Escreve o Cabecalho do relatório
   */
  private function escreveCabecalho() {

    $this->oPdf->setFontSize(9);
    $this->oPdf->setBold(true);

    $nWidth = $this->oPdf->getAvailWidth();

    $this->oPdf->cell($nWidth, 5, "MODELO 5", 0, 1, 'C');
    $this->oPdf->cell($nWidth, 6, "RELAÇÃO DE RESTOS A PAGAR", 'T:L:R', 1, 'C');
    $this->oPdf->cell($nWidth, 3, "", 'L:R:B', 1);

    $this->oPdf->setBold(false);
    $this->oPdf->setFontSize(7);

    $this->oPdf->cell($nWidth*0.45, 4, "Órgão / Entidade / Fundo", 'L:R');
    $this->oPdf->cell($nWidth*0.22, 4, "Município", 'R');
    $this->oPdf->cell($nWidth*0.08, 4, "Exercicío", 'R');
    $this->oPdf->cell($nWidth*0.25, 4, "Banco / Conta / Agência", 'R', 1);

    $this->oPdf->setBold(true);

    $this->oPdf->cell($nWidth*0.45, 5, $this->oInstituicao->getDescricao(), 'L:R');
    $this->oPdf->cell($nWidth*0.22, 5, $this->oInstituicao->getMunicipio(), 'R');
    $this->oPdf->cell($nWidth*0.08, 5, $this->iExercicio, 'R');
    $this->oPdf->cell($nWidth*0.25, 5, $this->sBanco, 'R', 1);

    $this->oPdf->cell($nWidth, 4, '', 'T:R:L', 1);

    $this->oPdf->cell($nWidth*0.38, 4, '', 'L');
    $this->oPdf->cell($nWidth*0.015, 4, ($this->iTipo == self::RESTOS_PROCESSADOS ? 'x' : ''), 1, 0, 'C');
    $this->oPdf->cell($nWidth*0.15, 4, "Processados");
    $this->oPdf->cell($nWidth*0.015, 4, ($this->iTipo == self::RESTOS_NAO_PROCESSADOS ? 'x' : ''), 1, 0, 'C');
    $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "Não Processados", 'R', 1);

    $this->oPdf->cell($nWidth, 4, '', 'B:R:L', 1);
  }

  /**
   * Escreve o Cabecalho da tabela dos itens
   */
  public function escreveCabecalhoTabela() {

    $this->oPdf->setFontSize(7);
    $this->oPdf->setBold(true);
    $this->oPdf->setAutoNewLineMulticell(false);

    $nWidth = $this->oPdf->getAvailWidth();

    $this->oPdf->multiCell( $nWidth*0.05, 4.5, "Incrição\nNº", 1, 'C');
    $this->oPdf->multiCell( $nWidth*0.10, 4.5, "Processo\nNº", 1, 'C');
    $this->oPdf->cell(      $nWidth*0.45, 9, "Nome/Razão Social", 1, 0, 'C');
    $this->oPdf->cell(      $nWidth*0.11, 9, "Programa de Trabalho", 1, 0, 'C');
    $this->oPdf->multiCell( $nWidth*0.08, 4.5, "Natureza da\nDespesa", 1, 'C');
    $this->oPdf->cell(      $nWidth*0.04, 9, "Fonte", 1, 0, 'C');
    $this->oPdf->multiCell( $nWidth*0.07, 4.5, "Nº do\nEmpenho", 1, 'C');
    $this->oPdf->cell(      $nWidth*0.10, 9, "Valor R$", 1, 1, 'C');
  }

  /**
   * Escreve os dados do item do relatório
   * @param  StdClass $oItem
   * @return float Valor do item a ser totalizado
   */
  public function escreveItem($oItem) {

    $this->oPdf->setFontSize(7);
    $this->oPdf->setBold(false);

    $nWidth = $this->oPdf->getAvailWidth();

    $sPrograma  = str_pad($oItem->o58_funcao, 2, '0', STR_PAD_LEFT);
    $sPrograma .= '.' . str_pad($oItem->o58_subfuncao, 3, '0', STR_PAD_LEFT);
    $sPrograma .= '.' . str_pad($oItem->o58_programa, 4, '0', STR_PAD_LEFT);
    $sPrograma .= '.' . str_pad($oItem->o58_projativ, 4, '0', STR_PAD_LEFT);

    if ($this->iTipo == self::RESTOS_PROCESSADOS) {
      $nValor = $oItem->liquidado;
    } else {
      $nValor = $oItem->aliquidar;
    }

    $this->oPdf->cell($nWidth*0.05, 4, $oItem->e60_numemp, 'L:R', 0, 'C');
    $this->oPdf->cell($nWidth*0.10, 4, $oItem->e150_numeroprocesso, 'L:R', 0, 'C');
    $this->oPdf->cell($nWidth*0.45, 4, $oItem->z01_nome, 'L:R', 0, 'L');
    $this->oPdf->cell($nWidth*0.11, 4, $sPrograma, 'L:R', 0, 'C');
    $this->oPdf->cell($nWidth*0.08, 4, substr($oItem->o56_elemento, 1, 7), 'L:R', 0, 'C');
    $this->oPdf->cell($nWidth*0.04, 4, $oItem->o58_codigo, 'L:R', 0, 'C');
    $this->oPdf->cell($nWidth*0.07, 4, "{$oItem->e60_codemp}/{$oItem->e60_anousu}", 'L:R', 0, 'R');
    $this->oPdf->cell($nWidth*0.10, 4, number_format($nValor, 2, ',', '.'), 'L:R', 1, 'R');

    return $nValor;
  }

}
