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
require_once(modification('model/contabilidade/contacorrente/AC/ContaCorrenteFonteRecurso.model.php'));

class BalancoPatrimonialDCASP2017 extends RelatoriosLegaisBase  {

  /**
   * Código do relatório no cadastrado no sistema
   * @var integer
   */
  const CODIGO_RELATORIO                  = 169;

  /**
   * Constantes que identificam as linhas iniciais e finais de cada quadro.
   */
  const QUADRO_PRINCIPAL_INICIAL          = 1;
  const QUADRO_PRINCIPAL_INICIO_PASSIVOS  = 19;
  const QUADRO_PRINCIPAL_FINAL            = 47;
  const QUADRO_ATIVOS_PASSIVOS_INICIAL    = 48;
  const QUADRO_ATIVOS_PASSIVOS_FINAL      = 56;
  const QUADRO_CONTAS_COMPENSACAO_INICIAL = 57;
  const QUADRO_CONTAS_COMPENSACAO_FINAL   = 68;

  /**
   * Constantes que identificam cada quadro.
   */
  const QUADRO_PRINCIPAL          = 1;
  const QUADRO_ATIVOS_PASSIVOS    = 2;
  const QUADRO_CONTAS_COMPENSACAO = 3;
  const QUADRO_SUPERAVIT          = 4;

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Linhas do relatório referente ao quadro principal.
   * @var stdClass[]
   */
  private $aQuadroPrincipal = array();

  /**
   * Linhas do relatório referente ao quadro de ativos e passivos.
   * @var stdClass[]
   */
  private $aQuadroAtivosPassivos = array();

  /**
   * Linhas do relatório referente ao quadro de contas de compensacao.
   * @var stdClass[]
   */
  private $aQuadroContasCompensacao = array();

  /**
   * Linhas do relatório referente ao quadro do Superávit/Déficit Financeiro.
   * @var stdClass[]
   */
  private $aQuadroSuperavitDeficit  = array();

  /**
   * Identifica quais quadros devem ser exibidos.
   * @var array
   */
  private $aRelatoriosExibir = array();

  /**
   * Identifica quais linhas são totalizadoras.
   * @var array
   */
  private $aLinhasTotalizadoras = array(7, 17, 18, 27, 36, 46, 47, 51, 55, 56, 62, 68);

  /**
   * Nome da instituição a ser exibida no relatório.
   * @var string
   */
  private $sDescricaoInstituicao;

  /**
   * Nome do período a ser exibido no relatório.
   * @var string
   */
  private $sDescricaoPeriodo;

  /**
   * Determina se deve buscar e exibir as informações do exercício anterior.
   * @var boolean
   */
  private $lExibirExercicioAnterior;

  /**
   * @param integer $iAnoUsu          Ano da emissão do relatório.
   * @param integer $iCodigoRelatorio Código do relatório cadastrado no sistema.
   * @param integer $iCodigoPeriodo   Código do período de emissão do relatório.
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $this->oPdf     = new PDFDocument();
    $this->iAltura  = 4;
    $this->iLargura = $this->oPdf->getAvailWidth() - 10;
  }

  /**
   * Adiciona uma nova página, reinserindo o cabeçalho do relatório.
   * @param string $sNomeQuadro Nome do quadro do relatório.
   * @param string $sNomeColuna Nome da columa de descrição do cabeçalho do quadro.
   */
  private function adicionarPagina($sNomeQuadro, $sNomeColuna) {

    $this->oPdf->clearHeaderDescription();
    $this->oPdf->addHeaderDescription($this->sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("BALANÇO PATRIMONIAL");
    $this->oPdf->addHeaderDescription($sNomeQuadro);
    $this->oPdf->addHeaderDescription("EXERCÍCIO : {$this->iAnoUsu}");
    $this->oPdf->addHeaderDescription("PERÍODO : {$this->sDescricaoPeriodo}");
    $this->oPdf->AddPage();
    if ($sNomeColuna !== null) {
      $this->escreverCabecalhoQuadro($sNomeColuna);
    }
  }

  /**
   * Verifica se a linha é uma das totalizadoras que deve ter bordas.
   * @param integer $iLinha Número da linha.
   *
   * @return bool
   */
  private function verificaTotalizadorFinal($iLinha) {
    return in_array($iLinha, $this->aLinhasTotalizadoras);
  }

  /**
   * Escreve uma linha do relatório.
   * @param stdClass $oLinha Linha a ser escrita.
   */
  private function escreverLinha(stdClass $oLinha) {

    $nPorcemtagemDescricao = 0.6;

    if ($oLinha->totalizar) {
      $this->oPdf->setBold(true);
    }

    $sBorda = "";
    if ($oLinha->totalizadorFinal) {
      $sBorda = "TB";
    }

    $sExercioAnterior = "-";
    if ($this->lExibirExercicioAnterior) {
      $sExercioAnterior = db_formatar($oLinha->vlrexanter, 'f');
    }

    $sDescricao = str_repeat(' ', $oLinha->nivel * 2) . $oLinha->descricao;

    if (isset($oLinha->codigo)) {
      $this->oPdf->Cell($this->iLargura * 0.05, $this->iAltura, $oLinha->codigo, $sBorda . 'R', 0, 'L');
      $nPorcemtagemDescricao -= 0.05;
    }

    $sEspacamento = str_repeat(' ', 20);
    $this->oPdf->Cell($this->iLargura * $nPorcemtagemDescricao, $this->iAltura, $sDescricao, $sBorda, 0, 'L');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, db_formatar($oLinha->vlrexatual, 'f').$sEspacamento, 'L' . $sBorda, 0, 'R');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, $sExercioAnterior.$sEspacamento, 'L' . $sBorda, 1, 'R');

    if ($oLinha->totalizadorFinal && !$oLinha->ultimaLinhaQuadro) {
      $this->oPdf->Cell($this->iLargura, $this->iAltura / 2, "", 'B', 1);
    }

    $this->oPdf->setBold(false);
  }

  /**
   * Escreve as assinaturas do quadro do relatório.
   *
   * @param string $sNomeQuadro Nome do quadro.
   * @param string $sNomeColuna Nome da coluna de descrição do quadro.
   */
  private function escreveAssinatura($sNomeQuadro, $sNomeColuna) {

    if ($this->oPdf->getAvailHeight() < 35) {
      $this->adicionarPagina($sNomeQuadro, $sNomeColuna);
    }
    $oAssinatura = new cl_assinatura();
    $this->oPdf->ln(18);
    assinaturas($this->oPdf, $oAssinatura, 'BG', false, false);
  }

  /**
   * Procura a descrição do período de acordo com o atributo iCodigoPeriodo
   * @return string
   */
  private function getDescricaoPeriodo() {

    $sNomePeriodo = "";
    $aPeriodos    = $this->getPeriodos();
    foreach ($aPeriodos as $oPeriodo) {

      if ($oPeriodo->o114_sequencial == $this->iCodigoPeriodo) {

        $sNomePeriodo = $oPeriodo->o114_descricao;
        break;
      }
    }
    return $sNomePeriodo;
  }

  /**
   * Popula os atributos que serão utilizados no cabeçalho para não precisar processa-los a cada página.
   */
  private function preparaCabecalhos() {

    $aListaInstituicoes = $this->getInstituicoes(true);

    if (count($aListaInstituicoes) > 1) {

      $oPrefeitura                 = InstituicaoRepository::getInstituicaoPrefeitura();
      $this->sDescricaoInstituicao = "INSTITUIÇÃO : {$oPrefeitura->getDescricao()} - CONSOLIDAÇÃO";
    } else {

      $oInstituicao                = current($aListaInstituicoes);
      $this->sDescricaoInstituicao = "INSTITUIÇÃO : {$oInstituicao->getDescricao()}";
    }

    $this->sDescricaoPeriodo = $this->getDescricaoPeriodo();
  }

  /**
   * Informa se um quadro do relatório deve ser exibido, de acordo com seu código.
   *
   * @param integer $iCodigo Código do quadro de acordo com as constantes desta classe.
   *
   * @return bool
   */
  private function exibirQuadroRelatorio($iCodigo) {
    return in_array($iCodigo, $this->aRelatoriosExibir);
  }

  /**
   * Prepara a linha para ser utilizada no relatório.
   * @param integer $iLinha            Número da linha.
   * @param integer $iLinhaFinalQuadro Número da linha final do quadro.
   *
   * @return stdClass
   */
  private function processarLinha($iLinha, $iLinhaFinalQuadro) {

    $oLinha                    = $this->aDados[$iLinha];
    $oLinha->ultimaLinhaQuadro = $iLinha == $iLinhaFinalQuadro;
    $oLinha->totalizadorFinal  = $this->verificaTotalizadorFinal($iLinha);
    return $oLinha;
  }

  /**
   * Popula os arrays de cada quadro caso deva exibi-los.
   */
  private function processarQuadros() {

    if ($this->exibirQuadroRelatorio(self::QUADRO_PRINCIPAL)) {

      for ($i = self::QUADRO_PRINCIPAL_INICIAL; $i <= self::QUADRO_PRINCIPAL_FINAL; $i++) {
        $this->aQuadroPrincipal[] = $this->processarLinha($i, self::QUADRO_PRINCIPAL_FINAL);
      }
    }

    if ($this->exibirQuadroRelatorio(self::QUADRO_ATIVOS_PASSIVOS)) {

      for ($i = self::QUADRO_ATIVOS_PASSIVOS_INICIAL; $i <= self::QUADRO_ATIVOS_PASSIVOS_FINAL; $i++) {
        $this->aQuadroAtivosPassivos[] = $this->processarLinha($i, self::QUADRO_ATIVOS_PASSIVOS_FINAL);;
      }
    }

    if ($this->exibirQuadroRelatorio(self::QUADRO_CONTAS_COMPENSACAO)) {

      for ($i = self::QUADRO_CONTAS_COMPENSACAO_INICIAL; $i <= self::QUADRO_CONTAS_COMPENSACAO_FINAL; $i++) {
        $this->aQuadroContasCompensacao[] = $this->processarLinha($i, self::QUADRO_CONTAS_COMPENSACAO_FINAL);;
      }
    }
  }

  /**
   * Realizar as configurações iniciais do pdf.
   */
  private function configurarPdf() {

    $this->oPdf->SetLeftMargin(10);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(true, 10);
    $this->oPdf->SetFillcolor(235);
    $this->oPdf->SetFont('arial', '', 6);
  }

  /**
   * Escreve o cabeçalho do quadro.
   * @param string $sNomeColuna Nome da coluna de descrição do quadro.
   */
  private function escreverCabecalhoQuadro($sNomeColuna) {

    $this->oPdf->setBold(true);
    $this->oPdf->Cell($this->iLargura * 0.60, $this->iAltura, $sNomeColuna, 'TB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exercício Atual", 'LTB', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.20, $this->iAltura, "Exercício Anterior", 'LTB', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Emite um quadro do relatório.
   *
   * @param string     $sNomeQuadro  Nome do quadro,
   * @param string     $sNomeColuna  Nome da coluna descrição,
   * @param stdClass[] $aDadosQuadro Linhas do quadro.
   */
  private function emitirQuadro($sNomeQuadro, $sNomeColuna, $aDadosQuadro) {

    /**
     * Se o quadro não foi processado
     */
    if (!$aDadosQuadro) return;

    $this->adicionarPagina($sNomeQuadro, $sNomeColuna);

    foreach ($aDadosQuadro as $oLinha) {

      if ($oLinha->ordem == self::QUADRO_PRINCIPAL_INICIO_PASSIVOS) {
        $sNomeColuna = "PASSIVO E PATRIMÔNIO LÍQUIDO";
        if ($this->oPdf->getAvailHeight() >= 18) {
          $this->escreverCabecalhoQuadro($sNomeColuna);
        }
      }

      if ($this->oPdf->getAvailHeight() < 18) {
        $this->adicionarPagina($sNomeQuadro, $sNomeColuna);
      }
      $this->escreverLinha($oLinha);
    }
    $this->escreverNotaExplicativa($sNomeQuadro, null);
    $this->escreveAssinatura($sNomeQuadro, null);
  }

  /**
   * Seta os quadros que devem ser exibidos de acordo com as constantes da classe.
   * @param array $aQuadrosExibir Array de constantes identificando quais quadros do relatório devem ser exibidos.
   */
  public function setExibirQuadros($aQuadrosExibir) {
    $this->aRelatoriosExibir = $aQuadrosExibir;
  }

  /**
   * Escreve a nota explicativa para o quadro.
   * @param string $sNomeQuadro Nome do quadro.
   * @param string $sNomeColuna Nome da coluna de descrição do quadro.
   */
  private function escreverNotaExplicativa($sNomeQuadro, $sNomeColuna) {

    $this->oPdf->Ln(2);
    if ($this->oPdf->getAvailWidth() < 10) {
      $this->adicionarPagina($sNomeQuadro, $sNomeColuna);
    }

    $this->getNotaExplicativa($this->oPdf, $this->iCodigoPeriodo, $this->oPdf->getAvailWidth());
  }

  /**
   * Sobreescreve o getDados.
   * @return array
   * @throws Exception
   */
  public function getDados() {

    if (!$this->exibirQuadroRelatorio(self::QUADRO_PRINCIPAL)
      && !$this->exibirQuadroRelatorio(self::QUADRO_ATIVOS_PASSIVOS)
      && !$this->exibirQuadroRelatorio(self::QUADRO_CONTAS_COMPENSACAO)) {
      return array();
    }

    $sWhereBalanceteVerificacao = " c61_instit in ({$this->getInstituicoes()}) ";

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');

    $oDataFinalAnterior = clone $this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    $rsBalanceteVerificacaoAtual =  db_planocontassaldo_matriz($this->iAnoUsu,
                                                               $this->getDataInicial()->getDate(),
                                                               $this->getDataFinal()->getDate(),
                                                               false,
                                                               $sWhereBalanceteVerificacao,
                                                               '',
                                                               'true',
                                                               'false'
    );
    db_query("drop table work_pl");

    if ($this->lExibirExercicioAnterior) {
      $rsBalanceteVerificacaoAnterior = db_planocontassaldo_matriz($this->iAnoUsu - 1,
                                                                   $oDataInicialAnterior->getDate(),
                                                                   $oDataFinalAnterior->getDate(),
                                                                   false,
                                                                   $sWhereBalanceteVerificacao,
                                                                   '',
                                                                   'true',
                                                                   'false'
      );
      db_query("drop table work_pl");
    }
    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $iLinha =>  $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $aColunas) {

        foreach ($aColunas->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      $oColunaAtual       = new stdClass();
      $oColunaAtual->nome = 'vlrexatual';

      $oColunaAnterior       = new stdClass();
      $oColunaAnterior->nome = 'vlrexanter';

      foreach ($oLinha->colunas as $oLinhaColuna) {

        if ($oLinhaColuna->o115_nomecoluna == 'vlrexatual') {
          $oColunaAtual->formula = $oLinhaColuna->o116_formula;
        }

        if ($this->lExibirExercicioAnterior && $oLinhaColuna->o115_nomecoluna == 'vlrexanter') {
          $oColunaAnterior->formula = $oLinhaColuna->o116_formula;
        }

      }

      if ($this->lExibirExercicioAnterior) {
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacaoAnterior,
                                                   $oLinha,
                                                   array($oColunaAnterior),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
        );
      }
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacaoAtual,
                                                 $oLinha,
                                                 array($oColunaAtual),
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
      );

      unset($oLinha->oLinhaRelatorio);
    }

    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }

  /**
   * Busca o saldo de todas as contas agrupado por recurso.
   * @param integer $iAno Ano para aplicação do período.
   *
   * @return stdClass[]
   */
  private function getSaldoPorRecurso($iAno) {

    $aLinhas = array();

    $iDiaFinal    = DBDate::getQuantidadeDiasMes($this->oPeriodo->o114_mesfinal, $iAno);
    $sDataInicial = "{$iAno}-01-01";
    $sDataFinal   = "{$iAno}-{$this->oPeriodo->o114_mesfinal}-{$iDiaFinal}";
    $sIntituicoes = $this->getInstituicoes();

    $sCampos = " c61_codigo, o15_descr, k13_conta, c61_instit ";
    $sOrdem  = " c61_codigo, k13_conta ";
    $sGroup  = " c61_codigo, o15_descr, k13_conta, c61_instit ";
    $sWhere  = " c60_codsis in (5,6) and (k13_limite is null or k13_limite >= '{$sDataFinal}')";

    $oDaoSaltes = new cl_saltes();
    $sSql       = $oDaoSaltes->sql_query_orcamento_recurso(null,
                                                           $sIntituicoes,
                                                           $iAno,
                                                           $sCampos,
                                                           $sOrdem,
                                                           $sWhere,
                                                           $sGroup
    );
    $rsRecursos = $oDaoSaltes->sql_record($sSql);

    $oLinha        = null;
    $nTotalRecurso = 0.0;
    for ($iRecurso = 0; $iRecurso < $oDaoSaltes->numrows; $iRecurso++) {

      $oRecursoConta = db_utils::fieldsMemory($rsRecursos, $iRecurso);
      if ($oLinha !== null  && $oLinha->codigo != $oRecursoConta->c61_codigo) {

        $oLinha->total = $nTotalRecurso;
        $aLinhas[]     = $oLinha;
        $nTotalRecurso = 0.0;
      }

      $oLinha            = new stdClass();
      $oLinha->codigo    = $oRecursoConta->c61_codigo;
      $oLinha->descricao = $oRecursoConta->o15_descr;

      $rsResultado = db_query("select fc_saltessaldo($oRecursoConta->k13_conta,'$sDataInicial','$sDataFinal', null, {$oRecursoConta->c61_instit})");

      $valores = pg_result($rsResultado, 0, 0);
      $valores = preg_split("/\s+/", $valores);
      if ($valores[0] == "1") {
        $nTotalRecurso += (float) str_replace(",", "", $valores[4]);
      }
    }
    $oLinha->total = $nTotalRecurso;
    $aLinhas[]     = $oLinha;

    return $aLinhas;
  }


  /**
   * Processa os valores da conta 82111 vinculadas a conta corrente 1 e 103
   * @param $iAno
   * @return array
   * @throws DBException
   */
  private function getValorDisponibilidadeFinanceira($iAno) {

    $iDiaFinal    = DBDate::getQuantidadeDiasMes($this->oPeriodo->getMesFinal(), $iAno);
    $oDataInicial = new DBDate("{$iAno}-01-01");
    $oDataFinal   = new DBDate("{$iAno}-{$this->oPeriodo->getMesFinal()}-{$iDiaFinal}");

    $sWhereConta = implode(' and ',array(
      "c60_estrut ilike '82111%'",
      "c61_anousu = {$iAno}",
      "c61_instit in ({$this->sListaInstit})"
    ));
    $oDaoPlanoConta    = new cl_conplanoreduz();
    $sSqlBuscaReduzido = $oDaoPlanoConta->sql_query(null, null, "array_to_string(array_accum(c61_reduz),',') as reduzidos", null, $sWhereConta);
    $rsBuscaReduzido   = db_query($sSqlBuscaReduzido);
    if (!$rsBuscaReduzido) {
      throw new DBException("Ocorreu um erro ao buscar os códigos de contas da conta com estrutural inicial 82111.");
    }
    unset($sWhereConta, $oDaoPlanoConta);
    $sReduzidos = db_utils::fieldsMemory($rsBuscaReduzido, 0)->reduzidos;

    $sWhereBetween = "c69_data between '{$oDataInicial->getDate(DBDate::DATA_EN)}' and '{$oDataFinal->getDate(DBDate::DATA_EN)}'";
    $sWhereConta = implode(' and ', array(
        "c19_reduz in ({$sReduzidos})",
        "c19_conplanoreduzanousu = {$iAno}",
        "c19_contacorrente in (".DisponibilidadeFinanceira::CONTA_CORRENTE.",".ContaCorrenteFonteRecurso::CONTA_CORRENTE.")",
        "($sWhereBetween) or ((c69_valor is null and (c29_credito is not null or c29_debito is not null)))",

				// implantado.
    ));

    // separado o group e reescrtito a sWhereConta , pois nao filtrava instituicao
    $sGroupBy      = " group by o15_codigo, o15_descr, c29_credito, c29_debito, c19_sequencial";
    $sInstituicoes = $this->getInstituicoes();
    $sWhereConta   = " c19_instit in ( {$sInstituicoes} ) and ({$sWhereConta}) {$sGroupBy} ";
    
    $sCampos = implode(',', array(
      "c19_sequencial",
      "o15_codigo as codigo_recurso",
      "o15_descr as descricao_recurso",
      "sum(case when c28_tipo = 'C' then c69_valor else 0 end) valor_credito",
      "sum(case when c28_tipo = 'D' then c69_valor else 0 end) valor_debito",
      "coalesce(c29_credito, 0) as valor_implantado_credito",
      "coalesce(c29_debito, 0) as valor_implantado_debito"
    ));

    $oDaoContaCorrente = new cl_contacorrentedetalhe();
    $sSqlBuscaContas   = $oDaoContaCorrente->sql_query_disponibilidade_financeira($sCampos, "o15_codigo", $sWhereConta);
    $rsBuscaContas     = db_query($sSqlBuscaContas);
    if (!$rsBuscaContas) {
      throw new DBException("Ocorreu um erro ao buscar os valores lançados para a conta 82111.");
    }

    $iTotalRegistros = pg_num_rows($rsBuscaContas);
    $aRecursos = array();
    for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

      $oStdConta = db_utils::fieldsMemory($rsBuscaContas, $iRow);
      $oStdLinha = new stdClass();
      $oStdLinha->codigo    = $oStdConta->codigo_recurso;
      $oStdLinha->descricao = $oStdConta->descricao_recurso;
      $oStdLinha->total     = $this->calcularTotal($oStdConta);

      if (empty($aRecursos[$oStdConta->codigo_recurso])) {
        $aRecursos[$oStdConta->codigo_recurso] = $oStdLinha;
      } else {
        $aRecursos[$oStdConta->codigo_recurso]->total += $oStdLinha->total;
      }
    }
    return $aRecursos;
  }

  /**
   * @param stdClass $oStdValor
   * @return float
   */
  private function calcularTotal(stdClass $oStdValor) {

    $nSomaCredito = ($oStdValor->valor_credito + $oStdValor->valor_implantado_credito);
    $nSomaDebito  = ($oStdValor->valor_debito + $oStdValor->valor_implantado_debito);
    return round(($nSomaCredito - $nSomaDebito), 2);
  }

  /**
   * Prepara um array de stdClass para ser escrito como linha no relatório.
   * @param stdClass[] $aDadosExercicioAtual
   * @param stdClass[] $aDadosExercicioAnterior
   *
   * @return stdClass[]
   */
  private function processaSuperavitDeficit($aDadosExercicioAtual, $aDadosExercicioAnterior) {

    $aLinhas                             = array();
    $oLinhaTotalizado                    = new stdClass();
    $oLinhaTotalizado->descricao         = "Total das Fontes de Recursos";
    $oLinhaTotalizado->vlrexatual        = 0.0;
    $oLinhaTotalizado->vlrexanter        = 0.0;
    $oLinhaTotalizado->totalizar         = 1;
    $oLinhaTotalizado->totalizadorFinal  = true;
    $oLinhaTotalizado->ultimaLinhaQuadro = true;
    $oLinhaTotalizado->ordem             = 0;
    $oLinhaTotalizado->nivel             = 1;

    $aRecursosAtual    = array();
    $aRecursosAnterior = array();

    foreach ($aDadosExercicioAnterior as $oStdRecursoAnterior) {
      $aRecursosAnterior[$oStdRecursoAnterior->codigo] = $oStdRecursoAnterior;
    }
    foreach ($aDadosExercicioAtual as $oAtual) {
      $aRecursosAtual[$oAtual->codigo] = $oAtual;
    }

    foreach ($aRecursosAnterior as $oStdRecursoAnterior) {

    	if (empty($oStdRecursoAnterior->total)) {
    		continue;
			}

      if (!array_key_exists($oStdRecursoAnterior->codigo, $aRecursosAtual)) {

      	$oAtual = new stdClass();
				$oAtual->codigo    = $oStdRecursoAnterior->codigo;
				$oAtual->descricao = $oStdRecursoAnterior->descricao;
				$oAtual->total     = 0.0;
				$aDadosExercicioAtual[] = $oAtual;
      }
    }

		foreach ($aRecursosAtual as $oStdRecursoAtual) {

			if (empty($oStdRecursoAtual->total)) {
				continue;
			}

			if (!array_key_exists($oStdRecursoAtual->codigo, $aRecursosAnterior)) {

				$oAnterior = new stdClass();
				$oAnterior->codigo    = $oStdRecursoAtual->codigo;
				$oAnterior->descricao = $oStdRecursoAtual->descricao;
				$oAnterior->total     = 0.0;
				$aDadosExercicioAnterior[] = $oAnterior;
			}
		}

		usort($aDadosExercicioAtual, function ($oItemA, $oItemB) {
			return $oItemA->codigo - $oItemB->codigo;
		});

		usort($aDadosExercicioAnterior, function ($oItemA, $oItemB) {
			return $oItemA->codigo - $oItemB->codigo;
		});

    foreach ($aDadosExercicioAtual as $oAtual) {

      $oLinha             = new stdClass();
      $oLinha->codigo     = $oAtual->codigo;
      $oLinha->descricao  = $oAtual->descricao;
      $oLinha->vlrexatual = $oAtual->total;
      $oLinha->vlrexanter = 0.0;

      if ($this->lExibirExercicioAnterior) {

        foreach ($aDadosExercicioAnterior as $oAnterior) {

          if ($oAtual->codigo == $oAnterior->codigo) {

            $oLinha->vlrexanter = $oAnterior->total;
            break;
          }
        }
      }

      $oLinhaTotalizado->vlrexatual += $oLinha->vlrexatual;
      $oLinhaTotalizado->vlrexanter += $oLinha->vlrexanter;

      $oLinha->totalizar         = 0;
      $oLinha->totalizadorFinal  = false;
      $oLinha->ultimaLinhaQuadro = false;
      $oLinha->ordem             = 0;
      $oLinha->nivel             = 1;
      $aLinhas[]                 = $oLinha;
    }
    $aLinhas[] = $oLinhaTotalizado;

    return $aLinhas;
  }

  /**
   * Busca os dados de Superavit/Deficit
   * @return stdClass[]|boolean
   */
  private function getSuperavitDeficit() {

    if (!$this->exibirQuadroRelatorio(self::QUADRO_SUPERAVIT)) {
      return false;
    }
    $aDadosExercicioAtual    = array();
    $aDadosExercicioAnterior = array();

    $sMetodoExercicioAtual = 'getSaldoPorRecurso';
    if ($this->iAnoUsu >= 2016) {
      $sMetodoExercicioAtual = 'getValorDisponibilidadeFinanceira';
    }

    $sMetodoExercicioAnterior = 'getSaldoPorRecurso';
    if ($this->iAnoUsu >= 2017) {
      $sMetodoExercicioAnterior = 'getValorDisponibilidadeFinanceira';
    }

    $aDadosExercicioAtual = $this->{$sMetodoExercicioAtual}($this->iAnoUsu);
    if ($this->lExibirExercicioAnterior) {
      $aDadosExercicioAnterior = $this->{$sMetodoExercicioAnterior}($this->iAnoUsu - 1);
    }
    return $this->processaSuperavitDeficit($aDadosExercicioAtual, $aDadosExercicioAnterior);
  }

  /**
   * Busca e processa os dados necessários para os quadros do relatório.
   */
  private function getDadosQuadros() {

    $this->aDados = $this->getDados();
    $this->processarQuadros();
    $this->aQuadroSuperavitDeficit = $this->getSuperavitDeficit();
  }

  /**
   * Realiza a emissão do relatório.
   */
  public function emitir() {
    $this->preparaCabecalhos();
    $this->getDadosQuadros();
    $this->configurarPdf();

    $this->emitirQuadro("QUADRO PRINCIPAL", "ATIVO" , $this->aQuadroPrincipal);
    $this->emitirQuadro("QUADRO DE ATIVOS E PASSIVOS FINANCEIROS E PERMANENTES\n(Lei nº 4.320/1964)", "", $this->aQuadroAtivosPassivos);
    $this->emitirQuadro("QUADRO DE CONTAS DE COMPENSAÇÃO\n(Lei nº 4.320/1964)",  "", $this->aQuadroContasCompensacao);
    $this->emitirQuadro("QUADRO DE SUPERÁVIT/DÉFICIT FINANCEIRO\n(Lei nº 4.320/1964)",  "FONTES DE RECURSOS", $this->aQuadroSuperavitDeficit);

    $this->oPdf->showPDF("balancoPatrimonialDCASP_" . time());
  }

  /**
   * @param boolean $lExibirExercicioAnterior
   */
  public function setExibirExercicioAnterior($lExibirExercicioAnterior) {
    $this->lExibirExercicioAnterior = $lExibirExercicioAnterior;
  }
}
